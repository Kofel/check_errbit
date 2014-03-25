#!/usr/bin/env php
<?php

$url = 'http://' . $argv[1];

$response = @file_get_contents($url . '/api/v1/problems?auth_token=' . $argv[2]);

if (!$response) {
    echo 'Failed HTTP request to ' . $argv[1];
    exit(3);
}

$response = json_decode($response);

if (!$response || !is_array($response)) {
    echo 'Response body is not valid Errbit JSON';
    exit(3);
}

$resolved = $unresolved = 0;

foreach ($response as $item) {
    if (!isset($item->resolved)) {
        echo 'Response body is not valid Errbit JSON';
        exit(3);
    }

    if ($item->resolved) {
        $resolved++;
    }
    else {
        $unresolved++;
    }
}

if ($unresolved > 0) {
    echo 'Errbit has ' . $unresolved . ' unresolved problem(s) (' . $resolved . ' resolved).';
    exit(2);
}
else {
    echo 'Errbit has all ' . $resolved . ' problems resolved.';
    exit(0);
}