<?php

use Illuminate\Support\Facades\Route;

// --- 1. OAUTH & AUTH CONTROLLERS ---
use App\Http\Controllers\Auth\SocialLoginController;

// --- 2. USER/PUBLIC CONTROLLERS ---
use App\Http\Controllers\HomeContoller;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CatalogeController;
use App\Http\Controllers\ExportsController;

// --- 3. E-COMMERCE & PARTS CONTROLLERS ---
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PartCatalogController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\ModelPartController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\VinController;
use App\Http\Controllers\PaymentController as FlutterwavePaymentController;

// --- 4. SUPPORT & DASHBOARD CONTROLLERS ---
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserDashboardController;

// --- 5. ADMIN CONTROLLERS ---
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AddressController;
use App\Http\Controllers\Admin\CareersController as Careers;
use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\BlogCategoryController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\WebpagesController;
use App\Http\Controllers\Admin\BodyTypeController;
use App\Http\Controllers\Admin\BroadcastController;
use App\Http\Controllers\Admin\CartController as AdminCartController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DriveTypeController;
use App\Http\Controllers\Admin\EngineTypeController;
use App\Http\Controllers\Admin\InboxController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\NewsController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PartBrandController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\PartFitmentController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ReportsController;
use App\Http\Controllers\Admin\ShippingController;
use App\Http\Controllers\Admin\TransmissionTypeController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\SpecificationController as AdminSpecificationController;
use App\Http\Controllers\Admin\SystemSettingsController;
use App\Http\Controllers\Admin\TicketController as Ticket;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\Shop\DashboardController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\Part;
use App\Http\Controllers\Shop\PartController as ShopPartController;
use App\Http\Controllers\Shop\PayoutController;
use App\Http\Controllers\Shop\SaleController;
use App\Http\Controllers\Shop\ShopProfileController;
use App\Http\Controllers\Shop\TicketController as ShopTicketController;

// =============================================================
// PUBLIC FRONTEND ROUTES
// =============================================================

Route::get('/', [HomeContoller::class, 'index']);

Route::controller(PageController::class)->group(function () {
    Route::get('/about', 'about')->name('about');
    
    // --- BLOGS & ARTICLES ---
    Route::get('/blogs', 'blogs')->name('blogs.index');
    // Using {slug} is better for SEO as we discussed
    Route::get('/articles/{slug}', 'article')->name('blogs.show'); 
    Route::get('/blogs/category/{id}', 'blog_category')->name('blogs.category');
    Route::get('/blogs/search/{keyword}', 'search')->name('blogs.search');
    
    // --- NEWS ---
    Route::get('/news', 'news')->name('news.index');
    Route::get('/news/{slug}', 'news_details')->name('news.show');
    
    // --- FORMS & ACTIONS ---
    Route::get('/application-sent', 'application_sent')->name('application.sent');
    Route::post('/comment', 'post')->name('comment.store');
    Route::delete('/comment/{id}', 'deleteComment')->name('comment.delete');

    // --- LEGAL & E-COMMERCE ---
    Route::get('/policies', 'policies')->name('policies');
    Route::get('/terms-and-conditions', 'terms_and_conditions')->name('terms');
    Route::get('/faqs', 'faqs')->name('faqs');
    Route::get('/cart', 'cart')->name('cart');
});

Route::get('/brands', [BrandsController::class, 'brands']);
Route::get('/cataloge/{id}', [CatalogeController::class, 'cataloge']);

// --- Forms & Applications ---
Route::resource('contact', ContactController::class);
Route::resource('subscribe', SubscriptionsController::class);

Route::controller(CareersController::class)->group(function () {
    Route::get('/career', 'index');
    Route::get('/job-details/{id}', 'jobDetails');
    Route::get('/apply/{id}', 'apply')->name('apply');
    Route::post('/apply', 'store');
});

// =============================================================
// E-COMMERCE & SPARE PARTS ROUTES
// =============================================================

Route::get('/shop/products', [ProductController::class, 'products']);

Route::get('/models', [VehicleModelController::class, 'index']);
Route::get('/models/{id}', [VehicleModelController::class, 'vehicle_model']);
Route::get('/vin-search', [VinController::class, 'search']);

Route::controller(PartCatalogController::class)->group(function () {
    Route::get('/spare-parts', 'parts')->name('spare-parts.index');
    Route::get('/spare-parts/p/{part:sku}', 'show')->name('spare-parts.show');
    Route::get('/catalog/{brand}/{model}/{slug}', 'part_for_specification')->name('specification.parts');
});

