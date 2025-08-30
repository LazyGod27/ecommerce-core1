@extends('layouts.frontend')

@section('title', 'Search Products - iMarket')

@section('content')
<div id="app">
    <product-search></product-search>
</div>
@endsection

@push('scripts')
@vite(['resources/js/app.js'])
@endpush