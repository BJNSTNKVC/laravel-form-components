<?php

namespace Bjnstnkvc\FormComponents;

use Bjnstnkvc\FormComponents\Console\Commands\PublishesComponents;
use Bjnstnkvc\FormComponents\Console\Commands\RestoresComponents;
use Bjnstnkvc\FormComponents\Views\Components\Form\Error;
use Bjnstnkvc\FormComponents\Views\Components\Form\Label;
use Bjnstnkvc\FormComponents\Views\Components\Form\Title;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FormComponentsServiceProvider extends ServiceProvider
{
    /**
     * Form components.
     */
    public $components;

    /**
     * Form components path used to publish config files.
     */
    public $publishPath;

    /**
     * Form components asset path.
     */
    public $assetPath;

    /**
     * Form components prefix.
     */
    public $prefix;

    /**
     * Form components separator.
     */
    public $separator;

    /**
     * Create new Service Provider instance.
     *
     * @param $app
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->components  = config('form_components.components') ?: [];
        $this->publishPath = config('form_components.publish_path') ?: public_path('vendor/form-components');
        $this->assetPath   = substr($this->publishPath, strlen(public_path('\\')), strlen($this->publishPath) - strlen(public_path('\\')));
        $this->prefix      = config('form_components.prefix');
        $this->separator   = config('form_components.separator');
    }

    /**
     * Register services.
     *
     * @return void
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
     *
     * @return void
     */
    public function boot(): void
    {
        $this->bootResources();
        $this->bootBladeComponents();
        $this->bootPublishing();
        $this->bootDirectives();
    }

    /**
     * Boot all blade files from resources.
     *
     * @return void
     */
    private function bootResources(): void
    {
        $this->loadViewsFrom(__DIR__ . '/resources/views/components', 'form-components');
    }

    /**
     * Boot all Blade components.
     *
     * @return void
     */
    private function bootBladeComponents(): void
    {
        foreach ($this->components as $alias => $class) {
            Blade::component($class, $this->prefix . $this->separator . $alias);
        }

        Blade::component(Label::class, 'form::label');
        Blade::component(Error::class, 'form::error');
    }

    /**
     * Boot all publishable files.
     *
     * @return void
     */
    private function bootPublishing(): void
    {
        // Publish Form Component Config file.
        $this->publishes([
            __DIR__ . '/config/form_components.php' => config_path('form_components.php'),
        ], 'form-config');

        // Publish Form Component Styles.
        $this->publishes([
            __DIR__ . '/public/css/form-components.css'     => $this->publishPath . '/css/form-components.css',
            __DIR__ . '/public/css/form-components.min.css' => $this->publishPath . '/css/form-components.min.css',
        ], 'form-styles');

        // Publish Form Component Scripts.
        $this->publishes([
            __DIR__ . '/public/js/form-components.js'     => $this->publishPath . '/js/form-components.js',
            __DIR__ . '/public/js/form-components.min.js' => $this->publishPath . '/js/form-components.min.js',
        ], 'form-scripts');

        // Publish Form Component Styles and Scripts.
        $this->publishes([
            __DIR__ . '/public/css/form-components.css'     => $this->publishPath . '/css/form-components.css',
            __DIR__ . '/public/css/form-components.min.css' => $this->publishPath . '/css/form-components.min.css',
            __DIR__ . '/public/js/form-components.js'       => $this->publishPath . '/js/form-components.js',
            __DIR__ . '/public/js/form-components.min.js'   => $this->publishPath . '/js/form-components.min.js',
        ], 'form-resources');
    }

    /**
     * Boot component Blade directives.
     *
     * @return void
     */
    private function bootDirectives(): void
    {
        Blade::directive('componentStyles', function ($type) {
            $type = str_replace(['\'', '"'], '', $type);
            $file = $type === 'full' ? 'form-components.css' : 'form-components.min.css';

            return "<link href='" . asset($this->assetPath . '/css/' . $file) . "' rel='stylesheet'>";
        });

        Blade::directive('componentScripts', function ($type) {
            $type = str_replace(['\'', '"'], '', $type);
            $file = $type === 'full' ? 'form-components.js' : 'form-components.min.js';

            return "<script src='" . asset($this->assetPath . '/js/' . $file) . "' defer></script>";
        });
    }
}
