<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPaths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ])
    ->withRootFiles()
    ->withSkip([
        '*.blade.php',
        __DIR__.'/src/Console/Commands/PublishesComponents.php',
        __DIR__.'/src/Console/Commands/RestoresComponents.php',
        __DIR__.'/src/FormComponentsServiceProvider.php',
        __DIR__.'/src/Helpers/Publisher.php',
        __DIR__.'/src/Components/Base/View/Components/Base.php',
        __DIR__.'/src/Components/Button/View/Components/Button.php',
        __DIR__.'/src/Components/Checkbox/View/Components/Checkbox.php',
        __DIR__.'/src/Components/Date/View/Components/Date.php',
        __DIR__.'/src/Components/DateTime/View/Components/DateTime.php',
        __DIR__.'/src/Components/Email/View/Components/Email.php',
        __DIR__.'/src/Components/Error/View/Components/Error.php',
        __DIR__.'/src/Components/File/View/Components/File.php',
        __DIR__.'/src/Components/Input/View/Components/Input.php',
        __DIR__.'/src/Components/Password/View/Components/Password.php',
        __DIR__.'/src/Components/Radio/View/Components/Radio.php',
        __DIR__.'/src/Components/Search/View/Components/Search.php',
        __DIR__.'/src/Components/Select/View/Components/Select.php',
        __DIR__.'/src/Components/Textarea/View/Components/Textarea.php',
        __DIR__.'/src/Components/Time/View/Components/Time.php',
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
