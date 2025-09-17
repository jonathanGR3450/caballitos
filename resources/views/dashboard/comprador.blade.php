@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="">🛒 Welcome {{ $user->name }}</h2>
    <p class="text-muted">Email: {{ $user->email }}</p>

    <hr class="border-secondary">

    <h4 class="">📦 Your Orders</h4>

    @if($orders->isEmpty())
        <div class="alert alert-info">You don’t have any registered orders yet.</div>
    @else
        <div class="table-responsive mt-3">
            <table class="table  table-bordered table-striped align-middle ">
                <thead class="table-secondary text-dark">
                    <tr>
                        <th># Order</th>
                        <th>Date</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Complete Payment</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr>
                        <td>{{ $order->order_number }}</td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td>${{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td>{{ ucfirst($order->status) }}</td>
                        <td>{{ ucfirst($order->payment_status) }}</td>
                        <td>
                            @if($order->payment_status === 'pending')
                                <a href="{{ route('payment.process', $order->id) }}" class="btn btn-primary btn-sm">Pay Now</a>
                            @else
                                <span class="text-success">Paid</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
