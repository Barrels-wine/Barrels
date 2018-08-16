<?php

$raw = $argv[1];
$raw = str_replace (["\r\n", "\n", "\r"], " ", $raw);
$raw = preg_replace('/\s+/', ' ', $raw);
$raw = str_replace(' ,', ',', $raw);
$raw = preg_replace('/,+/', ',', $raw);
$raw = str_replace(", ", ",\n", $raw);
$raw = str_replace(['-', ' '], '_', $raw);

$raw = str_replace('é', 'e', $raw);
$raw = str_replace('è', 'e', $raw);
$raw = str_replace('à', 'a', $raw);
$raw = str_replace('ç', 'c', $raw);
$raw = str_replace('ï', 'i', $raw);
$raw = str_replace('ü', 'u', $raw);
$raw = str_replace('û', 'u', $raw);
$raw = str_replace('ô', 'o', $raw);
$raw = str_replace('â', 'a', $raw);
$raw = str_replace('ê', 'e', $raw);
$raw = str_replace('\'', '_', $raw);
$raw = str_replace('’', '_', $raw);

$raw = strtoupper($raw);
$raw = 'Varietals::'.$raw;
$raw = preg_replace('/\n(.*)/', "\nVarietals::$1", $raw);
$pieces = explode(",\n", $raw);
sort($pieces);
$pieces = array_unique($pieces);

$result = join($pieces, ",\n");

echo "=====================================\n\n";
echo $result;
echo "\n\n=====================================";
exit;
