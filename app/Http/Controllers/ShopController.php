<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Price;

use Illuminate\Support\Facades\Log;

use Square\SquareClient;
use Square\Payments\Requests\CreatePaymentRequest;
use Square\Types\Money;
use Square\Types\Currency;



class ShopController extends Controller
{

    public function index(Request $request) 
    {
        $query = Product::vigentes()->with(['images', 'category', 'extra']);
        
        // 🔹 Filtro por categoría (por ID como espera la vista)
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        
        // 🔹 Filtro por país (en la categoría)
        if ($request->filled('country')) {
            $country = trim($request->country);
            $query->whereHas('category', function ($q) use ($country) {
                $q->where('country', $country);
            });
        }

        // filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // filtro por tipo lista
        if ($request->filled('tipo_listado')) {
            $query->where('tipo_listado', $request->tipo_listado);
        }

        // 🔹 Filtro por rango de precios
        if ($request->filled('price_min')) {
            $query->whereRaw('(COALESCE(price, 0) + COALESCE(interest, 0)) >= ?', [$request->price_min]);
        }
        if ($request->filled('price_max')) {
            $query->whereRaw('(COALESCE(price, 0) + COALESCE(interest, 0)) <= ?', [$request->price_max]);
        }

        // 🔹 Filtros por extra
        $query->whereHas('extra', function ($q) use ($request) {
            if ($request->filled('ubicacion')) {
                $q->where('ubicacion', 'LIKE', '%' . $request->ubicacion . '%');
            }
            if ($request->filled('raza')) {
                $q->where('raza', 'LIKE', '%' . $request->raza . '%');
            }
            if ($request->filled('edad')) {
                $q->where('edad', 'LIKE', '%' . $request->edad . '%');
            }
            if ($request->filled('genero')) {
                $q->where('genero', $request->genero);
            }
            if ($request->filled('pedigri')) {
                $q->where('pedigri', $request->pedigri);
            }
            if ($request->filled('entrenamiento')) {
                $q->where('entrenamiento', 'LIKE', '%' . $request->entrenamiento . '%');
            }
            if ($request->filled('historial_salud')) {
                $q->where('historial_salud', 'LIKE', '%' . $request->historial_salud . '%');
            }
        });

        // 🔹 Búsqueda general (en nombre y descripción del producto)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }
        
        // 🔹 Ordenamiento
        switch ($request->get('sort')) {
            case 'price_low':  
                $query->orderByRaw('(COALESCE(price, 0) + COALESCE(interest, 0)) ASC'); 
                break;
            case 'price_high': 
                $query->orderByRaw('(COALESCE(price, 0) + COALESCE(interest, 0)) DESC'); 
                break;
            case 'name':       
                $query->orderBy('name', 'asc');   
                break;
            case 'newest':     
                $query->latest();                 
                break;
            default:           
                $query->latest();                 
                break;
        }
        
        // 🔹 Paginar productos
        $products = $query->paginate(12)->appends($request->query());

        $favoritesIds = [];
        if (auth()->check()) {
            $favoritesIds = auth()->user()
                ->favoriteProducts()
                ->pluck('products.id')
                ->all();
        }
        
        // 🔹 Obtener categorías completas (como espera la vista)
        $categories = \App\Models\Category::query()
            ->withCount('products')
            ->orderBy('name')
            ->get();
        
        // 🔹 Obtener países disponibles
        $countries = \App\Models\Category::query()
            ->select('country')
            ->whereNotNull('country')
            ->where('country', '!=', '')
            ->distinct()
            ->orderBy('country')
            ->pluck('country');
        
        // Detectar si hay filtros aplicados
        $hasFilters = $request->hasAny([
            'category', 'country', 'search', 'sort',
            'price_min', 'price_max',
            'ubicacion', 'raza', 'edad', 'genero', 'pedigri', 'entrenamiento', 'historial_salud'
        ]);
        
        return view('shop.index', compact('products', 'categories', 'countries', 'hasFilters', 'favoritesIds'));
    }





        public function checkout() 
        {
            // Verificar que el carrito no esté vacío
            if (Cart::count() == 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty. Add some products before checkout.');
            }

            // Obtener información del carrito
            $cartItems = Cart::content();
            $cartSubtotal = Cart::subtotal(2, '', ''); // Sin formato
            
            // Verificar si el usuario está autenticado
            $user = Auth::user();
            
            // 🔴 Obtener países y ciudades para el envío
            $countries = Country::with('cities')->orderBy('name')->get();
            
            // Datos para la vista
            $checkoutData = [
                'cartItems' => $cartItems,
                'subtotal' => floatval(str_replace(',', '', $cartSubtotal)), // Convertir a número
                'countries' => $countries, // 🔴 Agregar países
                'isAuthenticated' => $user ? true : false,
                'user' => $user
            ];

            return view('shop.checkout', $checkoutData);
        }

