<?php

use App\Http\Controllers\CalenderController;
use Illuminate\Support\Facades\Route;


Route::get('/', [CalenderController::class, 'index'])->name('home');

Route::prefix('ajax')->group(function () {
  
    Route::post('add_or_update_event', [CalenderController::class, 'updateOrCreateEvent'])->name('ajax.add_or_update_event');
    Route::get('delete_event', [CalenderController::class, 'deleteEvent'])->name('ajax.delete_event');

    Route::get('generate-pdf', [CalenderController::class, 'generatePDF'])->name('ajax.pdf');
   
});