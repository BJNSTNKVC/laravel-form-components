<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Components\File\Tests\Feature;

use Bjnstnkvc\FormComponents\Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class FileTest extends TestCase
{
    #[Test]
    public function it_renders_a_file_input(): void
    {
        $this->withViewErrors([])
            ->blade('<x-form::file name="avatar" />')
            ->assertSee('type="file"', false)
            ->assertSee('name="avatar"', false);
    }
}