        // 🔴 Nuevo método para calcular costos dinámicamente
        public function calculateShippingAndTax(Request $request)
        {
            $countryId = $request->country_id;
            $cityId = $request->city_id;
            
            $cartItems = Cart::content();
            $subtotal = floatval(str_replace(',', '', Cart::subtotal(2, '', '')));
            
            $totalTax = 0;
            $shipping = 0;
            $shippingApplied = false;
            
            foreach ($cartItems as $item) {
                // Buscar configuración de precio para este producto y ubicación
                $priceConfig = \App\Models\Price::where('product_id', $item->id)
                    ->where('country_id', $countryId)
                    ->when($cityId, function($query) use ($cityId) {
                        return $query->where('city_id', $cityId);
                    })
                    ->first();
                
                if ($priceConfig) {
                    // Calcular impuesto por producto (interest como porcentaje)
                    $productTax = ($item->price * $item->qty * $priceConfig->interest) / 100;
                    $totalTax += $productTax;
                    
                    // Shipping se cobra solo una vez
                    if (!$shippingApplied) {
                        $shipping = $priceConfig->shipping;
                        $shippingApplied = true;
                    }
                }
            }
            
            $total = $subtotal + $totalTax + $shipping;
            
            return response()->json([
                'subtotal' => number_format($subtotal, 2),
                'tax' => number_format($totalTax, 2),
                'shipping' => number_format($shipping, 2),
                'total' => number_format($total, 2),
                'tax_raw' => $totalTax,
                'shipping_raw' => $shipping,
                'total_raw' => $total
            ]);
        }



        // En tu controlador (ShopController o donde tengas checkout)

        public function processOrder(Request $request)
        {
            // Validación
            $rules = [
                'country_id' => 'required|exists:countries,id',
                'city_id' => 'nullable|exists:cities,id',
                'total' => 'required|numeric',
                'tax' => 'required|numeric',
                'shipping' => 'required|numeric',
                'phone' => 'required|string',
                'address' => 'required|string',
                'notes' => 'nullable|string',
            ];

            // Validación adicional para guests
            if (!Auth::check()) {
                $rules['name'] = 'required|string|max:255';
                $rules['email'] = 'required|email|max:255';
            }

            $validatedData = $request->validate($rules);

            // Verificar que el carrito no esté vacío
            if (Cart::count() == 0) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }

            DB::beginTransaction();
            
