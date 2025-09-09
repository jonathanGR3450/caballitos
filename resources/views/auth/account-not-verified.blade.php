@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    <h2 class="text-danger mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        {{ __('Lo sentimos') }}
                    </h2>
                    <p class="text-muted fs-5">
                        {{ __('Tu cuenta de vendedor aún no ha sido verificada por el administrador.') }}
                    </p>
                    <p class="mb-4">
                        {{ __('Por favor comunícate con el administrador para que pueda verificar tu cuenta y darte acceso completo.') }}
                    </p>
                    <a href="{{ route('logout') }}" 
                       class="btn btn-secondary"
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt me-2"></i> {{ __('Cerrar sesión') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
