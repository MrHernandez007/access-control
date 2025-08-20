 <?php

use Illuminate\Support\Facades\Route;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

Route::get('/', function () {
    return view('Inicio');
});

Route::get('/Servicios', function () {
    return view('Servicios');
});

Route::get('/Contacto', function () {
    return view('Contacto');
});

Route::get('/Nclientes', function () {
    return view('Nclientes');
});

// routes/web.php

Route::view('/login_admin', 'login.login_admin')->name('login.admin');
Route::view('/login_residente', 'login.login_residente')->name('login.residente');



// Dashboard para administrador y residente
Route::view('/base/residente_dashboard', 'base.residente_dashboard')->name('base.residente');
Route::view('/base/admin_dashboard', 'base.admin_dashboard')->name('base.admin');
Route::view('/base/admin2_dashboard', 'base.admin2_dashboard')->name('base.admin2');

// Layouts base
Route::view('/adminP', 'layout.admins');
Route::view('/residenteP', 'layout.residente');
Route::view('/admin2P', 'layout.admin2');

// Vistas de módulos
Route::view('/administradores', 'administradores.index')->name('administradores.index');
Route::view('/administradores/create', 'administradores.create')->name('administradores.create');
Route::view('/administradores/{id}/edit', 'administradores.edit')->name('administradores.edit');


Route::view('/residentes', 'residentes.index');
Route::view('/residentes/create', 'residentes.create');
Route::view('/residentes/{id}/edit', 'residentes.edit');
Route::view('/residentes/{id}', 'residentes.show');
Route::get('/residentes/editar/{id}', function($id) {
    return view('residentes.edit', ['id' => $id]);
});

Route::view('/visitantes/editVisitantes', 'visitantes.editVisitantes')->name('visitantes.editVisitantes');
Route::view('/visitantes/indexVisitantes', 'visitantes.indexVisitantes')->name('visitantes.indexVisitantes');
Route::view('/visitantes', 'visitantes.index');
Route::view('/visitantes/create', 'visitantes.create');
Route::get('/visitantes/{id}/edit', function($id) {
    return view('visitantes.edit', ['id' => $id]);
});

Route::view('/viviendas', 'viviendas.index');
Route::view('/viviendas/create', 'viviendas.create');
Route::view('/viviendas/{id}/edit', 'viviendas.edit');
Route::view('/viviendas/{id}', 'viviendas.show');

Route::view('/visitas', 'visitas.index');
Route::view('/visitas/create', 'visitas.create');
Route::view('/visitas/{id}/edit', 'visitas.edit');

// Vista para residentes
Route::view('/pagos/indexResidente', 'pagos.indexResidente')->name('pagos.residente');
Route::view('/pagos/indexAdmin', 'pagos.indexAdmin')->name('pagos.admin');
Route::view('/pagos/editarAdmin/{id}', 'pagos.editarAdmin');

// Mostrar formularios de login (solo vistas)
Route::view('/login/admin', 'login.login_admin')->name('login.admin');
Route::view('/login/residente', 'login.login_residente')->name('login.residente');

//admin2
Route::view('/admin2/visitantes2', 'admin2.visitantes2')->name('admin2.visitantes2');
Route::view('/admin2/residentes2', 'admin2.residentes2')->name('admin2.residentes2');
Route::view('/admin2/visitas2', 'admin2.visitas2')->name('admin2.visitas2');
Route::view('/admin2/editarResidentes/{id}', 'admin2.editarResidentes')->name('admin2.editarResidentes');
// Puedes agregar más vistas estáticas aquí si lo necesitas


// use AdminAuthController as GlobalAdminAuthController;
// use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\ResidentesController;
// use App\Http\Controllers\AdministradoresController;
// use App\Http\Controllers\VisitantesController;
// use App\Http\Controllers\ViviendasController;
// use App\Http\Controllers\AdminAuthController;
// use App\Http\Controllers\ResidenteAuthController;
// use App\Http\Controllers\VisitasController;
// use App\Http\Controllers\PagosController;

// /*
// |--------------------------------------------------------------------------
// | Web Routes
// |--------------------------------------------------------------------------
// |
// | Here is where you can register web routes for your application. These
// | routes are loaded by the RouteServiceProvider and all of them will
// | be assigned to the "web" middleware group. Make something great!
// |
// */

// Route::get('/', function () {
//     return view('welcome');
// });

