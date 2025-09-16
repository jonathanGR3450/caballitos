<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    const MAX_ITEMS = 4; // máx columnas

    public function index(Request $request)
    {
        $ids = collect($request->session()->get('compare.products', []))->unique()->take(self::MAX_ITEMS)->values();

        $products = Product::with(['images','category','user','extra','prices'])
            ->whereIn('id', $ids)
            // conservar orden de IDs en sesión:
            ->orderByRaw('FIELD(id,'.($ids->isNotEmpty() ? $ids->implode(',') : '0').')')
            ->get();

        // Atributos a comparar (ajusta según tus columnas y relaciones)
        $baseFields = [
            'name' => 'Nombre',
            'price' => 'Precio',
            'avg_weight' => 'Peso prom.',
            'stock' => 'Stock',
            'estado' => 'Estado',
            'tipo_listado_id' => 'Tipo listado',
        ];

        return view('compare.index', [
            'products' => $products,
            'fields'   => $baseFields,
        ]);
    }

    public function toggle(Request $request, Product $product)
    {
        $ids = collect($request->session()->get('compare.products', []))->values();

        $status = 'added';
        if ($ids->contains($product->id)) {
            $ids = $ids->reject(fn($id) => (int)$id === (int)$product->id)->values();
            $status = 'removed';
        } else {
            if ($ids->count() >= self::MAX_ITEMS) {
                // quita el más antiguo y agrega el nuevo
                $ids->shift();
            }
            $ids->push($product->id);
        }

        $request->session()->put('compare.products', $ids->all());

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'count'  => $ids->count(),
                'ids'    => $ids->all(),
            ]);
        }

        return back()->with('compare_status', $status);
    }

    public function clear(Request $request)
    {
        $request->session()->forget('compare.products');
        return back()->with('compare_cleared', true);
    }
}
