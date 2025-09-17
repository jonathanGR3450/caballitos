@extends('layouts.app')

@section('title', 'Payment Successful - Order #' . $order->order_number)

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<style>
    .payment-success-card {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: none;
    }
    .success-header {
        background: linear-gradient(135deg, #011904 0%, #28a745 100%);
    }
    .badge-payment {
        font-size: 0.875rem;
    }
    .info-section {
        background-color: #f8f9fa;
        border-left: 4px solid #011904;
    }
    .card-title {
        color: #011904;
    }
    .btn-primary {
        background-color: #011904;
        border-color: #011904;
    }
    .btn-primary:hover {
        background-color: #023a07;
        border-color: #023a07;
    }
    .text-brand {
        color: #011904;
    }
    .border-brand {
        border-color: #011904 !important;
    }
    .bg-brand {
        background-color: #011904;
    }
    .table-success {
        background-color: rgba(1, 25, 4, 0.1);
    }
</style>
@endpush

@section('content')
<div class="container">
    <div class="alert alert-danger text-center">
        <h2>Pago Cancelado</h2>
        <p>Tu orden #{{ $order->order_number }} no pudo ser procesada.</p>
        <a href="{{ route('shop.index') }}" class="btn btn-primary">Volver a la tienda</a>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Optional: Auto-scroll to top when page loads
document.addEventListener('DOMContentLoaded', function() {
    window.scrollTo(0, 0);
    
    // Optional: Confetti effect (if you want to add a special effect)
    // console.log('Payment successful! 🎉');
});
</script>
@endpush