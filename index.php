<?php

declare(strict_types=1);

namespace App;


require_once('./Exception/AppException.php');
require_once('./Exception/StorageException.php');
require_once('./Exception/ConfigurationException.php');
require_once('./src/NoteController.php');
include_once('./src/Request.php');
include_once('./src/utils/debug.php');
require_once('./config/config.php');


use App\Exception\AppException;
use App\Exception\StorageException;
use App\Exception\CofigurationException;
use App\Request;
use Throwable;


$request = new Request($_GET, $_POST);

try {
    AbstractController::initConfiguration($configuration);
    $controller = new NoteController($request);
    $controller->run();
} catch (AppException $e) {
    echo "<h1>Wsytapil blod w aplikacji!</h1>";
    echo '<h3>' . $e->getMessage() . '</h3>';
} catch (Throwable $e) {
    echo "<h1> Wystapil blod w aplikacji! </h1>";
    dump($e);
}