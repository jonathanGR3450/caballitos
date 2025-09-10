<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Listar favoritos del comprador
    public function index()
    {
        $products = Auth::user()
            ->favoriteProducts()
            ->with(['images','category','user']) // reutiliza tus relaciones del grid
            ->latest('favorite_products.created_at')
            ->paginate(24);

        return view('favorites.index', compact('products'));
    }

    // Agregar / quitar favorito (toggle)
    public function toggle(Product $product, Request $request)
    {
        $user = Auth::user();
        $attached = $user->favoriteProducts()->where('products.id', $product->id)->exists();

        if ($attached) {
            $user->favoriteProducts()->detach($product->id);
            $status = 'removed';
        } else {
            $user->favoriteProducts()->attach($product->id);
            $status = 'added';
        }

        // Respuesta JSON para AJAX; fallback a redirect si no es ajax
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status,
                'product_id' => $product->id,
                'count' => $user->favoriteProducts()->count(),
            ]);
        }

        return back()->with('favorite_status', $status);
    }
}
