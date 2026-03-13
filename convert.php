<?php

require 'vendor/autoload.php';
(new Symfony\Component\Dotenv\Dotenv())->load(__DIR__ . '/.env');
require 'vite_helper.php';

use League\CommonMark\CommonMarkConverter;

// Define directories
$docsDir = __DIR__ . '/docs';
$distDir = __DIR__ . '/dist';
$themeDir = __DIR__ . '/themes/simple';

// Create dist directory if it doesn't exist
if (!is_dir($distDir)) {
   if (!mkdir($distDir, 0755, true)) {
      die("Failed to create dist directory.\n");
   }
}

// Check theme
if (!is_dir($themeDir) || !file_exists($themeDir . '/layout.php')) {
   die("Theme not found at $themeDir\n");
}

// Instantiate the converter
$converter = new CommonMarkConverter();

// Scan for markdown files
$files = [];
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($docsDir));
foreach ($iterator as $file) {
   if ($file->isFile() && $file->getExtension() === 'md') {
      $files[] = $file->getPathname();
   }
}

if (empty($files)) {
   echo "No markdown files found in $docsDir.\n";
   exit;
}

// Generate Menu
$menu = [];
foreach ($files as $file) {
   $filename = basename($file, '.md');
   // Simple title generation: clean up filename
   $title = ucfirst(str_replace(['-', '_'], ' ', $filename));
   $menu[] = [
      'title' => $title,
      'link' => $filename . '.html',
      'file' => $file,
      'filename' => $filename
   ];
}

foreach ($menu as $page) {
   echo "Converting " . basename($page['file']) . "...\n";

   // Read content
   $contentMarkdown = file_get_contents($page['file']);
   if ($contentMarkdown === false) {
      echo "Failed to read {$page['file']}\n";
      continue;
   }

   // Convert to HTML
   try {
      $contentHtml = $converter->convert($contentMarkdown);
   } catch (\Exception $e) {
      echo "Error converting {$page['file']}: " . $e->getMessage() . "\n";
      continue;
   }

   // Prepare data for view
   $title = $page['title'];
   $content = $contentHtml;

   // Set active state
   foreach ($menu as &$item) {
      $item['active'] = ($item['filename'] === $page['filename']);
   }
   unset($item); // Break reference

   // Render Layout
   ob_start();
   include $themeDir . '/layout.php';
   $html = ob_get_clean();

   // Determine output path
   $outputPath = $distDir . '/' . $page['filename'] . '.html';

   // Write HTML to dist directory
   if (file_put_contents($outputPath, $html) === false) {
      echo "Failed to write to $outputPath\n";
   } else {
      echo "Saved to $outputPath\n";
   }
}

echo "Conversion complete.\n";
