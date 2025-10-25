<?php

namespace App\Services;

use App\Models\CommissionRule;
use App\Models\Seller;
use App\Models\Product;
use App\Models\Order;
use App\Models\Earning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommissionService
{
    /**
     * Calculate commission for an order
     */
    public function calculateCommission(Order $order): array
    {
        $commissions = [];
        $totalCommission = 0;
        $totalPlatformFee = 0;

        foreach ($order->items as $item) {
            $product = $item->product;
            $seller = $product->seller;
            
            if (!$seller) continue;

            $itemTotal = $item->quantity * $item->price;
            
            // Get commission rate for this seller/product
            $commissionRate = $this->getCommissionRate($seller, $product, $itemTotal);
            $commissionAmount = $itemTotal * ($commissionRate / 100);
            
            // Calculate platform fee (fixed 5%)
            $platformFee = $itemTotal * 0.05;
            $netAmount = $itemTotal - $commissionAmount - $platformFee;

            $commissions[] = [
                'seller_id' => $seller->id,
                'order_id' => $order->id,
                'product_id' => $product->id,
                'amount' => $itemTotal,
                'commission_rate' => $commissionRate,
                'commission_amount' => $commissionAmount,
                'platform_fee' => $platformFee,
                'net_amount' => $netAmount,
                'currency' => 'PHP',
                'status' => 'pending'
            ];

            $totalCommission += $commissionAmount;
            $totalPlatformFee += $platformFee;
        }

        return [
            'commissions' => $commissions,
            'total_commission' => $totalCommission,
            'total_platform_fee' => $totalPlatformFee
        ];
    }

    /**
     * Get commission rate for seller/product
     */
    public function getCommissionRate(Seller $seller, Product $product, float $amount): float
    {
        // First, check if seller has a custom commission rate
        if ($seller->commission_rate > 0) {
            return $seller->commission_rate;
        }

        // Check subscription plan commission rate
        $subscription = $seller->currentSubscription;
        if ($subscription && $subscription->plan) {
            return $subscription->plan->commission_rate;
        }

        // Apply commission rules
        $rule = $this->getApplicableCommissionRule($seller, $product, $amount);
        if ($rule) {
            return $rule->commission_rate;
        }

        // Default commission rate
        return config('platform.default_commission_rate', 5.0);
    }

    /**
     * Get applicable commission rule
     */
    public function getApplicableCommissionRule(Seller $seller, Product $product, float $amount): ?CommissionRule
    {
        $rules = CommissionRule::where('is_active', true)
            ->where(function ($query) use ($seller, $product, $amount) {
                $query->where('category', $product->category)
                      ->orWhere('category', '*');
            })
            ->where(function ($query) use ($seller) {
                $query->whereNull('subscription_plan_id')
                      ->orWhere('subscription_plan_id', $seller->currentSubscription?->plan_id);
            })
            ->where(function ($query) use ($amount) {
                $query->whereNull('min_amount')
                      ->orWhere('min_amount', '<=', $amount);
            })
            ->where(function ($query) use ($amount) {
                $query->whereNull('max_amount')
                      ->orWhere('max_amount', '>=', $amount);
            })
            ->where(function ($query) {
                $query->whereNull('effective_from')
                      ->orWhere('effective_from', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('effective_to')
                      ->orWhere('effective_to', '>=', now());
            })
            ->orderBy('priority', 'asc')
            ->get();

        return $rules->first();
    }

    /**
     * Process commission for an order
     */
    public function processCommission(Order $order): void
    {
        try {
            DB::beginTransaction();

            $commissionData = $this->calculateCommission($order);
            
            foreach ($commissionData['commissions'] as $commissionData) {
                Earning::create($commissionData);
            }

            // Update seller statistics
            foreach ($order->items as $item) {
                $seller = $item->product->seller;
                if ($seller) {
                    $seller->increment('total_orders');
                }
            }

            Log::info('Commission processed for order', [
                'order_id' => $order->id,
                'total_commission' => $commissionData['total_commission'],
                'total_platform_fee' => $commissionData['total_platform_fee']
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to process commission', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Update commission rules
     */
    public function updateCommissionRules(array $rules): void
    {
        try {
            DB::beginTransaction();

            foreach ($rules as $ruleData) {
                if (isset($ruleData['id'])) {
                    // Update existing rule
                    $rule = CommissionRule::findOrFail($ruleData['id']);
                    $rule->update($ruleData);
                } else {
                    // Create new rule
                    CommissionRule::create($ruleData);
                }
            }

            Log::info('Commission rules updated', [
                'rules_count' => count($rules),
                'updated_by' => 'external_api'
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to update commission rules', [
                'error' => $e->getMessage(),
                'rules_data' => $rules
            ]);

            throw $e;
        }
    }

    /**
     * Get commission analytics
     */
    public function getCommissionAnalytics($period = 30): array
    {
        $startDate = now()->subDays($period);
        
        $analytics = [
            'total_commission_earned' => Earning::where('created_at', '>=', $startDate)->sum('commission_amount'),
            'total_platform_fee_earned' => Earning::where('created_at', '>=', $startDate)->sum('platform_fee'),
            'commission_by_category' => $this->getCommissionByCategory($startDate),
            'commission_by_seller' => $this->getCommissionBySeller($startDate),
            'average_commission_rate' => $this->getAverageCommissionRate($startDate),
            'top_earning_sellers' => $this->getTopEarningSellers($startDate)
        ];

        return $analytics;
    }

    /**
     * Get commission by category
     */
    private function getCommissionByCategory($startDate): array
    {
        return Earning::join('products', 'earnings.product_id', '=', 'products.id')
            ->where('earnings.created_at', '>=', $startDate)
            ->groupBy('products.category')
            ->selectRaw('products.category, sum(earnings.commission_amount) as total_commission, avg(earnings.commission_rate) as avg_rate')
            ->get();
    }

    /**
     * Get commission by seller
     */
    private function getCommissionBySeller($startDate): array
    {
        return Earning::join('sellers', 'earnings.seller_id', '=', 'sellers.id')
            ->where('earnings.created_at', '>=', $startDate)
            ->groupBy('sellers.id', 'sellers.business_name')
            ->selectRaw('sellers.id, sellers.business_name, sum(earnings.commission_amount) as total_commission, count(*) as transactions')
            ->orderBy('total_commission', 'desc')
            ->limit(20)
            ->get();
    }

    /**
     * Get average commission rate
     */
    private function getAverageCommissionRate($startDate): float
    {
        return Earning::where('created_at', '>=', $startDate)->avg('commission_rate') ?? 0;
    }

    /**
     * Get top earning sellers
     */
    private function getTopEarningSellers($startDate): array
    {
        return Seller::with(['user'])
            ->whereHas('earnings', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->withSum(['earnings' => function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            }], 'net_amount')
            ->orderBy('earnings_sum_net_amount', 'desc')
            ->limit(10)
            ->get();
    }

    /**
     * Recalculate commission for existing order
     */
    public function recalculateCommission(Order $order): void
    {
        try {
            DB::beginTransaction();

            // Delete existing earnings
            Earning::where('order_id', $order->id)->delete();

            // Recalculate and create new earnings
            $this->processCommission($order);

            Log::info('Commission recalculated for order', [
                'order_id' => $order->id
            ]);

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Failed to recalculate commission', [
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    /**
     * Get commission summary for seller
     */
    public function getSellerCommissionSummary(Seller $seller, $period = 30): array
    {
        $startDate = now()->subDays($period);
        
        $summary = [
            'total_earnings' => $seller->earnings()->where('created_at', '>=', $startDate)->sum('net_amount'),
            'total_commission' => $seller->earnings()->where('created_at', '>=', $startDate)->sum('commission_amount'),
            'total_platform_fee' => $seller->earnings()->where('created_at', '>=', $startDate)->sum('platform_fee'),
            'average_commission_rate' => $seller->earnings()->where('created_at', '>=', $startDate)->avg('commission_rate'),
            'pending_earnings' => $seller->earnings()->where('created_at', '>=', $startDate)->where('status', 'pending')->sum('net_amount'),
            'paid_earnings' => $seller->earnings()->where('created_at', '>=', $startDate)->where('status', 'paid')->sum('net_amount'),
            'earnings_by_month' => $this->getSellerEarningsByMonth($seller, $startDate)
        ];

        return $summary;
    }

    /**
     * Get seller earnings by month
     */
    private function getSellerEarningsByMonth(Seller $seller, $startDate): array
    {
        return $seller->earnings()
            ->where('created_at', '>=', $startDate)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") as month, sum(net_amount) as earnings')
            ->groupBy('month')
            ->orderBy('month')
            ->get();
    }
}


























