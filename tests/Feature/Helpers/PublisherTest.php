<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests\Feature\Helpers;

use Bjnstnkvc\FormComponents\Helpers\Publisher;
use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class PublisherTest extends TestCase
{
    #[Test]
    public function it_publishes_into_the_application_directories(): void
    {
        $publisher = new Publisher;

        $this->assertSame(config_path('form_components.php'), $publisher->configPath);
        $this->assertSame(app_path('View/Components/Form/Input.php'), $publisher->publishClass('Input'));
        $this->assertSame(resource_path('views/components/form/input.blade.php'), $publisher->publishView('input'));
    }
}
