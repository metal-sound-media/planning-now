<?php

// Set default timezone to Europe/Paris
ini_set('date.timezone', 'Europe/Paris');

// Open file and get content
$content = file_get_contents('planning.csv');

/** @var array<string, array{text: string, extra: mixed[]}> $data key: date(Y-m-d H:i), value: event's data to display */
$data = [];

// Loop on all CSV lines
foreach (explode(\PHP_EOL, $content) as $line) {
    // Skip empty line
    if (trim($line) === '') {
        continue;
    }

    // Parse CSV line and get all rows
    $rows = str_getcsv($line);

    // Get datetime and text
    $datetime = "$rows[0] $rows[1]";
    $text = $rows[2];

    // Get the image
    $image = $rows[3] ?? null;

    // Get extra data
    $extra = array_splice($rows, 4);

    // Throw an error if we've already had an event at this time
    if (true === \array_key_exists($datetime, $data)) {
        throw new \Exception("You already have an event at this time: $datetime");
    }

    // Store the data
    $data[$datetime] = [
        'text' => $text,
        'image' => $image,
        'extra' => $extra,
    ];
}

// Sort all event by date (ksort => key by sort) (sort order = asc)
ksort($data);

// Prepare variable to expose
$currentEvent = $nextEvent = null;

// Get the current time
$now = new \DateTime();

// Loop on each event
foreach ($data as $datetime => $eventData) {
    // Convert the current datetime to a real DateTime object
    $datetime = \DateTime::createFromFormat('Y-m-d H:i', $datetime);

    // Get the next entry of the array
    $next = next($data);

    // If we don't have a next entry, stop the loop
    if ($next === false) {
        if ($currentEvent === null) {
            $currentEvent = $eventData;
        }

        break;
    }

    // Convert the next datetime to a real DateTime object
    $nextDatetime = \DateTime::createFromFormat('Y-m-d H:i', key($data));

    // If the current time is before the current datetime or after the next
    if ($now < $datetime || $now > $nextDatetime) {
        continue;
    }

    $currentEvent = $eventData;
    $nextEvent = $next;
}

header('Content-type: application/json; charset=utf-8');

echo json_encode(
    [
        'now' => $currentEvent,
        'after' => $nextEvent,
    ],
);
