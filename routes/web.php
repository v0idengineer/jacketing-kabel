<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    MachineController,
    JacketingController,
    MultiStepFormController,
    StockDieController,
    RecipeController,
    DesignController,
    ProductionHistoryController
};

// Root -> redirect login/dashboard
Route::get(
    '/',
    fn() => auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login')
)->name('root');

// Semua route aplikasi dilindungi auth + force.password.refresh
Route::middleware(['auth', 'force.password.refresh'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [MachineController::class, 'dashboard'])->name('dashboard');

    // History
    Route::get('/history', [ProductionHistoryController::class, 'index'])->name('history');
    Route::get('/history/{id}/report', [ProductionHistoryController::class, 'report'])->name('history.report');

    // Form 3 tahap
    Route::get('/form/{machine}', [MultiStepFormController::class, 'show'])
        ->where('machine', '(?:JC|jc)[- ]?\d+')
        ->name('form.show');

    // Jacketing
    Route::resource('jacketing', JacketingController::class)->except(['store']);
    Route::post('/jacketing', [MultiStepFormController::class, 'store'])->name('jacketing.store');

    // Stock Dies
    Route::get('/stock',               [StockDieController::class, 'index'])->name('stock.index');
    Route::get('/stock/create',        [StockDieController::class, 'create'])->name('stock.create');
    Route::post('/stock',              [StockDieController::class, 'store'])->name('stock.store');
    Route::get('/stock/{stock}/edit',  [StockDieController::class, 'edit'])->name('stock.edit');
    Route::put('/stock/{stock}',       [StockDieController::class, 'update'])->name('stock.update');
    Route::delete('/stock/{stock}',    [StockDieController::class, 'destroy'])->name('stock.destroy');
    Route::get('/stock/{stock}',       [StockDieController::class, 'show'])->name('stock.show');

    // Recipes
    Route::resource('recipes', RecipeController::class);

    // AJAX rekomendasi dies
    Route::get('/dies/recommend', [RecipeController::class, 'recommend'])->name('dies.recommend');

    // Design
    Route::get('/design/render-demo', [DesignController::class, 'renderDemo']);
    Route::get('/design/print/{recipe}', [DesignController::class, 'print'])->name('design.print'); // <— DITAMBAHKAN

    // Signed download untuk report PDF
    Route::get('/reports/download', [MultiStepFormController::class, 'downloadReport'])
        ->middleware('signed')
        ->name('reports.download');


    // require __DIR__.'/profile.php';
});

// Auth routes (login/logout dari Breeze)
require __DIR__ . '/auth.php';
