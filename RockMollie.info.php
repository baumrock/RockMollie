<?php

namespace ProcessWire;

$info = [
  'title' => 'RockMollie',
  'version' => json_decode(file_get_contents(__DIR__ . "/package.json"))->version,
  'summary' => 'Integrate Mollie Payments into your ProcessWire website',
  'autoload' => false,
  'singular' => true,
  'icon' => 'money',
];
