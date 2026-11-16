<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Http\Controllers;

use Bjnstnkvc\FormComponents\Assets\Stylesheet;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class StylesheetController
{
    /**
     * Serve a stylesheet the package ships.
     */
    public function __invoke(string $file): BinaryFileResponse
    {
        return response()->file(Stylesheet::path($file), [
            'Content-Type' => 'text/css; charset=UTF-8',
            'Cache-Control' => 'public, max-age=31536000, immutable',
        ]);
    }
}
