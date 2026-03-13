<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?php echo isset($title) ? $title : 'Document'; ?></title>
   <!-- Vite Assets -->
   <?php echo vite('themes/simple/assets/main.js'); ?>
</head>

<body>
   <div class="container-fluid">
      <div class="row">
         <!-- Sidebar -->
         <nav class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
            <div class="position-sticky pt-3">
               <ul class="nav flex-column">
                  <?php foreach ($menu as $item): ?>
                     <li class="nav-item">
                        <a class="nav-link <?php echo $item['active'] ? 'active' : ''; ?>" aria-current="page" href="<?php echo $item['link']; ?>">
                           <?php echo $item['title']; ?>
                        </a>
                     </li>
                  <?php endforeach; ?>
               </ul>
            </div>
         </nav>

         <!-- Main Content -->
         <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 content">
            <?php echo $content; ?>
         </main>
      </div>
   </div>
</body>

</html>