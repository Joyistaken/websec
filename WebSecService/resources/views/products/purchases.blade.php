@extends('layouts.master')
@section('title', 'My Purchases')
@section('content')
<div class="container">
    <h2>My Purchase History</h2>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    @if($purchases->isEmpty())
        <div class="alert alert-info">
            You haven't made any purchases yet.
        </div>
        <a href="{{ route('products_list') }}" class="btn btn-primary">Browse Products</a>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price Paid</th>
                        <th>Purchase Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchases as $purchase)
                    <tr>
                        <td>{{ $purchase->product->name }}</td>
                        <td>${{ number_format($purchase->price_paid, 2) }}</td>
                        <td>{{ $purchase->created_at->format('M d, Y g:i A') }}</td>
                        <td>
                            <a href="{{ route('products_list') }}?keywords={{ urlencode($purchase->product->name) }}" class="btn btn-sm btn-info">View Product</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <a href="{{ route('products_list') }}" class="btn btn-primary">Browse More Products</a>
            <a href="{{ route('profile') }}" class="btn btn-secondary">Back to Profile</a>
        </div>
    @endif
</div>
@endsection 