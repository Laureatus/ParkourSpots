<?php

function parkour_autoloader($class_name) {
  // Website/src/Classes/SpotRepository.php
  if (stripos($class_name, 'Parkour\\') === 0) {
    $tmp = str_replace('\\','/',$class_name);
    $path = 'src/Classes/';
    $file = str_ireplace('Parkour/', $path, $tmp) . '.php';

    if (file_exists($file)) {
      require_once $file;
    }
  }
}

spl_autoload_register('parkour_autoloader');
