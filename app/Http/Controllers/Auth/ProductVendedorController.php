<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Country;
use App\Models\TipoListado;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductVendedorController extends ProductController
{

    use AuthorizesRequests, ValidatesRequests; 
    
    /*──────────────────────── INDEX ───────────────────────*/
    public function index()
    {
        // Solo productos del vendedor logueado
        $products = Product::with('category')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('vendedor.products.index', compact('products'));
    }

    /*──────────────────────── STORE ───────────────────────*/
    public function store(Request $request)
    {
        $this->authorize('create', \App\Models\Product::class);
        $this->validated($request);
        $user = Auth::user();

        try {
            $product = $this->saveProduct($request);
            $product->user_id = $user->id;
            $product->tipo_listado_id = $user->tipo_listado_id;

            $product->save();
            return redirect()->route('vendedor.products.index')
                ->with('success', 'Producto creado ✅');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el producto');
        }
    }

    public function create()
    {
        $countries = Country::with('cities')->get();
        $categories = Category::all();
        return view('vendedor.products.create', compact('categories','countries'));
    }



    /*──────────────────────── EDIT ────────────────────────*/
    public function edit($id)
    {
        $product = Product::where('user_id', Auth::id())
            ->with(['images', 'prices'])
            ->findOrFail($id);

        $categories = \App\Models\Category::all();
        $countries  = \App\Models\Country::with('cities')->get();
        return view('vendedor.products.edit', compact('product', 'categories', 'countries'));
    }

    /*──────────────────────── UPDATE ──────────────────────*/
    public function update(Request $request, Product $product)
    {
        // Validar que sea del usuario logueado
        if ($product->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para editar este producto');
        }

        $this->validated($request);
        $user = Auth::user();

        try {
            $product = $this->updateProduct($request, $product);
            $product->user_id = $user->id;
            $product->tipo_listado_id = $user->tipo_listado_id;

            $product->save();
            return back()->with('success', 'Producto actualizado ✔️');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear el producto');
        }
    }

    /*──────────────────────── DESTROY ─────────────────────*/
    public function destroy(Product $product)
    {
        // Validar que sea del usuario logueado
        if ($product->user_id !== Auth::id()) {
            abort(403, 'No tienes permiso para eliminar este producto');
        }

        return parent::destroy($product);
    }

    private function authorizeProduct(Product $product)
    {
        if ($product->user_id !== Auth::id()) {
            abort(403, 'No autorizado');
        }
    }
}
