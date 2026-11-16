<?php

declare(strict_types=1);

use Bjnstnkvc\FormComponents\Http\Controllers\StylesheetController;
use Illuminate\Support\Facades\Route;

Route::get('form-components/{file}', StylesheetController::class)
    ->where('file', 'form-components(\.min)?\.css')
    ->name('form-components.stylesheet');