// Route::get('/parts-catalog/{brand?}/{model?}/{variant?}', [SparePartController::class, 'catalog'])->name('parts.catalog');
// Route::get('/spare-parts/{id}', [SparePartController::class, 'parts']);

Route::get('/model/{brand}', [BrandController::class, 'show'])->name('brand.models');

Route::controller(ModelPartController::class)->group(function () {
    Route::get('/models/{model_id}/parts', 'model_parts')->name('model.parts');
    Route::get('/variants/{variant_id}/parts', 'variant_parts')->name('variant.parts');
});

Route::controller(SpecificationController::class)->group(function () {
    Route::get('/variant/{variant:slug}/specifications', 'show')->name('variant.specifications');
    Route::get('/model-specification/{model}', 'model_specification')->name('model.specification');
    Route::get('/variant-specification/{variant}', 'variant_specification')->name('variant.specification');
});

// --- Checkout & Cart ---
Route::resource('wishlist', WishlistController::class);
Route::resource('cart', CartController::class);
// Route::resource('shop', ShopController::class);
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::resource('orders', OrderController::class)->middleware('auth');

// =============================================================
// PAYMENT GATEWAY (FLUTTERWAVE)
// =============================================================

Route::controller(FlutterwavePaymentController::class)->group(function () {
    Route::get('/payment/process/{order}', 'process')->name('payment.process');
    Route::post('/payment/initialize', 'initialize')->name('payment.initialize');
    Route::get('/payment/callback', 'callback')->name('payment.callback');
    Route::get('/payment/receipt/{id}', 'downloadReceipt')->name('payment.receipt');
    Route::post('/flw-webhook', 'webhook')->name('payment.webhook');
});

// =============================================================
// AUTHENTICATED USER ROUTES
// =============================================================

Route::get('/auth/redirect/{provider}', [SocialLoginController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialLoginController::class, 'callback']);

Route::middleware(['auth'])->group(function () {
    
    // Dashboard & Profile
    Route::controller(UserDashboardController::class)->group(function () {
        Route::get('/user-dashboard', 'index')->name('user.dashboard');
        Route::get('/profile/edit', 'editProfile')->name('profile.edit');
        Route::patch('/profile/update', 'updateProfile')->name('profile.update');
        Route::put('/profile/password', 'updatePassword')->name('profile.password');
        Route::post('/garage/update', 'updateGarage')->name('garage.update');
        Route::post('/notifications/read-all', 'markAllRead')->name('notifications.readAll');
        Route::post('/notifications/read', function () {
            auth()->user()->unreadNotifications->markAsRead();
            return back();
        })->name('notifications.read');
    });

    // Support Ticket System
    Route::prefix('tickets')->name('tickets.')->controller(TicketController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/reply', 'reply')->name('reply');
    });

    Route::post('/like/toggle', [LikeController::class, 'toggle'])->name('like.toggle');

    // Specific Role Check
    Route::middleware(['verified', 'role:user'])->group(function () { 
        Route::get('/home', [HomeContoller::class, 'index'])->name('home');
    });
});

Route::middleware(['auth', 'role:seller'])->prefix('shop')->name('shop.')->group(function () {
   Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
   Route::resource('/parts', ShopPartController::class);
   Route::resource('/orders', ShopOrderController::class);

    Route::prefix('profile')->name('profile.')->group(function () {
          Route::get('/profile', [ShopProfileController::class, 'edit'])->name('edit');
          Route::put('/profile', [ShopProfileController::class, 'update'])->name('update');
    });

    Route::prefix('support')->name('support.')->group(function () {
    Route::get('/', [ShopTicketController::class, 'index'])->name('index');
    Route::get('/create', [TicketController::class, 'create'])->name('create');
    Route::post('/', [TicketController::class, 'store'])->name('store');
    Route::get('/{ticket}', [TicketController::class, 'show'])->name('show');
    Route::post('/{ticket}/reply', [TicketController::class, 'reply'])->name('reply');
    });

   Route::prefix('sales')->name('sales.')->group(function () {
        // Main sales history list
        Route::get('/', [SaleController::class, 'index'])->name('index');
        
        // Financial charts and data trends
        Route::get('/analytics', [SaleController::class, 'analytics'])->name('analytics');
        
        // Printable invoice view
        Route::get('/{id}/invoice', [SaleController::class, 'printInvoice'])->name('invoice');
        
        // Quick action to finalize a sale
        Route::post('/{id}/finalize', [SaleController::class, 'finalize'])->name('finalize');
    });

    // Payout & Earnings Routes
    Route::prefix('payouts')->name('payouts.')->group(function () {
        Route::get('/', [PayoutController::class, 'index'])->name('index');
        Route::post('/request', [PayoutController::class, 'store'])->name('store');
        // Optional: Route to view specific payout details
        Route::get('/{id}', [PayoutController::class, 'show'])->name('show');
    });

});