// // Route::middleware(['verificar.jwt', 'autorizar.rol:admin'])->group(function () {
// //     Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
// // });





// // Login para administradores
// Route::get('/login/admin', [AdminAuthController::class, 'showLoginForm'])->name('login.admin');
// Route::post('/login/admin', [AdminAuthController::class, 'login']);

// // Login para residentes
// Route::get('/login/residente', [ResidenteAuthController::class, 'showLoginForm'])->name('login.residente');
// Route::post('/login/residente', [ResidenteAuthController::class, 'login']);

// // Rutas protegidas admin
// Route::middleware(['verificar.jwt', 'autorizar.rol:admin'])->group(function () {
//     Route::get('/base/admin_dashboard', [AdministradoresController::class, 'dashboard'])->name('admin.dashboard');
    
//     // otras rutas admin...

//     Route::get('/viviendas/create', [ViviendasController::class, 'create'])->name('viviendas.create');
//     Route::get('/viviendas/index', [ViviendasController::class, 'index'])->name('viviendas.index');
//     Route::get('/viviendas/show', [ViviendasController::class, 'show'])->name('viviendas.show');
//     Route::post('/viviendas/store', [ViviendasController::class, 'store'])->name('viviendas.store');
//     Route::get('/viviendas/{id}/edit', [ViviendasController::class, 'edit'])->name('viviendas.edit');
//     Route::put('/viviendas/{id}/update', [ViviendasController::class, 'update'])->name('viviendas.update');
//     Route::delete('/viviendas/{id}/destroy', [ViviendasController::class, 'destroy'])->name('viviendas.destroy');

//     Route::get('/administradores/create', [AdministradoresController::class, 'create'])->name('administradores.create');
//     Route::get('/administradores/index', [AdministradoresController::class, 'index'])->name('administradores.index');
//     Route::get('/administradores/show', [AdministradoresController::class, 'show'])->name('administradores.show');
//     Route::post('/administradores/store', [AdministradoresController::class, 'store'])->name('administradores.store');
//     Route::get('/administradores/{id}/edit', [AdministradoresController::class, 'edit'])->name('administradores.edit');
//     Route::put('/administradores/{id}/update', [AdministradoresController::class, 'update'])->name('administradores.update');
//     Route::delete('/administradores/{id}/destroy', [AdministradoresController::class, 'destroy'])->name('administradores.destroy');

//     Route::get('/residentes/create', [ResidentesController::class, 'create'])->name('residentes.create');
//     Route::get('/residentes/index', [ResidentesController::class, 'index'])->name('residentes.index');
//     Route::get('/residentes/show', [ResidentesController::class, 'show'])->name('residentes.show');
//     Route::post('/residentes/store', [ResidentesController::class, 'store'])->name('residentes.store');
//     Route::get('/residentes/{id}/edit', [ResidentesController::class, 'edit'])->name('residentes.edit');
//     Route::put('/residentes/{id}/update', [ResidentesController::class, 'update'])->name('residentes.update');
//     Route::delete('/residentes/{id}/destroy', [ResidentesController::class, 'destroy'])->name('residentes.destroy');

//     Route::get('/pagos/index_admin', [PagosController::class, 'indexAdmin'])->name('pagos.admin');


//     Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
//  });

// Rutas protegidas residentes
//  Route::middleware(['verificar.jwt', 'autorizar.rol:residente'])->group(function () {
//     Route::get('/base/residente_dashboard', [ResidentesController::class, 'dashboard'])->name('residente.dashboard');
//     // otras rutas residentes...

    // Route::get('/visitas', [VisitasController::class, 'index'])->name('visitas.index');
    // Route::get('/visitas/create', [VisitasController::class, 'create'])->name('visitas.create');
    // Route::post('/visitas/store', [VisitasController::class, 'store'])->name('visitas.store');
    // Route::get('/visitas/{id}', [VisitasController::class, 'show'])->name('visitas.show');
    // Route::delete('/visitas/{id}', [VisitasController::class, 'destroy'])->name('visitas.destroy');

//     Route::get('/visitas/create', [VisitasController::class, 'create'])->name('visitas.create');
//     Route::get('/visitas/index', [VisitasController::class, 'index'])->name('visitas.index');
//     Route::get('/visitas/show', [VisitasController::class, 'show'])->name('visitas.show');
//     Route::post('/visitas/store', [VisitasController::class, 'store'])->name('visitas.store');
//     Route::get('/visitas/{id}/edit', [VisitasController::class, 'edit'])->name('visitas.edit');
//     Route::put('/visitas/{id}/update', [VisitasController::class, 'update'])->name('visitas.update');
//     Route::delete('/visitas/{id}/destroy', [VisitasController::class, 'destroy'])->name('visitas.destroy');

