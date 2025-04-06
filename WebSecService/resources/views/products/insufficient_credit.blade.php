@extends('layouts.master')
@section('title', 'Insufficient Credit')
@section('content')
<div class="container">
    <div class="alert alert-warning">
        <h2>Insufficient Credit</h2>
        <p>You don't have enough credit to purchase this product.</p>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3>Product Details</h3>
        </div>
        <div class="card-body">
            <h4>{{ $product->name }}</h4>
            <p><strong>Price:</strong> ${{ number_format($product->price, 2) }}</p>
            <p><strong>Your Current Credit:</strong> ${{ number_format(auth()->user()->credit, 2) }}</p>
            <p><strong>Additional Credit Needed:</strong> ${{ number_format($product->price - auth()->user()->credit, 2) }}</p>
            
            <p>Please contact an employee to add more credit to your account.</p>
            
            <div class="mt-3">
                <a href="{{ route('products_list') }}" class="btn btn-primary">Back to Products</a>
                <a href="{{ route('profile') }}" class="btn btn-secondary">View My Profile</a>
            </div>
        </div>
    </div>
</div>
@endsection 