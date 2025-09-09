@extends('layouts.app_admin')

@section('content')
<div class="container py-5 ">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-warning mb-0">Existing Roles </h2>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
            <i class="fas fa-plus me-2"></i>Add New Role
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($roles->count() > 0)
        <div class="row">
            @foreach($roles as $role)
                <div class="col-md-4 mb-4">
                    <div class="card  role-card h-100">
                        
                        <div class="card-body d-flex flex-column">
                            <div class="mb-auto">
                                <h5 class="card-title text-warning">{{ $role->name }}</h5>
                                
                                @if($role->country)
                                    <div class="mb-2">
                                        <span class="badge bg-info">
                                            <i class="fas fa-flag me-1"></i>{{ $role->country }}
                                        </span>
                                    </div>
                                @endif
                                
                                @if($role->description)
                                    <p class="card-text ">{{ Str::limit($role->description, 100) }}</p>
                                @else
                                    <p class="card-text text-muted">No description available</p>
                                @endif
                                
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Created: {{ $role->created_at->format('M d, Y') }}
                                </small>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="mt-3 d-flex gap-2">
                                <a href="{{ route('admin.roles.edit', $role->id) }}" class="btn btn-warning btn-sm flex-fill">
                                    <i class="fas fa-edit me-1"></i>Edit
                                </a>
                                
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" class="flex-fill" onsubmit="return confirmDelete('{{ $role->name }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm w-100">
                                        <i class="fas fa-trash me-1"></i>Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
    @else
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-folder-open fa-5x text-muted"></i>
            </div>
            <h4 class="text-muted">No Roles Found</h4>
            <p class="text-muted">Start by creating your first role</p>
            <a href="{{ route('admin.roles.create') }}" class="btn btn-success">
                <i class="fas fa-plus me-2"></i>Create First Role
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