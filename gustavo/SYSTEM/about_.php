<?php
session_start();
$_tabs_atr = 15;
require_once 'includes/_wrong.php';
require_once '../_con.php';
require_once 'includes/logic/logic_g.php';
require_once 'includes/scripts/_about.php';
require_once 'includes/_messages.php';
//
?>

<! DOCTYPE html>
    <html lang="es">

    <head>
        <?php include "includes/_head.php"; ?>
    </head>

    <body>
        <div id="fondo"></div>
        <main>
            <?php include "includes/_header.php"; ?>
            
        </main>
        <?php include 'includes/_bar.php'; ?>
    </body>

    </html>