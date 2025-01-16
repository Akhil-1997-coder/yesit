<?php
use App\Http\Controllers\ProfileController;

Route::get('/', [ProfileController::class, 'index'])->name('profiles.index');
Route::get('/profiles', [ProfileController::class, 'index'])->name('profiles.index');
Route::get('/profiles/create', [ProfileController::class, 'create'])->name('profiles.create');
Route::post('/profiles', [ProfileController::class, 'store'])->name('profiles.store');
Route::get('/profiles/{profile}/edit', [ProfileController::class, 'edit'])->name('profiles.edit');
Route::put('/profiles/{profile}', [ProfileController::class, 'update'])->name('profiles.update');
Route::delete('/profiles/{profile}', [ProfileController::class, 'destroy'])->name('profiles.destroy');
Route::post('/profiles/import', [ProfileController::class, 'import'])->name('profiles.import');
Route::post('/profiles/export', [ProfileController::class, 'export'])->name('profiles.export');
