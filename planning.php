<?php

/**
 * @author BaBeuloula <https://github.com/babeuloula>
 */

['now' => $currentEvent, 'after'=> $nextEvent] = require_once('parser.php');

header('Content-type: application/json; charset=utf-8');

echo json_encode(
    [
        'now' => $currentEvent,
        'after' => $nextEvent,
    ],
);
