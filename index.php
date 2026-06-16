<?php
session_start();

if (isset($_SESSION['usuario_id'])) {
    header("Location: area_restrita.php");
    exit;
}

header("Location: login.php");
exit;
