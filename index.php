<?php

require 'vendor/autoload.php';
(new Symfony\Component\Dotenv\Dotenv())->load(__DIR__ . '/.env');
require 'vite_helper.php';

use League\CommonMark\CommonMarkConverter;

// Define directories
$docsDir = __DIR__ . '/docs';
$themeDir = __DIR__ . '/themes/simple';

// Check theme
if (!is_dir($themeDir) || !file_exists($themeDir . '/layout.php')) {
   die("Theme not found at $themeDir");
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
   die("No markdown files found in $docsDir.");
}

// Determine current page
$pageParam = isset($_GET['page']) ? $_GET['page'] : '';
$currentPageFile = '';
$currentFilename = '';

// Generate Menu
$menu = [];
foreach ($files as $file) {
   $filename = basename($file, '.md');
   // Simple title generation
   $title = ucfirst(str_replace(['-', '_'], ' ', $filename));

   $menu[] = [
      'title' => $title,
      'link' => '?page=' . $filename,
      'file' => $file,
      'filename' => $filename
   ];

   // Check if this is the requested page
   if ($filename === $pageParam) {
      $currentPageFile = $file;
      $currentFilename = $filename;
   }
}

// Default to first page if not specified or not found
if (empty($currentPageFile)) {
   if (!empty($pageParam)) {
      // 404 behavior - keeping it simple for now, just load first page or show error
      // die("Page not found");
      // Fallback to first
      $currentPageFile = $files[0];
      $currentFilename = basename($files[0], '.md');
   } else {
      // Default homepage
      $currentPageFile = $files[0];
      $currentFilename = basename($files[0], '.md');
   }
}

// Read content
$contentMarkdown = file_get_contents($currentPageFile);
if ($contentMarkdown === false) {
   die("Failed to read $currentPageFile");
}

// Convert to HTML
try {
   $contentHtml = $converter->convert($contentMarkdown);
} catch (\Exception $e) {
   die("Error converting $currentPageFile: " . $e->getMessage());
}

// Prepare data for view
$title = ucfirst(str_replace(['-', '_'], ' ', $currentFilename));
$content = $contentHtml;

// Set active state
foreach ($menu as &$item) {
   $item['active'] = ($item['filename'] === $currentFilename);
}
unset($item);

// Render Layout
include $themeDir . '/layout.php';
