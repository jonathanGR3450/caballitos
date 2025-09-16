<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\StoreTipoListadoRequest;
use App\Http\Requests\Auth\UpdateTipoListadoRequest;
use App\Models\TipoListado;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TipoListadoController extends Controller
{
    public function index()
    {
        $listados = TipoListado::orderByDesc('is_activo')
            ->orderBy('precio')
            ->paginate(12);

        return view('tipo-listado.index', compact('listados'));
    }

    public function create()
    {
        return view('tipo-listado.create');
    }

    public function store(StoreTipoListadoRequest $request)
    {
        $data = $request->validated();

        // slug automático si viene vacío
        $data['slug'] = $data['slug'] ?: Str::slug($data['nombre']);

        // si es ilimitado, max_productos = null
        if ($data['is_ilimitado']) {
            $data['max_productos'] = null;
        }

        $tipoListado = TipoListado::create($data);

        return redirect()->route('admin.tipo-listado.index')
            ->with('success', 'Tipo de listado creado correctamente.');
    }

    public function edit(TipoListado $tipoListado)
    {
        return view('tipo-listado.edit', compact('tipoListado'));
    }

    public function update(UpdateTipoListadoRequest $request, TipoListado $tipoListado)
    {
        $data = $request->validated();
        $data['slug'] = $data['slug'] ?: Str::slug($data['nombre']);

        if ($data['is_ilimitado']) {
            $data['max_productos'] = null;
        }

        $tipoListado->update($data);

        return redirect()->route('admin.tipo-listado.index')
            ->with('success', 'Tipo de listado actualizado correctamente.');
    }

    public function destroy(TipoListado $tipoListado)
    {
        $tipoListado->delete();
        return redirect()->route('admin.tipo-listado.index')
            ->with('success', 'Tipo de listado eliminado.');
    }

    public function toggleActive(TipoListado $tipoListado)
    {
        $tipoListado->update(['is_activo' => ! $tipoListado->is_activo]);
        return back()->with('success', 'Estado actualizado.');
    }
}
