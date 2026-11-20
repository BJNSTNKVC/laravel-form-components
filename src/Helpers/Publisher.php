<?php

namespace Bjnstnkvc\FormComponents\Helpers;

use Illuminate\Support\Str;

class Publisher
{
    /**
     * Components root directory, holding one module folder per component.
     *
     * @var string
     */
    public $classRoot;

    /**
     * Components published config path.
     *
     * @var string
     */
    public $configPath;

    /**
     * Components published view path.
     *
     * @var string
     */
    public $viewPath;

    /**
     * Components published script path.
     */
    public string $scriptPath;

    /**
     * Components default Namespace.
     *
     * @var string
     */
    public $defaultNamespace;

    /**
     * Components published Namespace.
     *
     * @var string
     */
    private $publishedNamespace;

    /**
     * Components default class path.
     *
     * @var string
     */
    private $defaultPath;

    /**
     * Components published Namespace prefix.
     *
     * @var string
     */
    public $publishedPath;

    /**
     * Directory the Component classes are published to.
     *
     * @var string
     */
    public $classPath;

    /**
     * Components default view/blade name.
     *
     * @var string
     */
    private $defaultBlade;

    /**
     * Components published view/blade name.
     *
     * @var string
     */
    private $publishedBlade;

    public function __construct()
    {
        $this->classRoot = __DIR__.'/../Components/';
        $this->configPath = config_path('form_components.php');
        $this->viewPath = resource_path('views/components/form/');
        $this->scriptPath = resource_path('js/components/form/');
        $this->defaultNamespace = 'Bjnstnkvc\FormComponents\Components';
        $this->publishedNamespace = 'App\View\Components\Form';
        $this->defaultPath = 'Bjnstnkvc\FormComponents\Components\\';
        $this->publishedPath = 'App\View\Components\Form\\';
        $this->classPath = app_path('View/Components/Form/');
        $this->defaultBlade = 'form-components::';
        $this->publishedBlade = 'components.form.';
    }

    /**
     * Returns Class location.
     *
     * @param  string  $class  Class name.
     */
    public function getClass(string $class): string
    {
        return $this->classRoot.$class.'/View/Components/'.$class.'.php';
    }

    /**
     * Returns View location.
     *
     * @param  string  $view  View name.
     */
    public function getView(string $view): string
    {
        return $this->classRoot.Str::studly($view).'/resources/views/'.$view.'.blade.php';
    }

    /**
     * Returns Script location.
     */
    public function getScript(string $view): string
    {
        return $this->classRoot.Str::studly($view).'/resources/js/'.$view.'.js';
    }

    /**
     * Returns the config file the package ships.
     */
    public function getConfig(): string
    {
        return __DIR__.'/../config/form_components.php';
    }

    /**
     * Returns published Class location.
     *
     * @param  string  $class  Class name.
     */
    public function publishClass(string $class): string
    {
        return $this->classPath.$class.'.php';
    }

    /**
     * Returns published View location.
     *
     * @param  string  $view  Class name.
     */
    public function publishView(string $view): string
    {
        return $this->viewPath.$view.'.blade.php';
    }

    /**
     * Returns published Script location.
     */
    public function publishScript(string $view): string
    {
        return $this->scriptPath.$view.'.js';
    }

    /**
     * Updates Component Class file content.
     *
     * @param  string  $view  Name of the view.
     * @param  string  $class  Class file.
     */
    public function updateClass(string $view, string $class): string
    {
        return str_replace(
            [$this->defaultNamespace.'\\'.Str::studly($view).'\\View\\Components', $this->defaultBlade.$view],
            [$this->publishedNamespace, $this->publishedBlade.$view],
            $class
        );
    }

    /**
     * Updates Components' config file contents.
     *
     * @param  string  $class  Name of the class.
     * @param  string  $config  Class config.
     */
    public function updateConfig(string $class, string $config): string
    {
        return $this->replaceClass(
            $this->defaultPath.$class.'\\View\\Components\\'.$class,
            $this->publishedPath.$class,
            $config
        );
    }

    /**
     * Restores published Component Classes.
     *
     * @param  string  $view  Name of the view.
     * @param  string  $class  Class file.
     */
    public function restoreClass(string $view, string $class): string
    {
        return $this->replaceClass(
            $this->publishedPath.$view,
            $this->defaultPath.$view.'\\View\\Components\\'.$view,
            $class
        );
    }

    /**
     * Swaps a Component class imported or referenced in the config.
     *
     * @param  string  $from  Class being replaced.
     * @param  string  $to  Replacement class.
     * @param  string  $config  Class config.
     */
    private function replaceClass(string $from, string $to, string $config): string
    {
        return str_replace(
            ['use '.$from.';', $from.'::class'],
            ['use '.$to.';', $to.'::class'],
            $config
        );
    }
}
