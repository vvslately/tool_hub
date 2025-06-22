<?php
class Controller {
    public static function render($page) {
        include __DIR__ . '/../views/layout/header.php';
        include __DIR__ . '/../views/layout/navbar.php';
        include __DIR__ . "/../views/pages/{$page}/{$page}.php";
        include __DIR__ . '/../views/layout/footer.php';
    }
}
?>