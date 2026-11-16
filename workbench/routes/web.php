<?php

use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\MessageBag;
use Illuminate\Support\ViewErrorBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

use function Orchestra\Testbench\package_path;

Route::get('/', function (): Factory|View {
    $messages = collect(['input', 'email', 'password', 'search', 'textarea', 'select', 'date', 'time', 'date_time', 'file', 'checkbox', 'radio'])
        ->mapWithKeys(fn (string $field): array => [$field.'_error' => ['The '.$field.' field is required.']]);

    view()->share('errors', (new ViewErrorBag)->put('default', new MessageBag($messages->all())));

    return view('playground', [
        'styles' => file_get_contents(package_path('src', 'public', 'css', 'form-components.css')),
    ]);
})->name('playground');

Route::get('/livewire', fn (): Factory|View => view('livewire', [
    'styles' => file_get_contents(package_path('src', 'public', 'css', 'form-components.css')),
]))->name('livewire');

Route::get('/js/alpine.js', fn (): BinaryFileResponse => response()->file(
    package_path('node_modules', 'alpinejs', 'dist', 'module.esm.js'),
    ['Content-Type' => 'text/javascript'],
));

Route::get('/js/components/{script}.js', function (string $script): BinaryFileResponse {
    $files = glob(package_path('src', 'Components', '*', 'resources', 'js', $script.'.js')) ?: [];

    abort_if($files === [], 404);

    return response()->file($files[0], ['Content-Type' => 'text/javascript']);
})->where('script', '[a-z-]+');

Route::get('/js/{script}.js', fn (string $script): BinaryFileResponse => response()->file(
    package_path('workbench', 'resources', 'js', $script.'.js'),
    ['Content-Type' => 'text/javascript'],
))->where('script', 'app|livewire');
