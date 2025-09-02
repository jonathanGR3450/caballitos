<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class ProductController extends Controller
{
    /*──────────────────────── INDEX ───────────────────────*/
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    /*──────────────────────── CREATE ──────────────────────*/
    public function create()
    {
        $countries = Country::with('cities')->get();
        $categories = Category::all();
        return view('admin.products.create', compact('categories','countries'));
    }

    /*──────────────────────── STORE ───────────────────────*/
public function store(Request $request)
{
    $data = $this->validated($request);

    try {
        DB::beginTransaction();
        
        $product = Product::create($data);

        if ($request->has('has_extras')) {
            $product->extra()->create($request->only([
                'ubicacion','raza','edad','genero','pedigri','entrenamiento','historial_salud'
            ]));
        }

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products', 'public');
                $product->images()->create(['image' => $path]);
            }
        }

        // crear Price
        $prices = $request->input('prices', []);
        foreach ($prices as $priceData) {
            $product->prices()->create($priceData);
        }

        DB::commit();
        return redirect()->route('admin.products.index')
                        ->with('success', 'Producto creado ✅');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error al crear el producto');
    }
}

    /*──────────────────────── EDIT ────────────────────────*/
public function edit($id)
{
    $product = Product::with(['images', 'prices'])->findOrFail($id);
    $categories = Category::all();
    $countries = Country::with('cities')->get(); // 🔴 Necesitamos las ciudades también

    return view('admin.products.edit', compact('product', 'categories', 'countries'));
}

    /*──────────────────────── UPDATE ──────────────────────*/
public function update(Request $request, Product $product) 
{
    // Validación manual con todos los campos
    $validatedData = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'price' => 'required|numeric|min:0',
        'stock' => 'required|integer|min:0',
        'avg_weight' => 'nullable|string|max:50',
        'estado' => 'required|in:' . implode(',', Product::getEstados()),
        'vence'      => ['required', 'boolean'],
        'fecha_vencimiento' => $request->boolean('vence') ? 'required|date|after_or_equal:today' : 'nullable',
        'tipo_listado' => ['required', 'in:' . implode(',', Product::getTiposListado())],
        'category_id' => 'required|exists:categories,id',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        
        // 🔴 Nuevas validaciones para precios por ubicación
        'prices' => 'nullable|array',
        'prices.*.country_id' => 'required_with:prices.*|exists:countries,id',
        'prices.*.city_id' => 'nullable|exists:cities,id',
        'prices.*.interest' => 'nullable|numeric|min:0|max:100', // Asumiendo que es porcentaje
        'prices.*.shipping' => 'nullable|numeric|min:0',

        // extras
        'ubicacion'  => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'raza'       => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'edad'       => $request->has_extras ? ['required', 'integer', 'min:0'] : ['nullable', 'integer', 'min:0'],
        'genero'     => $request->has_extras ? ['required', 'string', 'in:Macho,Hembra'] : ['nullable', 'string', 'in:Macho,Hembra'],
        'pedigri'    => $request->has_extras ? ['required', 'boolean'] : ['nullable', 'boolean'],
        'entrenamiento' => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'historial_salud' => $request->has_extras ? ['required', 'string'] : ['nullable', 'string'],
    ]);

    // Actualizar el producto base (excluyendo images y prices)
    $productData = collect($validatedData)->except(['images', 'prices'])->toArray();
    $product->update($productData);

    if ($request->has('has_extras')) {
        $product->extra()->updateOrCreate(
            ['product_id' => $product->id],
            $request->only([
                'ubicacion','raza','edad','genero','pedigri','entrenamiento','historial_salud'
            ])
        );
    } else {
        $product->extra()?->delete();
    }

    // 🔴 Manejar configuraciones de precios por ubicación
    if ($request->has('prices') && is_array($request->prices)) {
        // Eliminar configuraciones anteriores
        $product->prices()->delete();

        // Crear nuevas configuraciones
        foreach ($request->prices as $priceData) {
            // Solo crear si tiene al menos un país seleccionado
            if (!empty($priceData['country_id'])) {
                $product->prices()->create([
                    'country_id' => $priceData['country_id'],
                    'city_id' => !empty($priceData['city_id']) ? $priceData['city_id'] : null,
                    'interest' => $priceData['interest'] ?? 0,
                    'shipping' => $priceData['shipping'] ?? 0,
                ]);
            }
        }
    }

    // Agregar nuevas imágenes (se mantiene igual)
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create(['image' => $path]);
        }
    }

    return back()->with('success', 'Producto actualizado ✔️');
}

    /*──────────────────────── DESTROY ─────────────────────*/
public function destroy(Product $product)
{
    try {
        DB::beginTransaction();
        
        // Eliminar imágenes del storage
        $product->images->each(function ($img) {
            Storage::disk('public')->delete($img->image);
        });
        
        // Laravel elimina automáticamente las imágenes de la BD por la relación
        $product->delete();
        
        DB::commit();
        return back()->with('success', 'Producto eliminado 🗑️');
    } catch (\Exception $e) {
        DB::rollback();
        return back()->with('error', 'Error al eliminar el producto');
    }
}

    /*──────────────────── VALIDACIÓN CENTRAL ──────────────*/
 private function validated(Request $request): array
{
    return $request->validate([
        'name'        => ['required', 'string', 'max:255'],
        'description' => ['nullable', 'string'],
        'price'       => ['required', 'numeric', 'min:0'],
        'stock'       => ['required', 'integer', 'min:0'],
        'category_id' => ['required', 'exists:categories,id'],
        'images.*'    => ['nullable', 'image', 'max:2048'],
        'avg_weight'  => ['nullable', 'string', 'max:250'],
        'estado'      => ['required', 'in:' . implode(',', Product::getEstados())],
        'vence'      => ['required', 'boolean'],
        'fecha_vencimiento' => 'required_if:vence,1|date|after_or_equal:today',
        'tipo_listado' => ['required', 'in:' . implode(',', Product::getTiposListado())],
        'ubicacion'  => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'raza'       => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'edad'       => $request->has_extras ? ['required', 'integer', 'min:0'] : ['nullable', 'integer', 'min:0'],
        'genero'     => $request->has_extras ? ['required', 'string', 'in:Macho,Hembra'] : ['nullable', 'string', 'in:Macho,Hembra'],
        'pedigri'    => $request->has_extras ? ['required', 'boolean'] : ['nullable', 'boolean'],
        'entrenamiento' => $request->has_extras ? ['required', 'string', 'max:250'] : ['nullable', 'string', 'max:250'],
        'historial_salud' => $request->has_extras ? ['required', 'string'] : ['nullable', 'string'],
    ]);
}
 public function show(Product $product)
    {
        // Cargar relaciones necesarias
        $product->load(['images', 'category']);
        
        // Productos relacionados de la misma categoría (8 productos)
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->with('images')
            ->take(8)
            ->get();
        
        // Productos populares/destacados (8 productos adicionales)
        $featuredProducts = Product::where('id', '!=', $product->id)
            ->where('stock', '>', 0)
            ->with('images')
            ->inRandomOrder() // o puedes usar ->orderBy('created_at', 'desc') para los más recientes
            ->take(8)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'featuredProducts'));
    }
}
