<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductQuestion;
use Illuminate\Http\Request;

class ProductQuestionController extends Controller
{
    public function store(Request $request, Product $product)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'user_email' => 'nullable|email',
            'question' => 'required|string|max:1000',
        ]);

        $product->questions()->create($request->only('user_name', 'user_email', 'question'));

        return back()->with('success', 'Tu pregunta fue enviada y será respondida pronto.');
    }

    public function update(Request $request, ProductQuestion $question)
    {
        $request->validate([
            'answer' => 'nullable|string|max:2000',
        ]);

        $question->update([
            'answer' => $request->answer,
        ]);

        return back()->with('success', 'Respuesta guardada correctamente.');
    }
}
