<?php

use Illuminate\Support\Facades\Route;

//Oauth Controllers
use App\Http\Controllers\Auth\SocialLoginController;

//User Controllers
use App\Http\Controllers\ContactController;
use App\Http\Controllers\SubscriptionsController;
use App\Http\Controllers\CareersController;
use App\Http\Controllers\BrandsController;
use App\Http\Controllers\CatalogeController;
use App\Http\Controllers\HomeContoller;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SparePartController;
use App\Http\Controllers\SpecificationController;
use App\Http\Controllers\VehicleModelController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\DonateController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\VolunteerController;

//Admin Controllers
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CareersController as Careers;
use App\Http\Controllers\Admin\CauseController;
use App\Http\Controllers\Admin\BloggersController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\EventController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\Admin\StoryController;
use App\Http\Controllers\Admin\WebpagesController;
use App\Http\Controllers\Admin\ApplicationsController;
use App\Http\Controllers\Admin\BodyTypeController;
use App\Http\Controllers\Admin\OrganizationController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DriveTypeController;
use App\Http\Controllers\Admin\EngineTypeController;
use App\Http\Controllers\Admin\ModelController;
use App\Http\Controllers\Admin\PartBrandController;
use App\Http\Controllers\Admin\PartController;
use App\Http\Controllers\Admin\PartFitmentController;
use App\Http\Controllers\Admin\TransmissionTypeController;
use App\Http\Controllers\Admin\VariantController;
use App\Http\Controllers\Admin\VariantSpecificationController;

//Guest routes
// Route::get('/', function() {
//   return view('under-maintainence');
// });

//User Routes
Route::get('/', [HomeContoller::class, 'index']);
Route::get('/about', [PageController::class, 'about']);
Route::get('/gallery', [PageController::class, 'gallery']);
Route::get('/blogs', [PageController::class, 'blogs']);
Route::get('/news', [PageController::class, 'news']);
Route::get('/news/{id}', [PageController::class, 'news_details']);
Route::get('/articles', [PageController::class, 'articles']);
Route::get('/articles/{id}', [PageController::class, 'article']);
Route::get('/policies', [PageController::class, 'policies']);
Route::get('/brands', [BrandsController::class, 'brands']);
Route::get('/shop/products', [ProductController::class, 'products']);
Route::get('/shop/products/{id}', [ProductController::class, 'product']);
Route::get('/terms-and-conditions', [PageController::class, 'terms_and_conditions']);
Route::get('/faqs', [PageController::class, 'faqs']);
Route::get('/cart', [PageController::class, 'cart']);
Route::get('/cart', [PageController::class, 'cart']);
Route::get('/blog/{id}', [PageController::class, 'blog']);
Route::get('/blogs/{id}', [PageController::class, 'blog_category']);
Route::get('/projects', [PageController::class, 'projects']);
Route::get('/cataloge/{id}', [CatalogeController::class, 'cataloge']);
Route::get('/stories', [PageController::class, 'stories']);
Route::get('/story/{id}', [PageController::class, 'story']);
Route::get('/causes', [PageController::class, 'causes']);
Route::get('/donate', [PageController::class, 'donate']);
Route::get('/volunteer', [PageController::class, 'volunteer']);
Route::get('/events', [PageController::class, 'events']);
Route::get('/events/{event}', [PageController::class, 'event']);
Route::get('/application-sent', [PageController::class, 'application_sent']);
Route::get('blogs/search/{keyword}', [PageController::class, 'search']);
Route::resource('contact', ContactController::class);
Route::resource('subscribe', SubscriptionsController::class);
Route::get('/volunteer', [VolunteerController::class, 'index'])->name('volunteer');
Route::post('/volunteer', [VolunteerController::class, 'store']);
Route::get('/donate', [DonateController::class, 'index']);
Route::post('/donate', [DonateController::class, 'store'])->name('donation.store');
Route::get('/career', [CareersController::class, 'index']);
Route::get('/job-details/{id}', [CareersController::class, 'jobDetails']);
Route::get('/apply/{id}', [CareersController::class, 'apply'])->name('apply');
Route::post('/apply', [CareersController::class, 'store']);
Route::resource('careers', Careers::class);
Route::resource('applications', ApplicationsController::class);
Route::get('/export-excel', [ExportsController::class, 'exportAll']);
Route::get('/export-excel/{id}', [ExportsController::class, 'exportSelected']);
Route::get('/models', [VehicleModelController::class, 'index']);
Route::get('/models/{id}', [VehicleModelController::class, 'vehicle_model']);
Route::get('/model-specification/{id}', [SpecificationController::class, 'model_specification']);
Route::get('/variant-specification/{id}', [SpecificationController::class, 'variant_specification']);
Route::get('/spare-parts/{id}', [SparePartController::class, 'parts']);

// Social login routes
Route::get('/auth/redirect/{provider}', [SocialLoginController::class, 'redirect']);
Route::get('/auth/callback/{provider}', [SocialLoginController::class, 'callback']);

//Authenticated user routes
Route::middleware(['auth', 'verified', 'role:user'])->group(function () { 
    Route::get('/home', [PageController::class, 'index'])->name('home');
    Route::post('/comment', [PageController::class, 'post']);
    Route::post('/deleteComment/{id}', [PageController::class, 'deleteComment']);
});

//Admin and super admin Routes
Route::middleware(['auth', 'role:admin|super-admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/admin/dashboard', fn() => 'Admin Dashboard')->name('admin.dashboard');

    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    Route::post('/add-task', [AdminController::class, 'addTask'])->name('addTask');
    Route::post('/task-done/{id}', [AdminController::class, 'taskDone'])->name('taskDone');
    Route::resource('pages', WebpagesController::class);
    Route::resource('causes', CauseController::class);
    Route::resource('stories', StoryController::class);
    Route::resource('bloggers', BloggersController::class);
    Route::resource('blogs', BlogController::class);
    Route::resource('gallery', GalleryController::class);
    Route::resource('projects', ProjectController::class);
    Route::resource('team', TeamController::class);
    Route::resource('careers', Careers::class);
    Route::resource('applications', ApplicationsController::class);
    Route::resource('specification', VariantSpecificationController::class);
    Route::post('applications/shortlist', [ApplicationsController::class, 'shortlist']);
    Route::post('/applications/export-all', [ApplicationsController::class, 'exportAll']);
    Route::get('/applications/export-selected', [ApplicationsController::class, 'exportSelected']);
    Route::get("/letmesee/{id}", [ApplicationsController::class, 'exportSelected']);
    Route::get('applications/filter/{id}', [ApplicationsController::class, 'filter']);
    Route::get('applications/search/{keyword}', [ApplicationsController::class, 'search']);
    Route::get('/downloadfiles', [ApplicationsController::class, 'downloadfiles']);
    Route::post('applications/hire', [ApplicationsController::class, 'hire']);
    Route::post('applications/reject', [ApplicationsController::class, 'reject']);
    Route::resource('events', EventController::class);
    Route::resource('users', UsersController::class);
    Route::resource('partners', PartnerController::class);
    Route::resource('organization', OrganizationController::class);
    //////////////////////////////////

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
});

//Testing routes
Route::get('/test-super-admin', function () {
    return 'You are super-admin!';
})->middleware(['auth', 'role:super-admin']);

Route::get('/test-admin', function () {
    return 'You are admin!';
})->middleware(['auth', 'role:admin']);

Route::get('/checkifemailisverified', function () {
    return "You have verified";
})->middleware(['verified']);

