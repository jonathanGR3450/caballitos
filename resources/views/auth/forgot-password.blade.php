@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-5 text-center">
                    
                    <h2 class="text-primary mb-3">
                        <i class="fas fa-unlock-alt me-2"></i>
                        {{ __('¿Olvidaste tu contraseña?') }}
                    </h2>

                    <p class="text-muted mb-4">
                        {{ __('No te preocupes, simplemente ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.') }}
                    </p>

                    <!-- Mensaje de estado -->
                    @if (session('status'))
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}" class="text-start">
                        @csrf

                        <!-- Email -->
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="fas fa-envelope me-2 text-primary"></i> {{ __('Correo electrónico') }}
                            </label>
                            <input id="email" 
                                   type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required 
                                   autofocus 
                                   placeholder="ejemplo@correo.com">
                            
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Botón -->
                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold">
                                <i class="fas fa-paper-plane me-2"></i>
                                {{ __('Enviar enlace de restablecimiento') }}
                            </button>
                        </div>
                    </form>

                    <div class="mt-4">
                        <a href="{{ route('login') }}" class="text-decoration-none">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ __('Volver al inicio de sesión') }}
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

