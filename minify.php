<?php

$sourceDir = realpath(__DIR__ . '/data/local');
$targetDir = __DIR__ . '/data/minified_local';

function minifyJsonFiles($sourceDir, $targetDir) {
    $directory = new RecursiveDirectoryIterator($sourceDir, FilesystemIterator::SKIP_DOTS);
    $iterator = new RecursiveIteratorIterator($directory);

    foreach ($iterator as $fileInfo) {
        if ($fileInfo->isFile() && strtolower($fileInfo->getExtension()) === 'json') {
            $sourcePath = $fileInfo->getRealPath();

            // Get path relative to sourceDir
            $relativePath = substr($sourcePath, strlen($sourceDir));
            $targetPath = $targetDir . $relativePath;

            $jsonContent = file_get_contents($sourcePath);
            $decoded = json_decode($jsonContent, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                $minified = json_encode($decoded, JSON_UNESCAPED_SLASHES);

                // Ensure destination directory exists
                $targetFolder = dirname($targetPath);
                if (!is_dir($targetFolder)) {
                    mkdir($targetFolder, 0777, true);
                }

                file_put_contents($targetPath, $minified);
                echo "✅ Minified: $targetPath\n";
            } else {
                echo "⚠️ Skipped (Invalid JSON): $sourcePath — " . json_last_error_msg() . "\n";
            }
        }
    }
}

if (!is_dir($sourceDir)) {
    die("❌ Source directory not found: $sourceDir\n");
}

minifyJsonFiles($sourceDir, $targetDir);
