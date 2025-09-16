<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Models\TipoListado;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('roles', 'tipoListado')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function toggleVerify(User $user)
    {
        // Cambiar el valor actual (si es true => false, si es false => true)
        $user->is_verified = !$user->is_verified;
        $user->save();

        return redirect()->back()->with('success', 'Estado de verificación actualizado.');
    }

    public function create()
    {
        $roles = Role::all();
        $tipoListados = TipoListado::where('is_activo', true)->get();
        return view('users.create', compact('roles', 'tipoListados'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role'     => 'required|exists:roles,name',
            'tipo_listado_id'     => 'required|exists:tipo_listados,id',
            'membresia_comprada_en' => 'required|date',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'tipo_listado_id' => $request->tipo_listado_id,
            'membresia_comprada_en' => $request->membresia_comprada_en,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }
    // Método para mostrar formulario de edición
    public function edit(User $user)
    {
        $roles = Role::all();
        $tipoListados = TipoListado::where('is_activo', true)->get();
        return view('users.edit', compact('user', 'roles', 'tipoListados'));
    }

    // ← Y ESTE TAMBIÉN
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'role'  => 'required|exists:roles,name',
            'tipo_listado_id' => 'required|exists:tipo_listados,id',
            'membresia_comprada_en' => 'required|date',
        ]);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'password' => $request->password
                ? Hash::make($request->password)
                : $user->password,
            'tipo_listado_id' => $request->tipo_listado_id,
            'membresia_comprada_en' => $request->membresia_comprada_en,
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully!');
    }
    public function destroy(User $user)
    {
        try {

            $user->delete();

            return redirect()->route('admin.users.index')
                            ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')
                            ->with('error', 'Error deleting user. Please try again.');
        }
    }
}
