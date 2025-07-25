    <?php

    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BukuController;
    use App\Http\Controllers\KategoriController;
    use App\Http\Controllers\PenerbitController;
    use App\Http\Controllers\PeminjamanController;

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::apiResource('buku', BukuController::class);
        Route::apiResource('kategori', KategoriController::class);
        route::apiResource('penerbit', PenerbitController::class);
        Route::apiResource('peminjaman', PeminjamanController::class);
        
    });