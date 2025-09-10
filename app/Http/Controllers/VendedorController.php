<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Rating;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendedorController extends Controller
{
    public function perfil(User $user)
    {
        if (!$user->hasRole('vendedor')) {
            abort(403, 'Usuario no es vendedor!');
        }
        // Productos del vendedor
        $products = Product::where('user_id', $user->id)
            ->with(['images', 'category', 'extra'])
            ->latest()
            ->paginate(12);

        // Calificaciones del vendedor (puedes adaptar según tu modelo Rating)
        $ratings = Rating::where('vendedor_id', $user->id)->get();

        // Calcular promedio
        $avgRating = $ratings->avg('score');

        return view('vendedor.perfil', compact('user', 'products', 'ratings', 'avgRating'));
    }

    public function store(Request $request, User $user)
    {
        $request->validate([
            'score' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        // Evitar que el vendedor se califique a sí mismo
        if ($user->id === Auth::id()) {
            return back()->with('error', 'No puedes calificarte a ti mismo.');
        }

        // Crear o actualizar la calificación si ya existe
        Rating::updateOrCreate(
            [
                'vendedor_id' => $user->id,
                'user_id' => Auth::id(),
            ],
            [
                'score' => $request->score,
                'comment' => $request->comment,
            ]
        );

        return back()->with('success', 'Calificación enviada correctamente ✅');
    }
}
