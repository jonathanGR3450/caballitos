@extends('layouts.app_admin')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning mb-0">Users</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New User
        </a>
    </div>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Tabla de usuarios --}}
    @if($users->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Roles') }}</th>
                        <th>{{ __('Listado') }}</th>
                        <th>{{ __('Fecha Membresia') }}</th>
                        <th>{{ __('Verified') }}</th>
                        <th>{{ __('Created At') }}</th>
                        <th class="text-center">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->roles->count() > 0)
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ $role->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted small">No roles</span>
                                @endif
                            </td>
                            <td>
                                @if($user->tipoListado?->count() > 0)
                                    <span class="badge bg-primary">{{ $user->tipoListado->nombre }}</span>
                                @else
                                    <span class="text-muted small">No Listado</span>
                                @endif
                            </td>
                            <td>{{ $user->membresia_comprada_en?->format('Y-m-d') }}</td>
                            <td>
                                <input type="checkbox" {{ $user->is_verified ? 'checked' : '' }} onchange="toggleVerify({{ $user->id }}, this.checked)">
                            </td>
                            <td>{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="text-center">
                                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-3">
            {{ $users->links('pagination::bootstrap-5') }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="fas fa-users fa-5x text-muted mb-3"></i>
            <h4 class="text-muted">No Users Found</h4>
            <p class="text-muted">Start by creating your first user</p>
            <a href="{{ route('admin.users.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Create First User
            </a>
        </div>
    @endif
</div>

<style>
/* Estilos adicionales para las tarjetas */
.role-card {
    border: 1px solid #DEB887;
    transition: all 0.3s ease;
    overflow: hidden;
}

.role-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(255, 193, 7, 0.15);
    border-color: #DEB887;
}

.role-card .card-img-top {
    transition: transform 0.3s ease;
}

.role-card:hover .card-img-top {
    transform: scale(1.05);
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
    transform: translateY(-1px);
}

.btn-danger {
    background-color: #dc3545;
    border-color: #dc3545;
}

.btn-danger:hover {
    background-color: #c82333;
    border-color: #bd2130;
    transform: translateY(-1px);
}

.btn-success { background-color: #DEB887; border-color: #DEB887; }
.btn-success:hover { background-color: #f7a831; border-color: #f7a831; color: #101820; }

.alert {
    border: none;
    border-radius: 10px;
}

.badge {
    font-size: 0.75rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .role-card {
        margin-bottom: 1rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
    }
    
    .d-flex.gap-2 .flex-fill {
        width: 100%;
        margin-bottom: 0.5rem;
    }
}
</style>

<script>
    function toggleVerify(userId, checked) {
        // prevenir evento por defecto
        event.preventDefault();
        let url = `{{ route('admin.users.toggle-verify', ['user' => 'USER_ID']) }}`;
        url = url.replace('USER_ID', userId);

        fetch(url, {
            method: 'PATCH',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ is_verified: checked })
        }).then(res => location.reload());
    }
    function confirmDelete(roleName) {
        return confirm(`Are you sure you want to delete the role "${roleName}"?\n\nThis action cannot be undone.`);
    }

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                if (alert.classList.contains('show')) {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }
            });
        }, 5000);
    });
</script>
@endsection