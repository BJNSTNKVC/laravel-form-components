<?php

namespace Bjnstnkvc\FormComponents;

use Bjnstnkvc\FormComponents\Components\Error\View\Components\Error;
use Bjnstnkvc\FormComponents\Console\Commands\PublishesComponents;
use Bjnstnkvc\FormComponents\Console\Commands\RestoresComponents;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormComponentsServiceProvider extends ServiceProvider
{
    /**
     * Form components.
     */
    public $components;

    /**
     * Form components prefix.
     */
    public $prefix;

    /**
     * Form components separator.
     */
    public $separator;

    /**
     * Form components path used to publish config files.
     */
    public $publishPath;

    /**
     * Form components asset path.
     */
    public $assetPath;

    /**
     * Create new Service Provider instance.
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->components = config('form_components.components') ?: [];
        $this->prefix = config('form_components.prefix');
        $this->separator = config('form_components.separator');
        $this->publishPath = config('form_components.publish_path') ?: public_path('vendor/form-components');
        $this->assetPath = substr($this->publishPath, strlen(public_path('\\')), strlen($this->publishPath) - strlen(public_path('\\')));
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PublishesComponents::class,
                RestoresComponents::class,
            ]);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootPublishing();
        $this->bootDirectives();
    }

    /**
     * Boot the Blade views kept in each component's resources folder.
     */
    private function bootResources(): void
    {
        $this->loadViewsFrom(glob(__DIR__.'/Components/*/resources/views', GLOB_ONLYDIR) ?: [], 'form-components');
    }

    /**
     * Boot all Blade components.
     */
    private function bootBladeComponents(): void
    {
        foreach ($this->components as $alias => $class) {
            Blade::component($class, $this->prefix.$this->separator.$alias);
        }

        Blade::component(Error::class, 'form::error');
    }

    /**
     * Boot all publishable files.
     */
    private function bootPublishing(): void
    {
        // Publish Form Component Config file.
        $this->publishes([
            __DIR__.'/config/form_components.php' => config_path('form_components.php'),
        ], 'form-config');

        // Publish Form Component Styles.
        $this->publishes([
            __DIR__.'/public/css/form-components.css' => $this->publishPath.'/css/form-components.css',
            __DIR__.'/public/css/form-components.min.css' => $this->publishPath.'/css/form-components.min.css',
        ], 'form-styles');

        // Publish Form Component Scripts.
        $this->publishes([
            __DIR__.'/public/js/form-components.js' => $this->publishPath.'/js/form-components.js',
            __DIR__.'/public/js/form-components.min.js' => $this->publishPath.'/js/form-components.min.js',
        ], 'form-scripts');

        // Publish Form Component Styles and Scripts.
        $this->publishes([
            __DIR__.'/public/css/form-components.css' => $this->publishPath.'/css/form-components.css',
            __DIR__.'/public/css/form-components.min.css' => $this->publishPath.'/css/form-components.min.css',
            __DIR__.'/public/js/form-components.js' => $this->publishPath.'/js/form-components.js',
            __DIR__.'/public/js/form-components.min.js' => $this->publishPath.'/js/form-components.min.js',
        ], 'form-resources');
    }

    /**
     * Boot component Blade directives.
     */
    private function bootDirectives(): void
    {
        Blade::directive('componentStyles', function ($type) {
            $type = str_replace(['\'', '"'], '', $type);
            $file = $type === 'full' ? 'form-components.css' : 'form-components.min.css';

            return "<link href='".asset($this->assetPath.'/css/'.$file)."' rel='stylesheet'>";
        });

        Blade::directive('componentScripts', function ($type) {
            $type = str_replace(['\'', '"'], '', $type);
            $file = $type === 'full' ? 'form-components.js' : 'form-components.min.js';

            return "<script src='".asset($this->assetPath.'/js/'.$file)."' defer></script>";
        });
    }
}
