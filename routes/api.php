    <?php

    use Illuminate\Foundation\Application;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BukuController;
    use App\Http\Controllers\KategoriController;

    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        
        Route::apiResource('buku', BukuController::class);
        Route::apiResource('kategori', KategoriController::class);
    });