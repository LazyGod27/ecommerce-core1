<?php

namespace App\Services;

use App\Models\PayoutRequest;
use App\Models\Seller;
use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PayoutService
{
    /**
     * Create payout request for seller
     */
    public function createPayoutRequest(Seller $seller, array $data): PayoutRequest
    {
        try {
            DB::beginTransaction();

            // Get pending earnings for the seller
            $pendingEarnings = $seller->earnings()
                ->where('status', 'pending')
                ->where('amount', '>', 0)
                ->get();

            if ($pendingEarnings->isEmpty()) {
                throw new \Exception('No pending earnings available for payout');
            }

            $totalAmount = $pendingEarnings->sum('net_amount');
            $minPayoutAmount = config('platform.min_payout_amount', 100);

            if ($totalAmount < $minPayoutAmount) {
                throw new \Exception("Minimum payout amount is {$minPayoutAmount}. Current available: {$totalAmount}");
            }

            // Create payout request
            $payoutRequest = PayoutRequest::create([
                'seller_id' => $seller->id,
                'amount' => $totalAmount,
                'currency' => 'PHP',
                'payment_method' => $data['payment_method'] ?? 'bank_transfer',
                'bank_details' => $data['bank_details'] ?? null,
                'status' => 'pending',
                'requested_at' => now(),
                'earnings_ids' => $pendingEarnings->pluck('id')->toArray()
            ]);

            // Update earnings status to 'processing'
            $pendingEarnings->each(function ($earning) {
                $earning->update(['status' => 'processing']);
            });

            Log::info('Payout request created', [
                'payout_request_id' => $payoutRequest->id,
                'seller_id' => $seller->id,
                'amount' => $totalAmount,
                'earnings_count' => $pendingEarnings->count()
            ]);

            DB::commit();

            return $payoutRequest;

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to create payout request', [
                'seller_id' => $seller->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Process payout request
     */
    public function processPayoutRequest(PayoutRequest $payoutRequest, $adminId, $action = 'approve'): void
    {
        try {
            DB::beginTransaction();

            if ($action === 'approve') {
                $this->approvePayoutRequest($payoutRequest, $adminId);
            } else {
                $this->rejectPayoutRequest($payoutRequest, $adminId);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process payout request', [
                'payout_request_id' => $payoutRequest->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Approve payout request
     */
    private function approvePayoutRequest(PayoutRequest $payoutRequest, $adminId): void
    {
        $payoutRequest->update([
            'status' => 'approved',
            'approved_by' => $adminId,
            'approved_at' => now()
        ]);

        // Update earnings status to 'paid'
        Earning::whereIn('id', $payoutRequest->earnings_ids)
            ->update(['status' => 'paid']);

        // Update seller total earnings
        $seller = $payoutRequest->seller;
        $seller->increment('total_earnings', $payoutRequest->amount);

        Log::info('Payout request approved', [
            'payout_request_id' => $payoutRequest->id,
            'seller_id' => $seller->id,
            'amount' => $payoutRequest->amount,
            'approved_by' => $adminId
        ]);
    }

    /**
     * Reject payout request
     */
    private function rejectPayoutRequest(PayoutRequest $payoutRequest, $adminId): void
    {
        $payoutRequest->update([
            'status' => 'rejected',
            'rejected_by' => $adminId,
            'rejected_at' => now()
        ]);

        // Revert earnings status back to 'pending'
        Earning::whereIn('id', $payoutRequest->earnings_ids)
            ->update(['status' => 'pending']);

        Log::info('Payout request rejected', [
            'payout_request_id' => $payoutRequest->id,
            'seller_id' => $payoutRequest->seller_id,
            'rejected_by' => $adminId
        ]);
    }

    /**
     * Get payout analytics
     */
    public function getPayoutAnalytics($period = 30): array
    {
        $startDate = now()->subDays($period);
        
        $analytics = [
            'total_payout_requests' => PayoutRequest::where('created_at', '>=', $startDate)->count(),
            'total_payout_amount' => PayoutRequest::where('created_at', '>=', $startDate)->where('status', 'approved')->sum('amount'),
            'pending_payout_amount' => PayoutRequest::where('status', 'pending')->sum('amount'),
            'average_payout_amount' => PayoutRequest::where('created_at', '>=', $startDate)->where('status', 'approved')->avg('amount'),
            'payouts_by_status' => $this->getPayoutsByStatus($startDate),
            'payouts_by_month' => $this->getPayoutsByMonth($startDate),
            'top_payout_sellers' => $this->getTopPayoutSellers($startDate)
        ];

        return $analytics;
    }

    /**
     * Get payouts by status
     */
    private function getPayoutsByStatus($startDate): array
    {
        return PayoutRequest::where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->selectRaw('status, count(*) as count, sum(amount) as total_amount')
            ->get();
    }

    /**
     * Get payouts by month
     */
    private function getPayoutsByMonth($startDate): array
    {
        return PayoutRequest::where('created_at', '>=', $startDate)
            ->where('status', 'approved')
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, count(*) as count, sum(amount) as total_amount')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }

    /**
     * Get top payout sellers
     */
    private function getTopPayoutSellers($startDate): array
    {
        return PayoutRequest::with(['seller.user'])
            ->where('created_at', '>=', $startDate)
            ->where('status', 'approved')
            ->groupBy('seller_id')
            ->selectRaw('seller_id, count(*) as payout_count, sum(amount) as total_paid')
            ->orderBy('total_paid', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Get seller payout summary
     */
    public function getSellerPayoutSummary(Seller $seller): array
    {
        $summary = [
            'total_payouts' => $seller->payoutRequests()->where('status', 'approved')->count(),
            'total_paid' => $seller->payoutRequests()->where('status', 'approved')->sum('amount'),
            'pending_payout' => $seller->payoutRequests()->where('status', 'pending')->sum('amount'),
            'available_for_payout' => $seller->earnings()->where('status', 'pending')->sum('net_amount'),
            'last_payout' => $seller->payoutRequests()->where('status', 'approved')->latest()->first(),
            'payout_history' => $seller->payoutRequests()->orderBy('created_at', 'desc')->limit(10)->get()
        ];

        return $summary;
    }

    /**
     * Auto-approve small payouts
     */
    public function autoApproveSmallPayouts(): void
    {
        $autoApproveLimit = config('platform.auto_approve_payout_limit', 500);
        
        $smallPayouts = PayoutRequest::where('status', 'pending')
            ->where('amount', '<=', $autoApproveLimit)
            ->where('created_at', '<=', now()->subHours(24)) // Wait 24 hours before auto-approval
            ->get();

        foreach ($smallPayouts as $payout) {
            try {
                $this->approvePayoutRequest($payout, 1); // System admin ID
                
                Log::info('Payout auto-approved', [
                    'payout_request_id' => $payout->id,
                    'amount' => $payout->amount
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to auto-approve payout', [
                    'payout_request_id' => $payout->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
    }

    /**
     * Generate payout report
     */
    public function generatePayoutReport($filters = []): array
    {
        $query = PayoutRequest::with(['seller.user']);

        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }

        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['seller_id'])) {
            $query->where('seller_id', $filters['seller_id']);
        }

        $payouts = $query->orderBy('created_at', 'desc')->get();

        $report = [
            'summary' => [
                'total_payouts' => $payouts->count(),
                'total_amount' => $payouts->where('status', 'approved')->sum('amount'),
                'pending_amount' => $payouts->where('status', 'pending')->sum('amount'),
                'average_amount' => $payouts->where('status', 'approved')->avg('amount')
            ],
            'payouts' => $payouts,
            'generated_at' => now(),
            'filters' => $filters
        ];

        return $report;
    }
}











