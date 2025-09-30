<?php
// app/Http/Controllers/Admin/PageController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;
use App\Models\Section;
use Illuminate\Support\Facades\Log; 

class PageController extends Controller
{
    public function index()
    {
        $pages = Page::orderBy('slug')->get();
        return view('admin.pages.index', compact('pages'));
    }

    // === MÉTODOS ESPECÍFICOS PARA CADA PÁGINA ===

    // Página de INICIO
    public function editInicio()
    {
        $page = Page::where('slug', 'inicio')->firstOrFail();
        return view('admin.pages.edit-inicio', compact('page'));
    }

    public function updateInicio(Request $request)
    {
        $page = Page::where('slug', 'inicio')->firstOrFail();
        return $this->updatePage($request, $page, 'admin.pages.edit-inicio');
    }

  



    // === MÉTODO COMPARTIDO PARA ACTUALIZAR ===
    private function updatePage(Request $request, Page $page, $redirectRoute)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'section' => 'nullable|string|max:255',
            'images.*' => 'nullable|image|max:2048',
            'video_urls' => 'nullable|string'
        ]);

        // Actualizar datos básicos
        $page->title = $request->title;
        $page->content = $request->content;
        $page->section = $request->section;

        // Manejar imágenes nuevas
        $currentImages = $page->getImagesArray();
        
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('pages', 'public');
                $currentImages[] = $path;
            }
        }
        
        $page->setImagesArray($currentImages);

        // Manejar videos
        $videos = [];
        if ($request->filled('video_urls')) {
            $videoLines = explode("\n", $request->video_urls);
            foreach ($videoLines as $line) {
                $url = trim($line);
                if (!empty($url)) {
                    $videos[] = $url;
                }
            }
        }
        $page->setVideosArray($videos);

        $page->save();

        return redirect()->route($redirectRoute)
            ->with('success', 'Página actualizada correctamente');
    }

    // === MÉTODO PARA ELIMINAR IMÁGENES ===
    public function deleteImage(Request $request, Page $page)
    {
        $imageIndex = $request->input('image_index');
        $images = $page->getImagesArray();

        if (isset($images[$imageIndex])) {
            \Storage::disk('public')->delete($images[$imageIndex]);
            
            unset($images[$imageIndex]);
            $images = array_values($images);
            
            $page->setImagesArray($images);
            $page->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    

   
  public function manageSections(Page $page)
    {
        $sections = $page->sections()->ordered()->get();
        return view('admin.pages.sections', compact('page', 'sections'));
    }

public function updateSection(Request $request, $pageId, $sectionId)
{
    // DEBUG: Ver qué datos llegan
    \Log::info('=== UPDATE SECTION UNIVERSAL ===');
    \Log::info('Page ID: ' . $pageId);
    \Log::info('Section ID: ' . $sectionId);
    \Log::info('Request Data: ', $request->all());
    
    $page = Page::findOrFail($pageId);
    $section = Section::findOrFail($sectionId);
    
    \Log::info('Page found: ' . $page->slug);
    \Log::info('Section found: ' . $section->name);
    
    // Verificar que la sección pertenece a la página
   if ($section->page_id != $page->id) {
    \Log::error('Section does not belong to page. Section page_id: ' . $section->page_id . ', Page id: ' . $page->id);
        abort(404, 'Sección no encontrada en esta página');
    }

    \Log::info('Section ownership verified');

    // Validación básica
    try {
        $request->validate([
            'title' => 'string|max:255',
            'content' => 'nullable|string',
            'is_active' => 'nullable',
            'images.*' => 'nullable|image|max:2048'
        ]);
        \Log::info('Validation passed');
    } catch (\Exception $e) {
        \Log::error('Validation failed: ' . $e->getMessage());
        throw $e;
    }

    // Datos anteriores
    \Log::info('Before update - Title: ' . $section->title);
    \Log::info('Before update - Content: ' . $section->content);
    
    // Actualizar datos básicos
    $section->title = $request->title;
    $section->content = $request->content;
    $section->is_active = $request->has('is_active') ? true : false;

    \Log::info('After assignment - Title: ' . $section->title);
    \Log::info('After assignment - Content: ' . $section->content);
    \Log::info('After assignment - Is Active: ' . ($section->is_active ? 'true' : 'false'));

    // ===== PROCESAR CAMPOS ESPECÍFICOS UNIVERSALMENTE =====
    $customData = [];

    switch ($section->name) {
        // === SECCIONES PARA "QUIÉNES SOMOS" ===
        case 'legacy':
            $customData = [
                'paragraph_1' => $request->input('paragraph_1'),
                'paragraph_2' => $request->input('paragraph_2'),
                'quote' => $request->input('quote')
            ];
            \Log::info('Legacy custom data: ', $customData);
            break;

        case 'quality':
            $customData = [
                'paragraph_1' => $request->input('paragraph_1'),
                'paragraph_2' => $request->input('paragraph_2'),
                'badge_1' => $request->input('badge_1'),
                'badge_2' => $request->input('badge_2'),
                'badge_3' => $request->input('badge_3'),
                'badge_4' => $request->input('badge_4')
            ];
            \Log::info('Quality custom data: ', $customData);
            break;

        case 'passion':
            $customData = [
                'paragraph_1' => $request->input('paragraph_1'),
                'paragraph_2' => $request->input('paragraph_2'),
                'team_quote' => $request->input('team_quote'),
                'quote_author' => $request->input('quote_author')
            ];
            \Log::info('Passion custom data: ', $customData);
            break;

        case 'benefits':
            $customData = [
                'paragraph_1' => $request->input('paragraph_1'),
                'paragraph_2' => $request->input('paragraph_2'),
                'benefit_1_icon' => $request->input('benefit_1_icon'),
                'benefit_1_title' => $request->input('benefit_1_title'),
                'benefit_1_desc' => $request->input('benefit_1_desc'),
                'benefit_2_icon' => $request->input('benefit_2_icon'),
                'benefit_2_title' => $request->input('benefit_2_title'),
                'benefit_2_desc' => $request->input('benefit_2_desc'),
                'benefit_3_icon' => $request->input('benefit_3_icon'),
                'benefit_3_title' => $request->input('benefit_3_title'),
                'benefit_3_desc' => $request->input('benefit_3_desc')
            ];
            \Log::info('Benefits custom data: ', $customData);
            break;

        case 'cta':
            $customData = [
                'button_text' => $request->input('button_text'),
                'final_question' => $request->input('final_question')
            ];
            \Log::info('CTA custom data: ', $customData);
            break;

        // === SECCIONES PARA "CONTACTO" ===
        case 'services':
            $customData = [
                'service_1_icon' => $request->input('service_1_icon'),
                'service_1_title' => $request->input('service_1_title'),
                'service_1_desc' => $request->input('service_1_desc'),
                'service_2_icon' => $request->input('service_2_icon'),
                'service_2_title' => $request->input('service_2_title'),
                'service_2_desc' => $request->input('service_2_desc'),
                'service_3_icon' => $request->input('service_3_icon'),
                'service_3_title' => $request->input('service_3_title'),
                'service_3_desc' => $request->input('service_3_desc'),
                'service_4_icon' => $request->input('service_4_icon'),
                'service_4_title' => $request->input('service_4_title'),
                'service_4_desc' => $request->input('service_4_desc')
            ];
            \Log::info('Services custom data: ', $customData);
            break;

        case 'contact_info':
            $customData = [
                'whatsapp_number' => $request->input('whatsapp_number'),
                'whatsapp_link' => $request->input('whatsapp_link'),
                'phone_number' => $request->input('phone_number'),
                'phone_link' => $request->input('phone_link'),
                'email' => $request->input('email'),
                'email_link' => $request->input('email_link'),
                'schedule_weekdays' => $request->input('schedule_weekdays'),
                'schedule_saturday' => $request->input('schedule_saturday')
            ];
            \Log::info('Contact info custom data: ', $customData);
            break;

        // === SECCIONES PARA "SERVICIOS" (futuras) ===
        case 'service_list':
            $customData = [
                'service_list_data' => $request->input('service_list_data')
            ];
            \Log::info('Service list custom data: ', $customData);
            break;

        // === SECCIONES GENÉRICAS (HERO, INFO, etc.) ===
        case 'hero':
        case 'info':
        case 'form_header':
            // Estas secciones solo usan title y content, no necesitan custom_data
            \Log::info($section->name . ' section - using only title and content');
            break;
        
        case 'social':
            $customData = [
                'facebook' => $request->input('facebook'),
                'instagram' => $request->input('instagram'),
                'whatsapp' => $request->input('whatsapp'),
                'email' => $request->input('email'),
            ];
            break;

        case 'nav':
            // Espera links[label,route] (array)
            $customData = [
                'links' => $request->input('links', []),
            ];
            break;

        case 'contact':
            $customData = [
                'email' => $request->input('email'),
                'phone' => $request->input('phone'),
                'phone_link' => $request->input('phone_link'),
                'location' => $request->input('location'),
            ];
            break;

        case 'hours':
            $customData = [
                'weekdays' => $request->input('weekdays'),
                'saturday' => $request->input('saturday'),
                'sunday' => $request->input('sunday'),
            ];
            break;

        case 'badges':
            $customData = [
                'items' => $request->input('items', []), // items[][icon,text]
            ];
            break;

        case 'bottom':
            $customData = [
                'copyright' => $request->input('copyright'),
            ];
            break;

        // === SECCIONES FUTURAS ===
        default:
            \Log::info('Unknown section type: ' . $section->name . ' - no custom data processing');
            break;
    }

    // Guardar custom data si hay
    if (!empty($customData)) {
        \Log::info('Setting custom data...');
        
        // Verificar si el método existe
        if (method_exists($section, 'setCustomDataArray')) {
            $section->setCustomDataArray($customData);
            \Log::info('Custom data set via setCustomDataArray');
        } else {
            // Fallback manual
            $section->custom_data = $customData;
            \Log::info('Custom data set directly to custom_data field');
        }
        
        \Log::info('Custom data after setting: ', $section->custom_data ?? []);
    }

    // Procesar imágenes Y VIDEOS
    if ($request->hasFile('images') || $request->hasFile('hero_video')) {
        \Log::info('Processing media files...');
        
        if ($section->name === 'hero') {
            // ===== LÓGICA ESPECIAL PARA HERO: VIDEO O IMÁGENES =====
            
            // Procesar video de hero (solo para página inicio)
            if ($request->hasFile('hero_video') && $request->input('media_type') === 'video') {
                \Log::info('Processing hero video...');
                
                // Eliminar video anterior si existe
                $currentVideos = $section->getVideosArray();
                if (!empty($currentVideos)) {
                    foreach ($currentVideos as $oldVideo) {
                        \Storage::disk('public')->delete($oldVideo);
                        \Log::info('Deleted old video: ' . $oldVideo);
                    }
                }

                // Eliminar imágenes si había (porque ahora usa video)
                $currentImages = $section->getImagesArray();
                if (!empty($currentImages)) {
                    foreach ($currentImages as $oldImage) {
                        \Storage::disk('public')->delete($oldImage);
                        \Log::info('Deleted old image: ' . $oldImage);
                    }
                    $section->setImagesArray([]);
                }

                // Guardar nuevo video
                $videoPath = $request->file('hero_video')->store('sections/videos', 'public');
                $section->setVideosArray([$videoPath]);
                \Log::info('Hero video saved: ' . $videoPath);
            }
            // Procesar imágenes de hero (si no hay video o media_type es images)
            elseif ($request->hasFile('images') && $request->input('media_type') !== 'video') {
                \Log::info('Processing hero images...');
                
                // Eliminar video si existía (porque ahora usa imágenes)
                $currentVideos = $section->getVideosArray();
                if (!empty($currentVideos)) {
                    foreach ($currentVideos as $oldVideo) {
                        \Storage::disk('public')->delete($oldVideo);
                        \Log::info('Deleted old video: ' . $oldVideo);
                    }
                    $section->setVideosArray([]);
                }

                // Hero: reemplazar imagen existente (solo 1)
                $currentImages = $section->getImagesArray();
                if (!empty($currentImages)) {
                    foreach ($currentImages as $oldImage) {
                        \Storage::disk('public')->delete($oldImage);
                    }
                }
                $imagePaths = [];
                // Iterar sobre todas las imágenes
                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $image) {
                        $imagePath = $image->store('sections/images', 'public');
                        $imagePaths[] = $imagePath;
                    }
                }
                $section->setImagesArray($imagePaths);
                // $imagePath = $request->file('images')[0]->store('sections/images', 'public');
                // $section->setImagesArray([$imagePath]);
                \Log::info('Hero image saved: ' . $imagePath);
            }
            
        } else {
            // ===== OTRAS SECCIONES: SOLO IMÁGENES NORMALES =====
            if ($request->hasFile('images')) {
                \Log::info('Processing regular images for section: ' . $section->name);
                
                // Agregar a las imágenes existentes
                $currentImages = $section->getImagesArray();
                foreach ($request->file('images') as $image) {
                    $imagePath = $image->store('sections/images', 'public');
                    $currentImages[] = $imagePath;
                }
                $section->setImagesArray($currentImages);
                \Log::info('Images added to section');
            }
        }
    }

    // Intentar guardar
    try {
        $result = $section->save();
        \Log::info('Section save result: ' . ($result ? 'SUCCESS' : 'FAILED'));
        
        // Verificar que se guardó
        $section->refresh();
        \Log::info('After save - Title: ' . $section->title);
        \Log::info('After save - Content: ' . $section->content);
        \Log::info('After save - Custom Data: ', $section->custom_data ?? []);
        
    } catch (\Exception $e) {
        \Log::error('Save failed: ' . $e->getMessage());
        \Log::error('Exception trace: ' . $e->getTraceAsString());
        
        return redirect()->back()
            ->with('error', 'Error al guardar: ' . $e->getMessage())
            ->withInput();
    }

    \Log::info('=== END DEBUG ===');

    // ===== REDIRECT UNIVERSAL - FUNCIONA CON CUALQUIER PÁGINA =====
    $redirectRoute = $this->getPageEditRoute($page->slug);
    
    \Log::info('Redirecting to: ' . $redirectRoute . ' (Page slug: ' . $page->slug . ')');

    return redirect()->route($redirectRoute)
        ->with('success', "Sección '{$section->title}' actualizada correctamente");
}

