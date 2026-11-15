<?php

declare(strict_types=1);

namespace Bjnstnkvc\FormComponents\Tests;

use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase as Testbench;

abstract class TestCase extends Testbench
{
    use WithWorkbench;
}
