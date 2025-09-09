<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Admin\ProductController; // <-- NUEVO
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;

/* ---------- Landing y páginas públicas ---------- */
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contacto', [App\Http\Controllers\ContactController::class, 'index'])->name('contact.index');
Route::post('/contacto', [App\Http\Controllers\ContactController::class, 'store'])->name('contact.submit');
Route::get('/recipes', [App\Http\Controllers\Admin\PageController::class, 'servicios'])->name('recipes');



Route::delete('/admin/products/images/{id}', [App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('admin.products.images.destroy');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/calculate', [ShopController::class, 'calculateShippingAndTax'])->name('checkout.calculate');
Route::post('/order/process', [ShopController::class, 'processOrder'])->name('order.process');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{product}', [ProductController::class, 'show'])->name('product.show');
Route::view('/insiders','insiders')->name('insiders');

Route::post('/products/{product}/questions', [ProductQuestionController::class, 'store'])
    ->name('products.questions.store');
Route::put('/admin/questions/{question}', [ProductQuestionController::class, 'update'])
    ->name('admin.questions.update');


//los de dentro de recipes
Route::put('/admin/pages/{page}/sections/{section}', [App\Http\Controllers\Admin\PageController::class, 'updateSection'])->name('admin.pages.sections.update');
Route::view('/chefs',   'chefs')->name('chefs');
Route::view('/wholesale','wholesale')->name('wholesale');
Route::get('/admin/seo/{page}/edit', [App\Http\Controllers\Admin\SeoController::class, 'edit'])->name('admin.seo.edit');
Route::put('/admin/seo/{page}', [App\Http\Controllers\Admin\SeoController::class, 'update'])->name('admin.seo.update');
/* ---------- Catálogo y carrito ---------- */
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::delete('/admin/products/images/{id}', [App\Http\Controllers\Admin\ProductImageController::class, 'destroy'])->name('admin.products.images.destroy');
Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout/calculate', [ShopController::class, 'calculateShippingAndTax'])->name('checkout.calculate');
Route::post('/order/process', [ShopController::class, 'processOrder'])->name('order.process');

// Ruta para la pasarela de pago
Route::get('/payment/gateway/{order}', [App\Http\Controllers\ShopController::class, 'paymentGateway'])->name('payment.gateway');
Route::post('/payment/process/{order}', [App\Http\Controllers\ShopController::class, 'processPayment'])->name('payment.process');
Route::get('/payment/success/{order}', [App\Http\Controllers\ShopController::class, 'paymentSuccess'])->name('payment.success');

Route::post('/cart',           [CartController::class, 'add'])->name('cart.add');
Route::get('/cart',            [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/{rowId}',  [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{rowId}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/shipping-policy', function () {
    return view('policies.shipping');
})->name('shipping.policy');

Route::get('/return-policy', function () {
    return view('policies.return');
})->name('return.policy');

Route::get('/refund-policy', function () {
    return view('policies.refund');
})->name('refund.policy');

Route::get('/terms-conditions', function () {
    return view('policies.terms');
})->name('terms.conditions');

Route::get('/account-not-verified', function () {
    return view('auth.account-not-verified');
})->name('verification.notice.admin');


/* ---------- Dashboard y perfil ---------- */
Route::middleware(['auth', 'verified', 'verified.user'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile',    [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',  [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // routes/web.php - SOLO cambia las rutas de páginas

    Route::prefix('admin')->as('admin.')->middleware(['auth', 'role:admin'])->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-verify', [UserController::class, 'toggleVerify'])->name('users.toggle-verify');

        // Countries (NO TOCAR - YA FUNCIONAN)
        Route::get('/countries', [LocationController::class, 'countriesIndex'])->name('countries.index');
        Route::post('/countries', [LocationController::class, 'countriesStore'])->name('countries.store');
        Route::delete('/countries/{id}', [LocationController::class, 'countriesDestroy'])->name('countries.destroy');

        // Cities (NO TOCAR - YA FUNCIONAN)
        Route::get('/cities', [LocationController::class, 'citiesIndex'])->name('cities.index');
        Route::post('/cities', [LocationController::class, 'citiesStore'])->name('cities.store');
        Route::delete('/cities/{id}', [LocationController::class, 'citiesDestroy'])->name('cities.destroy');

        // === RUTAS ESPECÍFICAS PARA PÁGINAS ===
        
        // Lista general de páginas
        Route::get('pages', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('pages.index');
        
        // Página INICIO
        Route::get('pages/inicio/edit', [App\Http\Controllers\Admin\PageController::class, 'editInicio'])->name('pages.edit-inicio');
        Route::put('pages/inicio', [App\Http\Controllers\Admin\PageController::class, 'updateInicio'])->name('pages.update-inicio');
        
        // Página QUIÉNES SOMOS
        Route::get('pages/quienes-somos/edit', [App\Http\Controllers\Admin\PageController::class, 'editQuienesSomos'])->name('pages.edit-quienes-somos');
        Route::put('pages/quienes-somos', [App\Http\Controllers\Admin\PageController::class, 'updateQuienesSomos'])->name('pages.update-quienes-somos');
        
        // Página SERVICIOS
        Route::get('pages/servicios/edit', [App\Http\Controllers\Admin\PageController::class, 'editServicios'])->name('pages.edit-servicios');
        Route::put('pages/servicios', [App\Http\Controllers\Admin\PageController::class, 'updateServicios'])->name('pages.update-servicios');
        
        // Página CONTACTO
        Route::get('pages/contacto/edit', [App\Http\Controllers\Admin\PageController::class, 'editContacto'])->name('pages.edit-contacto');
        Route::put('pages/contacto', [App\Http\Controllers\Admin\PageController::class, 'updateContacto'])->name('pages.update-contacto');
        
        // Eliminar imágenes (funciona para todas las páginas)
        Route::delete('pages/{page}/image', [App\Http\Controllers\Admin\PageController::class, 'deleteImage'])->name('pages.delete-image');

        Route::delete('pages/{page}/sections/{section}/images', [App\Http\Controllers\Admin\PageController::class, 'deleteSectionImage'])->name('pages.sections.delete-image');
    });
});

/* ---------- CRUD de productos (solo usuarios logueados) ---------- */
Route::middleware(['auth'])
      ->prefix('admin')
      ->name('admin.')
      ->group(function () {
          Route::resource('products', ProductController::class)->except(['show']);
});
// Rutas del carrito
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/', [CartController::class, 'add'])->name('add');
    Route::patch('/{rowId}', [CartController::class, 'update'])->name('update');
    Route::delete('/{rowId}', [CartController::class, 'remove'])->name('remove');
    Route::delete('/', [CartController::class, 'clear'])->name('clear');
    
    // Rutas AJAX
    Route::get('/count', [CartController::class, 'count'])->name('count');
    Route::get('/info', [CartController::class, 'info'])->name('info');
    Route::post('/check-stock', [CartController::class, 'checkStock'])->name('check-stock');
    
    // Rutas de descuentos
    Route::post('/discount', [CartController::class, 'applyDiscount'])->name('apply-discount');
    Route::delete('/discount', [CartController::class, 'removeDiscount'])->name('remove-discount');
});

Route::prefix('admin/pages')->name('admin.pages.')->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\PageController::class, 'index'])->name('index');
    
    // Rutas que redirigen a secciones
    Route::get('/inicio/edit', [App\Http\Controllers\Admin\PageController::class, 'editInicio'])->name('edit-inicio');
    Route::get('/quienes-somos/edit', [App\Http\Controllers\Admin\PageController::class, 'editQuienesSomos'])->name('edit-quienes-somos');
    Route::get('/servicios/edit', [App\Http\Controllers\Admin\PageController::class, 'editServicios'])->name('edit-servicios');
    Route::get('/contacto/edit', [App\Http\Controllers\Admin\PageController::class, 'editContacto'])->name('edit-contacto');
    
    // NUEVAS RUTAS PARA SECCIONES
    Route::get('/{page}/sections', [App\Http\Controllers\Admin\PageController::class, 'manageSections'])->name('sections');
    // Route::put('/{page}/sections/{section}', [App\Http\Controllers\Admin\PageController::class, 'updateSection'])->name('sections.update');
    Route::delete('/{page}/sections/{section}/images', [App\Http\Controllers\Admin\PageController::class, 'deleteSectionImage'])->name('sections.delete-image');
});


require __DIR__.'/auth.php';
