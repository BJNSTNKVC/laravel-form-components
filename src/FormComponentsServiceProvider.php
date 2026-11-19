<?php

namespace Bjnstnkvc\FormComponents;

use Bjnstnkvc\FormComponents\Assets\Stylesheet;
use Bjnstnkvc\FormComponents\Components\Error\View\Components\Error;
use Bjnstnkvc\FormComponents\Console\Commands\InstallsComponents;
use Bjnstnkvc\FormComponents\Console\Commands\PublishesComponents;
use Bjnstnkvc\FormComponents\Console\Commands\RestoresComponents;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
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
     * Create new Service Provider instance.
     */
    public function __construct($app)
    {
        parent::__construct($app);

        $this->components = config('form_components.components') ?: [];
        $this->prefix = config('form_components.prefix');
        $this->separator = config('form_components.separator');
    }

    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->scoped(Stylesheet::class);

        if ($this->app->runningInConsole()) {
            $this->commands([
                InstallsComponents::class,
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
        $this->bootStylesheet();
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
    }

    /**
     * Boot component Blade directives.
     */
    private function bootDirectives(): void
    {
        Blade::directive('componentStyles', fn (string $variant): string => '<?php echo app(\\'.Stylesheet::class.'::class)->tag('.$variant.'); ?>');
    }

    /**
     * Serve the stylesheet and link it into pages rendering components.
     */
    private function bootStylesheet(): void
    {
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');

        View::composer(['form-components::*', 'components.form.*'], fn () => $this->app->make(Stylesheet::class)->require());

        Event::listen(RequestHandled::class, fn (RequestHandled $event) => $this->app->make(Stylesheet::class)->inject($event->response));
    }
}
