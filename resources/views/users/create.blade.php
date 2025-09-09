@extends('layouts.app_admin')

@section('content')
<div class="container py-5 text-white">
    <h2 class="mb-4 text-warning">Add New User</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="p-4 rounded">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-light">Name</label>
            <input type="text" name="name" id="name" class="form-control"
                   required value="{{ old('name') }}" placeholder="Enter user name">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-light">Email</label>
            <input type="email" name="email" id="email" class="form-control"
                   required value="{{ old('email') }}" placeholder="Enter email address">
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-light">Password</label>
            <input type="password" name="password" id="password" class="form-control"
                   required placeholder="Enter password">
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label text-light">Confirm Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation"
                   class="form-control" required placeholder="Confirm password">
        </div>

        <div class="mb-3">
            <label for="role" class="form-label text-light">Assign Role</label>
            <select name="role" id="role" class="form-select" required>
                <option value="">-- Select Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-3">
            <button type="submit" class="btn btn-success fw-bold">
                <i class="fas fa-save me-2"></i>Save User
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Users
            </a>
        </div>
    </form>
</div>


<style>
/* Estilos adicionales para mejorar el formulario */
.form-control {
    background-color: #FAF9F6;
    color: #000;
}

.form-control:focus {
    background-color: #FAF9F6;
    border-color: #DEB887;
    box-shadow: 0 0 0 0.2rem rgba(222, 184, 135, 0.25);
    color: #000;
}

.form-control::placeholder {
    color: #a0aec0;
}

.btn-warning:hover {
    background-color: #DEB887;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(222, 184, 135, 0.3);
}

.btn-success { background-color: #DEB887 !important; border-color: #DEB887 !important; }
.btn-success:hover { background-color: #f7a831 !important; border-color: #f7a831 !important; color: #101820 !important; }

.btn-secondary {
    background-color: #6c757d;
    border-color: #6c757d;
}

.btn-secondary:hover {
    background-color: #5a6268;
    transform: translateY(-2px);
}
</style>
@endsection