// =============================================================
// ADMIN & SUPER-ADMIN ROUTES
// =============================================================

Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
    
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/add-task', [AdminController::class, 'addTask'])->name('addTask');
    Route::post('/task-done/{id}', [AdminController::class, 'taskDone'])->name('taskDone');

    // Content Management
    Route::resource('pages', WebpagesController::class);
    Route::resource('blog-categories', BlogCategoryController::class);
    // Articles / Blogs
    Route::resource('blogs', BlogController::class);
    // News & Updates
    Route::resource('news', NewsController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('organization', OrganizationController::class);
    Route::resource('users', UsersController::class);

    // HR & Applications
    Route::resource('careers', Careers::class);
    Route::resource('applications', ApplicationsController::class);
    Route::controller(ApplicationsController::class)->group(function() {
        Route::post('applications/shortlist', 'shortlist');
        Route::post('/applications/export-all', 'exportAll');
        Route::get('/applications/export-selected', 'exportSelected');
        Route::get('applications/filter/{id}', 'filter');
        Route::get('applications/search/{keyword}', 'search');
        Route::get('/downloadfiles', 'downloadfiles');
        Route::post('applications/hire', 'hire');
        Route::post('applications/reject', 'reject');
    });

    // E-commerce Management
    Route::resource('carts', AdminCartController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('shippings', ShippingController::class);
    Route::resource('addresses', AddressController::class);
    Route::resource('specifications', AdminSpecificationController::class);
    Route::resource('vehicle-brands', VehicleBrandController::class);
    Route::resource('part-brands', PartBrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('body-types', BodyTypeController::class);
    Route::resource('engine-types', EngineTypeController::class);
    Route::resource('transmission-types', TransmissionTypeController::class);
    Route::resource('drive-types', DriveTypeController::class);
    Route::resource('vehicle-models', ModelController::class);
    Route::resource('variants', VariantController::class);
    Route::resource('spare-parts', PartController::class);
    Route::resource('fitments', PartFitmentController::class);
    Route::delete('fitments/photos/{id}', [PartFitmentController::class, 'deletePhoto'])->name('fitments.deletePhoto');

    // Broadcasts
    Route::controller(BroadcastController::class)->group(function() {
        Route::get('/broadcast', 'index')->name('broadcast.index');
        Route::post('/broadcast', 'send')->name('broadcast.send');
        Route::get('/broadcast/{broadcast}', 'show')->name('broadcast.show');
        Route::delete('/broadcast/clear-all', 'clearAll')->name('broadcast.clearAll');
        Route::delete('/broadcast/{broadcast}', 'destroy')->name('broadcast.destroy');
    });

    // Tickets (Admin)
    Route::controller(Ticket::class)->group(function() {
        Route::get('/tickets', 'index')->name('tickets.index');
        Route::get('/tickets/{ticket}', 'show')->name('tickets.show');
        Route::patch('/tickets/{ticket}/status', 'updateStatus')->name('tickets.status');
        Route::post('/tickets/{ticket}/reply', 'storeReply')->name('tickets.reply');
    });

    // Mailbox
    Route::prefix('mailbox')->controller(InboxController::class)->group(function () {
        Route::get('/inbox', 'index')->name('mailbox.index');
        Route::get('/read/{id}', 'show')->name('mailbox.read');
        Route::patch('/status/{id}', 'updateStatus')->name('mailbox.status');
        Route::delete('/delete/{id}', 'destroy')->name('mailbox.delete');
    });

    // Reports & Exports
    Route::get('/export-excel', [ExportsController::class, 'exportAll']);
    Route::get('/export-excel/{id}', [ExportsController::class, 'exportSelected']);
    Route::get('/export/excel', [PartController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [PartController::class, 'exportPdf'])->name('export.pdf');

    Route::prefix('reports')->controller(ReportsController::class)->group(function () {
        Route::get('/sales', 'sales')->name('reports.sales');
        Route::get('/inventory', 'inventory')->name('reports.inventory');
        Route::get('/sales/pdf', 'downloadSalesPDF')->name('reports.sales.pdf');
        Route::get('/inventory/pdf', 'downloadInventoryPDF')->name('reports.inventory.pdf');
    });

    // System Settings
    Route::get('/settings', [SystemSettingsController::class, 'index'])->name('settings');
    Route::post('/settings', [SystemSettingsController::class, 'update'])->name('settings.update');
});