//     Route::get('/visitantes/create', [VisitantesController::class, 'create'])->name('visitantes.create');
//     Route::get('/visitantes/index', [VisitantesController::class, 'index'])->name('visitantes.index');
//     Route::get('/visitantes/show', [VisitantesController::class, 'show'])->name('visitantes.show');
//     Route::post('/visitantes/store', [VisitantesController::class, 'store'])->name('visitantes.store');
//     Route::get('/visitantes/{id}/edit', [VisitantesController::class, 'edit'])->name('visitantes.edit');
//     Route::put('/visitantes/{id}/update', [VisitantesController::class, 'update'])->name('visitantes.update');
//     Route::delete('/visitantes/{id}/destroy', [VisitantesController::class, 'destroy'])->name('visitantes.destroy');

//     Route::get('/pagos/index_residente', [PagosController::class, 'indexResidente'])->name('pagos.residente');

//     Route::post('/logout', [ResidenteAuthController::class, 'logout'])->name('logout');
// });




// Route::get('/residentes/create', [ResidentesController::class, 'create'])->name('residentes.create');
// Route::get('/residentes/index', [ResidentesController::class, 'index'])->name('residentes.index');
// Route::get('/residentes/show', [ResidentesController::class, 'show'])->name('residentes.show');
// Route::post('/residentes/store', [ResidentesController::class, 'store'])->name('residentes.store');
// Route::get('/residentes/{id}/edit', [ResidentesController::class, 'edit'])->name('residentes.edit');
// Route::put('/residentes/{id}/update', [ResidentesController::class, 'update'])->name('residentes.update');
// Route::delete('/residentes/{id}/destroy', [ResidentesController::class, 'destroy'])->name('residentes.destroy');

// Route::get('/administradores/create', [AdministradoresController::class, 'create'])->name('administradores.create');
// Route::get('/administradores/index', [AdministradoresController::class, 'index'])->name('administradores.index');
// Route::get('/administradores/show', [AdministradoresController::class, 'show'])->name('administradores.show');
// Route::post('/administradores/store', [AdministradoresController::class, 'store'])->name('administradores.store');
// Route::get('/administradores/{id}/edit', [AdministradoresController::class, 'edit'])->name('administradores.edit');
// Route::put('/administradores/{id}/update', [AdministradoresController::class, 'update'])->name('administradores.update');
// Route::delete('/administradores/{id}/destroy', [AdministradoresController::class, 'destroy'])->name('administradores.destroy');

// Route::get('/viviendas/create', [ViviendasController::class, 'create'])->name('viviendas.create');
// Route::get('/viviendas/index', [ViviendasController::class, 'index'])->name('viviendas.index');
// Route::get('/viviendas/show', [ViviendasController::class, 'show'])->name('viviendas.show');
// Route::post('/viviendas/store', [ViviendasController::class, 'store'])->name('viviendas.store');
// Route::get('/viviendas/{id}/edit', [ViviendasController::class, 'edit'])->name('viviendas.edit');
// Route::put('/viviendas/{id}/update', [ViviendasController::class, 'update'])->name('viviendas.update');
// Route::delete('/viviendas/{id}/destroy', [ViviendasController::class, 'destroy'])->name('viviendas.destroy');

// Route::get('/visitantes/create', [VisitantesController::class, 'create'])->name('visitantes.create');
// Route::get('/visitantes/index', [VisitantesController::class, 'index'])->name('visitantes.index');
// Route::get('/visitantes/show', [VisitantesController::class, 'show'])->name('visitantes.show');
// Route::post('/visitantes/store', [VisitantesController::class, 'store'])->name('visitantes.store');
// Route::get('/visitantes/{id}/edit', [VisitantesController::class, 'edit'])->name('visitantes.edit');
// Route::put('/visitantes/{id}/update', [VisitantesController::class, 'update'])->name('visitantes.update');
// Route::delete('/visitantes/{id}/destroy', [VisitantesController::class, 'destroy'])->name('visitantes.destroy'); -->
Route::view('/adminP', '/layout.admins');

    