@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <h2 class="text-primary mb-3">
                        <i class="fas fa-envelope me-2"></i>
                        {{ __('Verifica tu correo electrónico') }}
                    </h2>

                    <p class="text-muted fs-5 mb-4">
                        {{ __('Gracias por registrarte. Antes de comenzar, por favor confirma tu dirección de correo electrónico haciendo clic en el enlace que te acabamos de enviar. Si no lo recibiste, puedes solicitar otro.') }}
                    </p>

                    @if (session('status') == 'verification-link-sent')
                        <div class="alert alert-success mb-4">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ __('Se ha enviado un nuevo enlace de verificación a tu correo electrónico.') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-center gap-3">
                        <form method="POST" action="{{ route('verification.send') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ __('Reenviar correo de verificación') }}
                            </button>
                        </form>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-secondary">
                                <i class="fas fa-sign-out-alt me-2"></i>
                                {{ __('Cerrar sesión') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
