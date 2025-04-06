@extends('layouts.master')
@section('title', 'Add Credit')
@section('content')
<div class="row">
    <div class="m-4 col-sm-6">
        <h2>Add Credit for {{ $user->name }}</h2>
        <p>Current Credit Balance: ${{ number_format($user->credit, 2) }}</p>
        
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <form method="POST" action="{{ route('customers.store_credit', $user->id) }}">
            @csrf
            <div class="mb-3">
                <label for="amount" class="form-label">Amount to Add ($)</label>
                <input type="number" step="0.01" min="0.01" class="form-control" id="amount" name="amount" required>
                <div class="form-text">Enter a positive amount to add to the customer's credit balance.</div>
            </div>
            
            <button type="submit" class="btn btn-primary">Add Credit</button>
            <a href="{{ route('profile', $user->id) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection 