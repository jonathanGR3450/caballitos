<?php

namespace App\Http\Controllers;

use App\Mail\ContactFormMail;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Page;
use App\Models\TipoListado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
     public function index()
    {
        // Obtener la página de contacto con sus secciones activas y ordenadas
        $page = Page::where('slug', 'contacto')->with(['sections' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }])->first();

        // Si no existe la página, usar datos por defecto
        if (!$page) {
            $sectionsData = [
                'hero' => null,
                'info' => null, 
                'services' => null,
                'contact_info' => null,
                'form_header' => null
            ];
        } else {
            // Convertir las secciones en un array asociativo para fácil acceso
            $sectionsData = [];
            foreach($page->sections as $section) {
                $sectionsData[$section->name] = $section;
            }
        }

        $categories = Category::with('products')->get();
        $tipoListados = TipoListado::where('is_activo', true)->get();

        return view('partner-chefs', compact('sectionsData', 'page', 'categories', 'tipoListados'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|max:255',
            'phone'          => 'required|string|max:20',
            'country_code'   => 'nullable|string|max:5',
            'category_id'    => 'required|exists:categories,id',
            'tipo_listado_id'   => 'nullable|exists:tipo_listados,id',
            'address'        => 'required|string|max:500',
            'message'        => 'required|string|max:2000',
            'whatsapp_contact' => 'nullable|boolean',
        ]);

        $contact = Contact::create($validated);

        // Enviar correo
        $email = env('MAIL_FROM_ADDRESS', 'noreply@example.com');
        Mail::to($email)->send(new ContactFormMail($contact));

        return redirect()->back()->with('success', '¡Gracias por tu mensaje! Te contactaremos pronto.');
    }
}
