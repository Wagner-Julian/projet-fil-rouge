<?php

// include/config.php
define('UPLOAD_URL', '/ressources/telechargement/');           // ← pas de /public
define('UPLOAD_DISK', realpath(__DIR__ . '/../public' . UPLOAD_URL) . DIRECTORY_SEPARATOR);
