<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Pagination\Paginator;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
        ]);

        $data = $request->only('name');

        Role::create($data);

        return redirect()->route('admin.roles.index')->with('success', 'Rol creado correctamente');
    }
    // Método para mostrar formulario de edición
  public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    // ← Y ESTE TAMBIÉN
    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $role->fill($validated);

        $role->save();

        return redirect()->route('admin.roles.index')
                        ->with('success', 'Role updated successfully!');
    }
    public function destroy(Role $role)
    {
        try {

            $role->delete();

            return redirect()->route('admin.roles.index')
                            ->with('success', 'Role deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.roles.index')
                            ->with('error', 'Error deleting role. Please try again.');
        }
    }
}
