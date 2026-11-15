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
     * Components published class path.
     *
     * @var string
     */
    public $publishedPath;

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
        $this->viewPath = resource_path('views\components\form\\');
        $this->defaultNamespace = 'Bjnstnkvc\FormComponents\Components';
        $this->publishedNamespace = 'App\View\Components\Form';
        $this->defaultPath = 'Bjnstnkvc\FormComponents\Components\\';
        $this->publishedPath = 'App\View\Components\Form\\';
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
     * Returns published Class location.
     *
     * @param  string  $class  Class name.
     */
    public function publishClass(string $class): string
    {
        return $this->publishedPath.$class.'.php';
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
     * Updates Component Class file content.
     *
     * @param  string  $view  Name of the view.
     * @param  string  $class  Class file.
     * @return array|string|string[]
     */
    public function updateClass(string $view, string $class)
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
     * @return array|string|string[]
     */
    public function updateConfig(string $class, string $config)
    {
        return str_replace(
            $this->defaultPath.$class.'\\View\\Components\\'.$class.'::class',
            $this->publishedPath.$class.'::class',
            $config
        );
    }

    /**
     * Restores published Component Classes.
     *
     * @param  string  $view  Name of the view.
     * @param  string  $class  Class file.
     * @return array|string|string[]
     */
    public function restoreClass(string $view, string $class)
    {
        return str_replace(
            $this->publishedPath.$view.'::class',
            $this->defaultPath.$view.'\\View\\Components\\'.$view.'::class',
            $class
        );
    }
}