            try {
                // Datos del cliente
                $user = Auth::user();
                $customerData = [
                    'customer_name' => $user ? $user->name : $validatedData['name'],
                    'customer_email' => $user ? $user->email : $validatedData['email'],
                    'customer_phone' => $validatedData['phone'],
                    'customer_address' => $validatedData['address'],
                ];

                // Crear la orden
                $order = Order::create([
                    'order_number' => Order::generateOrderNumber(),
                    'user_id' => $user ? $user->id : null, // 🔴 NULL para guests
                    ...$customerData,
                    'country_id' => $validatedData['country_id'],
                    'city_id' => $validatedData['city_id'],
                    'subtotal' => floatval(str_replace(',', '', Cart::subtotal(2, '', ''))),
                    'tax_amount' => $validatedData['tax'],
                    'shipping_amount' => $validatedData['shipping'],
                    'total_amount' => $validatedData['total'],
                    'notes' => $validatedData['notes'],
                    'status' => 'pending',
                    'payment_status' => 'pending',
                ]);

                // Crear items de la orden
                foreach (Cart::content() as $cartItem) {
                    // Calcular impuesto para este item específico
                    $priceConfig = Price::where('product_id', $cartItem->id)
                        ->where('country_id', $validatedData['country_id'])
                        ->when($validatedData['city_id'], function($query) use ($validatedData) {
                            return $query->where('city_id', $validatedData['city_id']);
                        })
                        ->first();

                    $taxRate = $priceConfig ? $priceConfig->interest : 0;
                    $itemTaxAmount = ($cartItem->price * $cartItem->qty * $taxRate) / 100;

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->id,
                        'product_name' => $cartItem->name,
                        'product_price' => $cartItem->price,
                        'quantity' => $cartItem->qty,
                        'total_price' => $cartItem->total,
                        'tax_rate' => $taxRate,
                        'tax_amount' => $itemTaxAmount,
                    ]);
                }

                DB::commit();

                // Limpiar carrito
                Cart::destroy();

                // Redirigir a pasarela de pago
                return redirect()->route('payment.gateway', ['order' => $order->id])
                                ->with('success', 'Order created successfully! Order #' . $order->order_number);

            } catch (\Exception $e) {
                DB::rollback();
                return back()->with('error', 'Error creating order: ' . $e->getMessage());
            }
        }

 public function paymentGateway(Order $order)
{
    // Verificar que la orden exista y esté pendiente
    if ($order->payment_status !== 'pending') {
        return redirect()->route('shop.index')->with('error', 'Order not found or already processed.');
    }

    return view('payment.gateway', compact('order'));
}
public function processPayment(Request $request, Order $order) 
{     
    // Verificar que la orden esté pendiente     
    if ($order->payment_status !== 'pending') {         
        return redirect()->route('shop.index')->with('error', 'Order already processed.');     
    }      

    try {         
        // Validar datos del pago         
        $request->validate([
            'token' => 'required|string', // Token del card_token de MercadoPago
            'payment_method_id' => 'required|string', // visa, master, etc.
            'installments' => 'required|integer|min:1',
            'issuer_id' => 'nullable|string',
            'payer_email' => 'required|email'
        ]);          

        \Log::info('Processing MercadoPago payment for order', [             
            'order_id' => $order->id,             
            'amount' => $order->total_amount,             
            'token' => $request->token         
        ]);          

        // Crear pago usando MercadoPago API         
        $paymentData = [             
            'token' => $request->token,
            'issuer_id' => $request->issuer_id,
            'payment_method_id' => $request->payment_method_id,
            'transaction_amount' => (float) $order->total_amount,
            'installments' => (int) $request->installments,
            'description' => 'Order #' . $order->order_number,
            'external_reference' => 'order_' . $order->id,
            'payer' => [
                'email' => $request->payer_email,
                'first_name' => $order->customer_name ?? 'Customer',
                'last_name' => '', 
                'identification' => [
                    'type' => 'DNI', // o CC, CE, etc según tu país
                    'number' => '00000000' // Deberías pedirlo en el form
                ]
            ],
            'statement_descriptor' => 'TU_TIENDA', // Aparece en el resumen de tarjeta
            'notification_url' => route('mercadopago.webhook'), // Para webhooks
            'metadata' => [
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]
        ];          

        // Determinar URL según ambiente
        $baseUrl = env('MERCADOPAGO_ENV', 'sandbox') === 'production' 
            ? 'https://api.mercadopago.com' 
            : 'https://api.mercadopago.com';

        $ch = curl_init();         
        curl_setopt($ch, CURLOPT_URL, $baseUrl . '/v1/payments');         
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);         
        curl_setopt($ch, CURLOPT_POST, true);         
        curl_setopt($ch, CURLOPT_HTTPHEADER, [             
            'Authorization: Bearer ' . env('MERCADOPAGO_ACCESS_TOKEN'),             
            'Content-Type: application/json',
            'X-Idempotency-Key: order_' . $order->id . '_' . time()
        ]);         
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($paymentData));          

        $response = curl_exec($ch);         
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);         
        curl_close($ch);          

        \Log::info('MercadoPago API Response', [
            'http_code' => $httpCode,
            'response' => $response
        ]);

        if ($httpCode !== 200 && $httpCode !== 201) {             
            $errorData = json_decode($response, true);             
            $errorMessage = 'Payment processing failed';
            
            if (isset($errorData['message'])) {
                $errorMessage = $errorData['message'];
            } elseif (isset($errorData['cause'][0]['description'])) {
                $errorMessage = $errorData['cause'][0]['description'];
            }
                                      
            \Log::error('MercadoPago payment failed', [                 
                'http_code' => $httpCode,                 
                'response' => $response             
            ]);                          

            return back()->with('error', 'Payment failed: ' . $errorMessage);         
        }          

        // Procesar respuesta exitosa         
        $paymentResult = json_decode($response, true);         
        $transactionId = $paymentResult['id'];
        $status = $paymentResult['status'];
        $statusDetail = $paymentResult['status_detail'];

        // MercadoPago tiene diferentes estados
        if ($status === 'approved') {
            $order->update([                 
                'payment_status' => 'paid',                 
                'status' => 'confirmed',                  
                'payment_method' => 'mercadopago',                 
                'transaction_id' => $transactionId,                 
                'paid_at' => now()             
            ]);              

            \Log::info('MercadoPago payment successful', [                 
                'order_id' => $order->id,                 
                'transaction_id' => $transactionId             
            ]);                      

            return redirect()->route('payment.success', $order)                 
                ->with('success', 'Payment processed successfully!');
        } elseif ($status === 'pending') {
            $order->update([                 
                'payment_status' => 'pending',                 
                'status' => 'pending_payment',                  
                'payment_method' => 'mercadopago',                 
                'transaction_id' => $transactionId
            ]);

            return redirect()->route('payment.pending', $order)                 
                ->with('info', 'Payment is being processed. You will receive confirmation soon.');
        } else {
            // rejected, cancelled, etc.
            \Log::warning('MercadoPago payment not approved', [                 
                'order_id' => $order->id,                 
                'status' => $status,
                'status_detail' => $statusDetail
            ]);

            return back()->with('error', 'Payment was not approved: ' . $statusDetail);
        }

    } catch (\Exception $e) {         
        \Log::error('MercadoPago payment processing error: ' . $e->getMessage());         
        return back()->with('error', 'Payment processing failed. Please try again.');
    }
}
public function paymentSuccess(Order $order) 
{
    // Verificar que el pago haya sido exitoso
    if ($order->payment_status !== 'paid') {
        return redirect()->route('shop.index')->with('error', 'Order not found or payment not completed.');
    }

    // NO cargar relaciones por ahora - usar datos simples
    \Log::info('Payment success for order', [
        'order_id' => $order->id,
        'transaction_id' => $order->transaction_id
    ]);

    return view('payment.success', compact('order'));
}      
}