// ===== MÉTODO HELPER PARA REDIRECT UNIVERSAL =====
private function getPageEditRoute($pageSlug)
{
    // Mapeo de slugs a rutas de edición
    $routeMap = [
        'inicio' => 'admin.pages.edit-inicio',
        'quienes-somos' => 'admin.pages.edit-quienes-somos', 
        'contacto' => 'admin.pages.edit-contacto',
        'servicios' => 'admin.pages.edit-servicios',
        'productos' => 'admin.pages.edit-productos',
        'blog' => 'admin.pages.edit-blog',
        'footer' => 'admin.pages.edit-footer', 
        // Fácil agregar más páginas aquí...
    ];

    // Si existe la ruta específica, usarla
    if (isset($routeMap[$pageSlug])) {
        return $routeMap[$pageSlug];
    }

    // Fallback 1: Intentar generar automáticamente
    $autoRoute = 'admin.pages.edit-' . $pageSlug;
    if (\Route::has($autoRoute)) {
        \Log::info('Using auto-generated route: ' . $autoRoute);
        return $autoRoute;
    }

    // Fallback 2: Ir al index general
    \Log::warning('No specific edit route found for page: ' . $pageSlug . ', redirecting to index');
    return 'admin.pages.index';
}

    // Método para eliminar video de Hero
    public function deleteSectionVideo(Request $request, Page $page, Section $section)
    {
        if ($section->name !== 'hero') {
            return response()->json(['success' => false, 'message' => 'Solo Hero puede tener videos'], 400);
        }

        $videos = $section->getVideosArray();
        
        if (!empty($videos)) {
            // Eliminar archivo físico
            foreach ($videos as $video) {
                \Storage::disk('public')->delete($video);
            }
            
            // Limpiar de la base de datos
            $section->setVideosArray([]);
            $section->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    // Método para eliminar imagen de una sección
    public function deleteSectionImage(Request $request, $pageId, $sectionId)
    {
        $page = Page::findOrFail($pageId);
        $section = Section::findOrFail($sectionId);
        
        // Verificar que la sección pertenece a la página
if ($section->page_id != $page->id) {
            return response()->json(['success' => false, 'message' => 'Sección no válida'], 404);
        }

        $imageIndex = $request->input('image_index');
        $images = $section->getImagesArray();

        if (isset($images[$imageIndex])) {
            \Storage::disk('public')->delete($images[$imageIndex]);
            
            unset($images[$imageIndex]);
            $images = array_values($images);
            
            $section->setImagesArray($images);
            $section->save();

            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 404);
    }

    public function editFooter()
    {
        $page = Page::where('slug', 'footer')
            ->with(['sections' => fn($q) => $q->orderBy('order')])
            ->first();

        if (!$page) {
            $page = Page::create([
                'slug' => 'footer',
                'title' => 'Footer',
                'content' => 'Configuración del pie de página',
            ]);

            // Secciones del footer (todas editables)
            $defs = [
                ['name'=>'about','title'=>env('APP_NAME','CaballosApp'),
                    'content'=>'Marketplace para comprar y vender caballos...',
                    'custom_data'=>null,'order'=>1],
                ['name'=>'social','title'=>'Redes','content'=>'', 'custom_data'=>[
                    'facebook'=>'https://facebook.com/',
                    'instagram'=>'https://instagram.com/',
                    'whatsapp'=>'https://wa.me/573000000000',
                    'email'=>'info@freepegasus.com',
                ],'order'=>2],
                ['name'=>'nav','title'=>'Navegación','content'=>'', 'custom_data'=>[
                    'links'=>[
                        ['label'=>'Inicio','route'=>'home'],
                        ['label'=>'Caballos','route'=>'shop.index'],
                        ['label'=>'Quiénes Somos','route'=>'about'],
                        ['label'=>'Contacto','route'=>'contact.index'],
                        ['label'=>'Publica tu Caballo','route'=>'recipes'],
                    ],
                ],'order'=>3],
                ['name'=>'contact','title'=>'Contacto','content'=>'', 'custom_data'=>[
                    'email'=>'info@freepegasus.com',
                    'phone'=>'+57 300 000 0000',
                    'phone_link'=>'tel:+573000000000',
                    'location'=>'Bogotá, Colombia (Atención LATAM)',
                ],'order'=>4],
                ['name'=>'hours','title'=>'Horarios','content'=>'', 'custom_data'=>[
                    'weekdays'=>'Lunes a Viernes: 8:00 AM - 6:00 PM',
                    'saturday'=>'Sábados: 8:00 AM - 4:00 PM',
                    'sunday'=>'Domingos: Cerrado',
                ],'order'=>5],
                ['name'=>'badges','title'=>'Sellos','content'=>'', 'custom_data'=>[
                    'items'=>[
                        ['icon'=>'fas fa-user-check','text'=>'Vendedores verificados'],
                        ['icon'=>'fas fa-truck','text'=>'Transporte y bienestar'],
                        ['icon'=>'fas fa-shield-alt','text'=>'Compra protegida'],
                    ],
                ],'order'=>6],
                ['name'=>'bottom','title'=>'Legal','content'=>'', 'custom_data'=>[
                    'copyright'=>'© '.date('Y').' '.env('APP_NAME','CaballosApp').'. Todos los derechos reservados.',
                ],'order'=>7],
            ];

            foreach ($defs as $d) {
                $page->sections()->create([
                    'name'=>$d['name'],'title'=>$d['title'],'content'=>$d['content'],
                    'custom_data'=>$d['custom_data'],'order'=>$d['order'],'is_active'=>true,
                ]);
            }
            $page->load('sections');
        }

        return view('admin.pages.edit-footer', compact('page'));
    }

    public function updateFooter(Request $request)
    {
        $page = Page::where('slug', 'footer')->firstOrFail();
        return $this->updatePage($request, $page, 'admin.pages.edit-footer');
    }




    //About
    public function editQuienesSomos()
{
    $page = Page::where('slug', 'quienes-somos')->with(['sections' => function($query) {
        $query->orderBy('order');
    }])->first();

    $appName = env('APP_NAME', 'CaballosApp');
    
    // Si no existe la página, crearla con secciones por defecto
    if (!$page) {
        $page = Page::create([
            'slug' => 'quienes-somos',
            'title' => 'Quiénes Somos',
            'content' => 'Página sobre ' . $appName
        ]);
        
        // Crear secciones por defecto (enfocadas en caballos)
        $sectionsData = [
            ['name' => 'hero', 'title' => 'Acerca de ' . $appName, 'content' => 'Tradición en cría y comercio equino', 'order' => 1],
            ['name' => 'legacy', 'title' => 'Tradición en cría y comercio equino', 'content' => 'En ' . $appName . ', cada caballo listado refleja años de selección responsable, bienestar animal y pasión por el mundo ecuestre.', 'order' => 2],
            ['name' => 'quality', 'title' => 'Transparencia, bienestar y seguridad', 'content' => 'Trabajamos con vendedores verificados, documentación al día (pedigrí, exámenes veterinarios) y procesos de compra seguros (depósito en garantía / Mercado Pago).', 'order' => 3],
            ['name' => 'passion', 'title' => 'Nuestra pasión por los caballos', 'content' => 'Somos una comunidad de criadores, jinetes y profesionales. Ponemos el bienestar del caballo y la confianza del comprador en el centro de todo.', 'order' => 4],
            ['name' => 'benefits', 'title' => 'Por qué elegir ' . $appName, 'content' => 'Acceso a ejemplares verificados, trato justo, soporte durante la transacción y una comunidad confiable.', 'order' => 5],
            ['name' => 'cta', 'title' => 'Únete a la comunidad ' . $appName, 'content' => 'Encuentra el caballo ideal o publica tus ejemplares con herramientas de gestión, verificación y visibilidad.', 'order' => 6]
        ];
        
        foreach ($sectionsData as $sectionData) {
            $page->sections()->create([
                'name' => $sectionData['name'],
                'title' => $sectionData['title'],
                'content' => $sectionData['content'],
                'order' => $sectionData['order'],
                'is_active' => true
            ]);
        }
    }
    
    return view('admin.pages.edit-quienes-somos', compact('page'));
}


public function updateQuienesSomos(Request $request)
{
    $page = Page::where('slug', 'quienes-somos')->firstOrFail();
    return $this->updatePage($request, $page, 'admin.pages.edit-quienes-somos');
}

//Contacto

public function editContacto()
{
    $page = Page::where('slug', 'contacto')->with(['sections' => function($query) {
        $query->orderBy('order');
    }])->first();
    
    if (!$page) {
        $page = Page::create([
            'slug' => 'contacto',
            'title' => 'Contacto',
            'content' => "Página de contacto de " . env('APP_NAME', 'CaballosApp')
        ]);
        
        $sectionsData = [
            [
                'name' => 'hero', 
                'title' => 'Contáctanos', 
                'content' => 'Marketplace especializado en compra y venta de caballos', 
                'order' => 1
            ],
            [
                'name' => 'info', 
                'title' => '¿Buscas el caballo ideal?', 
                'content' => 'En ' . env('APP_NAME', 'CaballosApp') . ' conectamos compradores con criadores y vendedores verificados. Publica tus ejemplares o encuentra tu próximo caballo con seguridad.', 
                'order' => 2
            ],
            [
                'name' => 'services', 
                'title' => 'Nuestros Servicios', 
                'content' => 'Servicios para compradores, vendedores y granjas de cría (haras)', 
                'order' => 3
            ],
            [
                'name' => 'contact_info', 
                'title' => 'Información de Contacto', 
                'content' => 'Datos de contacto y horarios', 
                'order' => 4
            ],
            [
                'name' => 'form_config', 
                'title' => 'Configuración del Formulario', 
                'content' => 'Configuración del formulario de contacto', 
                'order' => 5
            ]
        ];
        
        foreach ($sectionsData as $sectionData) {
            $page->sections()->create([
                'name' => $sectionData['name'],
                'title' => $sectionData['title'],
                'content' => $sectionData['content'],
                'order' => $sectionData['order'],
                'is_active' => true
            ]);
        }
    }
    
    return view('admin.pages.edit-contacto', compact('page'));
}
public function updateContacto(Request $request)
{
    $page = Page::where('slug', 'contacto')->firstOrFail();
    return $this->updatePage($request, $page, 'admin.pages.edit-contacto');
}


public function editServicios()
{
    $page = Page::where('slug', 'servicios')->with(['sections' => function($query) {
        $query->orderBy('order');
    }])->first();
    
    if (!$page) {
        $page = Page::create([
            'slug' => 'servicios',
            'title' => 'Servicios',
            'content' => 'Página de servicios de ' . env('APP_NAME', 'CaballosApp')
        ]);
        
        $sectionsData = [
            [
                'name' => 'hero', 
                'title' => 'Nuestros Servicios', 
                'content' => 'Servicios para compra y venta de caballos', 
                'order' => 1
            ],
            [
                'name' => 'intro', 
                'title' => 'Expertos en el mundo equino', 
                'content' => 'Con años de experiencia, ofrecemos soluciones para compradores, vendedores y haras: publicación, verificación y soporte en transacciones.', 
                'order' => 2
            ],
            [
                'name' => 'services_list', 
                'title' => 'Servicios Disponibles', 
                'content' => 'Servicios clave para transacciones seguras y exitosas', 
                'order' => 3
            ],
            [
                'name' => 'process', 
                'title' => 'Cómo funciona', 
                'content' => 'Proceso diseñado para seguridad, transparencia y bienestar animal', 
                'order' => 4
            ],
            [
                'name' => 'why_choose', 
                'title' => 'Por qué elegir ' . env('APP_NAME', 'CaballosApp'), 
                'content' => 'Razones para confiar en nosotros', 
                'order' => 5
            ],
            [
                'name' => 'cta', 
                'title' => 'Publica o encuentra tu caballo hoy', 
                'content' => '¿Listo para publicar o comprar? Contáctanos y te acompañamos en todo el proceso.', 
                'order' => 6
            ]
        ];

        foreach ($sectionsData as $sectionData) {
            try {
                $section = $page->sections()->create([
                    'name' => $sectionData['name'],
                    'title' => $sectionData['title'],
                    'content' => $sectionData['content'],
                    'order' => $sectionData['order'],
                    'is_active' => true
                ]);
                
                \Log::info("Sección {$sectionData['name']} creada para servicios con ID: {$section->id}");
            } catch (\Exception $e) {
                \Log::error("Error creando sección {$sectionData['name']} para servicios: " . $e->getMessage());
            }
        }
        
        $page = $page->fresh(['sections']);
    }

    $page = Page::where('slug', 'servicios')->with(['sections' => function($query) {
        $query->orderBy('order');
    }])->first();

    return view('admin.pages.edit-servicios', compact('page'));
}


public function updateServicios(Request $request)
{
    $page = Page::where('slug', 'servicios')->firstOrFail();
    return $this->updatePage($request, $page, 'admin.pages.edit-servicios');
}


public function servicios()
{
    $page = Page::where('slug', 'servicios')->with(['sections' => function($query) {
        $query->where('is_active', true)->orderBy('order');
    }])->first();
    
    if (!$page) {
        $page = Page::create([
            'slug' => 'servicios',
            'title' => 'Nuestros Servicios',
            'content' => 'Página de servicios de ' . env('APP_NAME', 'CaballosApp')
        ]);
        
        $this->createDefaultServicesSection($page);
        
        $page->load(['sections' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }]);
    }
    
    $sectionsData = [];
    foreach($page->sections as $section) {
        $sectionsData[$section->name] = $section;
    }
    
    return view('recipes', compact('sectionsData', 'page'));
}

/**
 * Crear secciones por defecto para servicios
 */
private function createDefaultServicesSection($page)
{
    $sections = [
        [
            'name' => 'hero',
            'title' => 'Nuestros Servicios',
            'content' => 'Servicios especializados para el mercado equino',
            'order' => 1,
            'is_active' => true
        ],
        [
            'name' => 'intro', 
            'title' => 'Expertos en el mundo equino',
            'content' => 'Ofrecemos publicación de listados, verificación documental y veterinaria, asesoría y soporte durante todo el proceso de compra/venta.',
            'order' => 2,
            'is_active' => true
        ],
        [
            'name' => 'services_list',
            'title' => 'Servicios Disponibles',
            'content' => 'Soluciones para compradores, vendedores y haras',
            'custom_data' => json_encode([
                'service_1_icon' => '🐎',
                'service_1_title' => 'Asesoría de compra/venta',
                'service_1_desc' => 'Acompañamiento experto para elegir o listar caballos',
                'service_2_icon' => '⭐',
                'service_2_title' => 'Publicación Premium/Destacada',
                'service_2_desc' => 'Mayor visibilidad en la plataforma',
                'service_3_icon' => '🩺',
                'service_3_title' => 'Verificación veterinaria',
                'service_3_desc' => 'Chequeos y pruebas documentadas',
                'service_4_icon' => '📄',
                'service_4_title' => 'Gestión de pedigrí y documentos',
                'service_4_desc' => 'Documentación y trámites ordenados',
                'service_5_icon' => '🚚',
                'service_5_title' => 'Transporte equino',
                'service_5_desc' => 'Logística segura para el caballo',
                'service_6_icon' => '🏇',
                'service_6_title' => 'Entrenamiento y acondicionamiento',
                'service_6_desc' => 'Programas a medida con profesionales'
            ]),
            'order' => 3,
            'is_active' => true
        ],
        [
            'name' => 'process',
            'title' => 'Cómo funciona',
            'content' => 'Proceso centrado en seguridad y bienestar',
            'custom_data' => json_encode([
                'step_1_number' => '1',
                'step_1_title' => 'Evaluación del ejemplar',
                'step_1_desc' => 'Información, historial y salud',
                'step_2_number' => '2',
                'step_2_title' => 'Publicación y alcance',
                'step_2_desc' => 'Listados optimizados y difusión',
                'step_3_number' => '3',
                'step_3_title' => 'Negociación segura',
                'step_3_desc' => 'Soporte y pago seguro (Mercado Pago)',
                'step_4_number' => '4',
                'step_4_title' => 'Entrega y seguimiento',
                'step_4_desc' => 'Logística y posventa responsable'
            ]),
            'order' => 4,
            'is_active' => true
        ],
        [
            'name' => 'why_choose',
            'title' => 'Por qué elegir ' . env('APP_NAME', 'CaballosApp'),
            'content' => 'Confianza, bienestar y alcance',
            'custom_data' => json_encode([
                'reason_1_icon' => '🛡️',
                'reason_1_title' => 'Vendedores verificados',
                'reason_1_desc' => 'Perfiles y reputación auditables',
                'reason_2_icon' => '❤️',
                'reason_2_title' => 'Bienestar y transparencia',
                'reason_2_desc' => 'Documentación, pedigrí y controles',
                'reason_3_icon' => '💳',
                'reason_3_title' => 'Pago seguro',
                'reason_3_desc' => 'Integramos Mercado Pago',
                'reason_4_icon' => '🌍',
                'reason_4_title' => 'Alcance internacional',
                'reason_4_desc' => 'Visibilidad para compradores y haras'
            ]),
            'order' => 5,
            'is_active' => true
        ],
        [
            'name' => 'cta',
            'title' => 'Publica o encuentra tu caballo hoy',
            'content' => '¿Listo para publicar o comprar? Contáctanos y te acompañamos de principio a fin.',
            'custom_data' => json_encode([
                'button_primary_text' => 'Contactar ahora',
                'button_secondary_text' => 'Ver más servicios'
            ]),
            'order' => 6,
            'is_active' => true
        ]
    ];
    
    foreach($sections as $sectionData) {
        $page->sections()->create($sectionData);
    }
}




}