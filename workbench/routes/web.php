<?php

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;

use function Orchestra\Testbench\package_path;

Route::get('/', fn (): Factory|View => view('playground', [
    'styles' => file_get_contents(package_path('src', 'public', 'css', 'form-components.css')),
    'scripts' => file_get_contents(package_path('src', 'public', 'js', 'form-components.js')),
]))->name('playground');
