<?php

function vite($entry, $buildDir = 'dist')
{
   $baseUrl = isset($_ENV['VITE_BASE_URL']) ? $_ENV['VITE_BASE_URL'] : '/dist/';
   $port = isset($_ENV['VITE_PORT']) ? $_ENV['VITE_PORT'] : 5173;

   $manifestPath = __DIR__ . '/' . $buildDir . '/.vite/manifest.json';

   // Check if manifest exists (Production Mode)
   if (file_exists($manifestPath)) {
      $manifest = json_decode(file_get_contents($manifestPath), true);

      if (isset($manifest[$entry])) {
         $file = $manifest[$entry]['file'];
         $css = isset($manifest[$entry]['css']) ? $manifest[$entry]['css'] : [];

         $output = '';
         // CSS from manifest
         foreach ($css as $cssFile) {
            $output .= '<link rel="stylesheet" href="' . $baseUrl . $cssFile . '">' . PHP_EOL;
         }
         // JS from manifest
         $output .= '<script type="module" src="' . $baseUrl . $file . '"></script>' . PHP_EOL;

         return $output;
      }
   }

   // Development Mode
   return '<script type="module" src="http://localhost:' . $port . '/' . $entry . '"></script>' . PHP_EOL;
}
