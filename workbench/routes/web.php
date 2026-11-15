<?php

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;

use function Orchestra\Testbench\package_path;

Route::get('/', function (): Factory|View {
    $messages = collect(['input', 'email', 'select', 'checkbox', 'radio'])
        ->mapWithKeys(fn (string $field): array => [$field.'_error' => ['The '.$field.' field is required.']]);

    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag($messages->all())));

    return view('playground', [
        'styles' => file_get_contents(package_path('src', 'public', 'css', 'form-components.css')),
        'scripts' => file_get_contents(package_path('src', 'public', 'js', 'form-components.js')),
    ]);
})->name('playground');
