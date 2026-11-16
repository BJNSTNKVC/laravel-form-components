<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Assets;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Vite;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class Stylesheet
{
    /**
     * Whether the current request rendered a form component.
     */
    private bool $required = false;

    /**
     * Whether the current request already linked the stylesheet.
     */
    private bool $linked = false;

    /**
     * Path of a stylesheet file the package ships.
     */
    public static function path(string $file): string
    {
        return __DIR__.'/../public/css/'.$file;
    }

    /**
     * Note that the current request rendered a form component.
     */
    public function require(): void
    {
        $this->required = true;
    }

    /**
     * Link tag loading the minified or readable stylesheet.
     */
    public function tag(string $variant = 'minified'): string
    {
        $this->linked = true;

        $file = $variant === 'full' ? 'form-components.css' : 'form-components.min.css';
        $url = route('form-components.stylesheet', ['file' => $file, 'id' => $this->version($file)]);
        $nonce = Vite::cspNonce();

        return sprintf(
            '<link href="%s" rel="stylesheet"%s>',
            e($url),
            $nonce === null ? '' : ' nonce="'.e($nonce).'"',
        );
    }

    /**
     * Link the stylesheet at the start of a page rendering components.
     */
    public function inject(SymfonyResponse $response): void
    {
        if (! $this->required || $this->linked || ! config('form_components.inject_styles', true)) {
            return;
        }

        if (! $response instanceof Response || ! str_contains((string) $response->headers->get('Content-Type'), 'text/html')) {
            return;
        }

        $content = (string) $response->getContent();

        if (preg_match('/<head(?:\s[^>]*)?>/i', $content, $head, PREG_OFFSET_CAPTURE) !== 1) {
            return;
        }

        $offset = $head[0][1] + strlen($head[0][0]);
        $original = $response->original;

        $response->setContent(substr_replace($content, $this->tag(), $offset, 0));
        $response->original = $original;
    }

    /**
     * Short hash of a stylesheet, busting caches on change.
     */
    private function version(string $file): string
    {
        return substr((string) hash_file('xxh3', self::path($file)), 0, 8);
    }
}
