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
                $imagePath = $request->file('images')[0]->store('sections/images', 'public');
                $section->setImagesArray([$imagePath]);
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




    //About
    public function editQuienesSomos()
{
    $page = Page::where('slug', 'quienes-somos')->with(['sections' => function($query) {
        $query->orderBy('order');
    }])->first();
    
    // Si no existe la página, crearla con secciones por defecto
    if (!$page) {
        $page = Page::create([
            'slug' => 'quienes-somos',
            'title' => 'Quiénes Somos',
            'content' => 'Página sobre ElectraHome'
        ]);
        
        // Crear secciones por defecto
        $sectionsData = [
            ['name' => 'hero', 'title' => 'Acerca de ElectraHome', 'content' => 'Tradición en Electrodomésticos de Calidad', 'order' => 1],
            ['name' => 'legacy', 'title' => 'Tradición en Electrodomésticos de Calidad', 'content' => 'En ElectraHome, cada electrodoméstico que ofrecemos representa años de innovación...', 'order' => 2],
            ['name' => 'quality', 'title' => 'Garantía Oficial y Servicio Especializado', 'content' => 'Como distribuidores autorizados de Oster, ofrecemos garantía oficial...', 'order' => 3],
            ['name' => 'passion', 'title' => 'La Pasión Detrás del Servicio', 'content' => 'Nuestro equipo no son solo vendedores; somos entusiastas de la cocina...', 'order' => 4],
            ['name' => 'benefits', 'title' => 'Por Qué Elegir ElectraHome', 'content' => 'Elegir ElectraHome significa elegir productos que duran...', 'order' => 5],
            ['name' => 'cta', 'title' => 'Únete a la Familia ElectraHome', 'content' => 'Te invitamos a ser parte de esta historia...', 'order' => 6]
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
    
    // Si no existe la página, crearla con secciones por defecto
    if (!$page) {
        $page = Page::create([
            'slug' => 'contacto',
            'title' => 'Contacto',
            'content' => 'Página de contacto de ElectraHome'
        ]);
        
        // Crear secciones por defecto para contacto
        $sectionsData = [
            [
                'name' => 'hero', 
                'title' => 'Contáctanos', 
                'content' => 'Servicio técnico especializado en línea blanca y electrodomésticos en Quito', 
                'order' => 1
            ],
            [
                'name' => 'info', 
                'title' => '¿Necesitas ayuda con tus electrodomésticos?', 
                'content' => 'En ElectraHome somos especialistas en reparación, mantenimiento e instalación de línea blanca...', 
                'order' => 2
            ],
            [
                'name' => 'services', 
                'title' => 'Nuestros Servicios', 
                'content' => 'Servicios especializados para tu hogar', 
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
    
    // Si no existe la página, crearla con secciones por defecto
    if (!$page) {
        $page = Page::create([
            'slug' => 'servicios',
            'title' => 'Servicios',
            'content' => 'Página de servicios de ElectraHome'
        ]);
        
        // Crear secciones por defecto para servicios
        $sectionsData = [
            [
                'name' => 'hero', 
                'title' => 'Nuestros Servicios', 
                'content' => 'Servicios especializados en electrodomésticos y línea blanca', 
                'order' => 1
            ],
            [
                'name' => 'intro', 
                'title' => 'Expertos en Electrodomésticos', 
                'content' => 'Con años de experiencia en el sector, ofrecemos servicios integrales...', 
                'order' => 2
            ],
            [
                'name' => 'services_list', 
                'title' => 'Servicios Disponibles', 
                'content' => 'Amplia gama de servicios para tus electrodomésticos', 
                'order' => 3
            ],
            [
                'name' => 'process', 
                'title' => 'Nuestro Proceso de Trabajo', 
                'content' => 'Metodología probada para garantizar resultados', 
                'order' => 4
            ],
            [
                'name' => 'why_choose', 
                'title' => 'Por Qué Elegir ElectraHome', 
                'content' => 'Razones que nos convierten en tu mejor opción', 
                'order' => 5
            ],
            [
                'name' => 'cta', 
                'title' => 'Solicita tu Servicio Hoy', 
                'content' => '¿Listo para reparar tu electrodoméstico? Contáctanos ahora', 
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
        
        // Recargar la página con las secciones
        $page = $page->fresh(['sections']);
    }

    // Obtener la página con sus secciones ordenadas
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
    // Obtener la página de servicios con sus secciones activas
    $page = Page::where('slug', 'servicios')->with(['sections' => function($query) {
        $query->where('is_active', true)->orderBy('order');
    }])->first();
    
    // Si no existe la página, crear estructura básica
    if (!$page) {
        $page = Page::create([
            'slug' => 'servicios',
            'title' => 'Nuestros Servicios',
            'content' => 'Página de servicios de ElectraHome'
        ]);
        
        // Crear secciones por defecto
        $this->createDefaultServicesSection($page);
        
        // Recargar con secciones
        $page->load(['sections' => function($query) {
            $query->where('is_active', true)->orderBy('order');
        }]);
    }
    
    // Convertir secciones a array asociativo para fácil acceso
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
            'content' => 'Servicios especializados en electrodomésticos',
            'order' => 1,
            'is_active' => true
        ],
        [
            'name' => 'intro', 
            'title' => 'Expertos en Electrodomésticos',
            'content' => 'Con más de 10 años de experiencia, ofrecemos servicios de reparación y mantenimiento de electrodomésticos con la más alta calidad.',
            'order' => 2,
            'is_active' => true
        ],
        [
            'name' => 'services_list',
            'title' => 'Servicios Disponibles',
            'content' => 'Ofrecemos una amplia gama de servicios especializados',
            'custom_data' => json_encode([
                'service_1_icon' => '🔧',
                'service_1_title' => 'Reparación de Lavadoras',
                'service_1_desc' => 'Diagnóstico y reparación de todo tipo de lavadoras',
                'service_2_icon' => '❄️',
                'service_2_title' => 'Reparación de Refrigeradoras',
                'service_2_desc' => 'Servicio técnico especializado en refrigeración',
                'service_3_icon' => '🍳',
                'service_3_title' => 'Reparación de Cocinas',
                'service_3_desc' => 'Mantenimiento y reparación de cocinas eléctricas y gas',
                'service_4_icon' => '🌀',
                'service_4_title' => 'Reparación de Secadoras',
                'service_4_desc' => 'Servicio completo para secadoras de ropa',
                'service_5_icon' => '⚡',
                'service_5_title' => 'Electrodomésticos Oster',
                'service_5_desc' => 'Reparación especializada en productos Oster',
                'service_6_icon' => '🏠',
                'service_6_title' => 'Servicio a Domicilio',
                'service_6_desc' => 'Atendemos en tu hogar u oficina'
            ]),
            'order' => 3,
            'is_active' => true
        ],
        [
            'name' => 'process',
            'title' => 'Nuestro Proceso de Trabajo',
            'content' => 'Seguimos un proceso sistemático para garantizar el mejor servicio',
            'custom_data' => json_encode([
                'step_1_number' => '1',
                'step_1_title' => 'Diagnóstico',
                'step_1_desc' => 'Evaluamos el problema y identificamos la solución',
                'step_2_number' => '2',
                'step_2_title' => 'Presupuesto',
                'step_2_desc' => 'Te damos un presupuesto claro y sin sorpresas',
                'step_3_number' => '3',
                'step_3_title' => 'Reparación',
                'step_3_desc' => 'Realizamos la reparación con repuestos originales',
                'step_4_number' => '4',
                'step_4_title' => 'Garantía',
                'step_4_desc' => 'Tu electrodoméstico queda con garantía de servicio'
            ]),
            'order' => 4,
            'is_active' => true
        ],
        [
            'name' => 'why_choose',
            'title' => 'Por Qué Elegir ElectraHome',
            'content' => 'Razones por las cuales somos tu mejor opción',
            'custom_data' => json_encode([
                'reason_1_icon' => '⭐',
                'reason_1_title' => 'Experiencia Comprobada',
                'reason_1_desc' => 'Más de 10 años reparando electrodomésticos',
                'reason_2_icon' => '🛡️',
                'reason_2_title' => 'Garantía Completa',
                'reason_2_desc' => 'Todos nuestros trabajos incluyen garantía',
                'reason_3_icon' => '⚡',
                'reason_3_title' => 'Servicio Rápido',
                'reason_3_desc' => 'Atención inmediata y respuesta en 24h',
                'reason_4_icon' => '💰',
                'reason_4_title' => 'Precios Justos',
                'reason_4_desc' => 'Presupuestos transparentes sin costos ocultos'
            ]),
            'order' => 5,
            'is_active' => true
        ],
        [
            'name' => 'cta',
            'title' => 'Solicita tu Servicio Hoy',
            'content' => '¿Necesitas reparar tu electrodoméstico? Contáctanos ahora y recibe atención personalizada. Nuestros expertos están listos para ayudarte.',
            'custom_data' => json_encode([
                'button_primary_text' => 'Contactar Ahora',
                'button_secondary_text' => 'Ver Más Servicios'
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