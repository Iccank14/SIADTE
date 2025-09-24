<?php

use App\Http\Controllers\ArchiveController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('archives.index');
});

Route::resource('archives', ArchiveController::class);
Route::get('/archives/{archive}/download', [ArchiveController::class, 'download'])
    ->name('archives.download');