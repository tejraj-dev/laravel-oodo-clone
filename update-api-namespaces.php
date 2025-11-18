<?php

/**
 * Update namespaces for modular API structure
 */

$modules = ['Core', 'CRM', 'Sales', 'Purchase', 'Inventory', 'HR', 'Projects', 'Manufacturing', 'Accounting', 'POS'];

foreach ($modules as $module) {
    // Update Controllers
    $controllerDir = __DIR__ . "/app/Modules/{$module}/Http/Controllers/Api";
    if (is_dir($controllerDir)) {
        $files = glob("{$controllerDir}/*.php");
        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Update namespace
            $content = str_replace(
                "namespace App\\Http\\Controllers\\Api\\V1\\{$module};",
                "namespace App\\Modules\\{$module}\\Http\\Controllers\\Api;",
                $content
            );

            // Update resource use statements
            $content = str_replace(
                "use App\\Http\\Resources\\V1\\{$module}\\",
                "use App\\Modules\\{$module}\\Http\\Resources\\",
                $content
            );

            file_put_contents($file, $content);
            echo "Updated controller: " . basename($file) . "\n";
        }
    }

    // Update Resources
    $resourceDir = __DIR__ . "/app/Modules/{$module}/Http/Resources";
    if (is_dir($resourceDir)) {
        $files = glob("{$resourceDir}/*.php");
        foreach ($files as $file) {
            $content = file_get_contents($file);

            // Update namespace
            $content = str_replace(
                "namespace App\\Http\\Resources\\V1\\{$module};",
                "namespace App\\Modules\\{$module}\\Http\\Resources;",
                $content
            );

            file_put_contents($file, $content);
            echo "Updated resource: " . basename($file) . "\n";
        }
    }
}

echo "\nAll namespaces updated successfully!\n";
