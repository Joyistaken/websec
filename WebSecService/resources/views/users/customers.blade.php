@extends('layouts.master')
@section('title', 'Customers')
@section('content')
<div class="container">
    <h2>Customer List</h2>
    
    <div class="row mb-3">
        <div class="col-md-6">
            <form action="{{ route('customers.list') }}" method="GET" class="d-flex">
                <input type="text" name="keywords" class="form-control me-2" placeholder="Search by name" value="{{ request('keywords') }}">
                <button type="submit" class="btn btn-primary">Search</button>
            </form>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Credit Balance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td>{{ $customer->id }}</td>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>${{ number_format($customer->credit, 2) }}</td>
                    <td>
                        <a href="{{ route('profile', $customer->id) }}" class="btn btn-sm btn-info">View</a>
                        @if(auth()->user()->hasPermissionTo('add_credit'))
                            <a href="{{ route('customers.credit', $customer->id) }}" class="btn btn-sm btn-success">Add Credit</a>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    @if($customers->isEmpty())
        <div class="alert alert-info">
            No customers found.
        </div>
    @endif
</div>
@endsection 