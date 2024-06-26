<?php

/**
 * @author BaBeuloula <https://github.com/babeuloula>
 */

['now' => $currentEvent, 'after'=> $nextEvent] = require_once('parser.php');

$image = ($_GET['when'] ?? 'now') === 'now'
    ? $currentEvent['image']
    : $nextEvent['image'] ?? ''
;
$text = ($_GET['when'] ?? 'now') === 'now'
    ? $currentEvent['text']
    : $nextEvent['text'] ?? ''
;

?>

<!doctype html>
<html lang="fr">
    <head>
        <title><?= $text ?></title>
        <style>
            html, body, img {
                background: transparent !important;
                padding: 0 !important;
                margin: 0 !important;
                overflow: hidden !important;
            }
        </style>
        <meta http-equiv="refresh" content="30">
    </head>
    <body>
        <img src="<?= $image ?>" alt="<?= $text ?>">
    </body>
</html>
