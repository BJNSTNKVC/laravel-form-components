<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
        __DIR__.'/workbench',
    ])
    ->withRootFiles()
    ->withSkip([
        '*.blade.php',
        __DIR__.'/src/Console/Commands/PublishesComponents.php',
        __DIR__.'/src/Console/Commands/RestoresComponents.php',
        __DIR__.'/src/FormComponentsServiceProvider.php',
        __DIR__.'/src/Helpers/Publisher.php',
        __DIR__.'/src/View/Components/Form/Base.php',
        __DIR__.'/src/View/Components/Form/Button.php',
        __DIR__.'/src/View/Components/Form/Checkbox.php',
        __DIR__.'/src/View/Components/Form/Date.php',
        __DIR__.'/src/View/Components/Form/DateTime.php',
        __DIR__.'/src/View/Components/Form/Email.php',
        __DIR__.'/src/View/Components/Form/Error.php',
        __DIR__.'/src/View/Components/Form/File.php',
        __DIR__.'/src/View/Components/Form/Input.php',
        __DIR__.'/src/View/Components/Form/Password.php',
        __DIR__.'/src/View/Components/Form/Radio.php',
        __DIR__.'/src/View/Components/Form/Search.php',
        __DIR__.'/src/View/Components/Form/Select.php',
        __DIR__.'/src/View/Components/Form/Textarea.php',
        __DIR__.'/src/View/Components/Form/Time.php',
        __DIR__.'/src/config/form_components.php',
    ])
    ->withCache(__DIR__.'/build/rector')
    ->withPhpSets()
    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        typeDeclarations: true,
        privatization: true,
        instanceOf: true,
        earlyReturn: true,
    )
    ->withAttributesSets(phpunit: true)
    ->withImportNames(importShortClasses: false, removeUnusedImports: true);
