@extends('layouts.app_admin')

@section('content')
<div class="container py-5 ">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning mb-0">Edit User</h2>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" enctype="multipart/form-data" class="p-4 rounded">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label ">Name</label>
            <input type="text" name="name" id="name" class="form-control"
                   required value="{{ old('name', $user->name) }}"
                   placeholder="Enter user name">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label ">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   required value="{{ old('email', $user->email) }}"
                   placeholder="Enter email address">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label ">Password (leave blank to keep current)</label>
            <input type="password" name="password" id="password" class="form-control"
                   placeholder="Enter new password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label ">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control" placeholder="Confirm new password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label ">Assign Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}"
                        {{ (old('role') ?? $user->roles->pluck('name')->first()) == $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="tipo_listado_id" class="form-label ">Assign Listado</label>
            <select name="tipo_listado_id" id="tipo_listado_id" class="form-select" required>
                <option value="">-- Select Listado --</option>
                @foreach($tipoListados as $listado)
                    <option value="{{ $listado->id }}"
                        {{ old('tipo_listado_id', $user->tipo_listado_id ?? '') == $listado->id ? 'selected' : '' }}>
                        {{ $listado->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="membresia_comprada_en" class="form-label ">Fecha de Compra Membresia</label>
            <input type="date" id="membresia_comprada_en" name="membresia_comprada_en"
                class="form-control"
                value="{{ old('membresia_comprada_en', $user->membresia_comprada_en ?? '') }}">
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-warning fw-bold">
                <i class="fas fa-save me-2"></i>Update User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-times me-2"></i>Cancel
            </a>
        </div>
    </form>
</div>


<style>
/* Estilos para el formulario de edición */
.form-control {
    background-color: #FAF9F6;
    color: #000;
}

.form-control:focus {
    background-color: #FAF9F6;
    border-color: #ffc107;
    box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    color: #000;
}

.form-control::placeholder {
    color: #a0aec0;
}

.btn-warning {
    background-color: #ffc107;
    border-color: #ffc107;
    color: #000;
    font-weight: 600;
}

.btn-warning:hover {
    background-color: #e0a800;
    border-color: #d39e00;
    color: #000;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(255, 193, 7, 0.3);
}

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    border-color: #545b62;
    transform: translateY(-2px);
}

.alert {
    border: none;
    border-radius: 10px;
}

/* Para la imagen actual */
.border {
    border: 2px solid #4a5568 !important;
}
</style>
@endsection