<?php
/**
 * Bootstrap a new Moar module project.
 */

if ($argc < 2) {
  echo "usage: php {$argv[0]} REPO\n";
  exit(1);
}

function expand_tokens ($file, $tokens) {
  $guts = file_get_contents($file);
  $keys = array_map(
      function ($val) { return '{{' . $val . '}}'; },
      array_keys($tokens));
  $new = str_replace($keys, array_values($tokens), $guts);
  file_put_contents($file, $new);
}

$repo = $argv[1];
$parts = explode('-', $repo);
$suite = $parts[0];
$components = array_slice($parts, 1);
$ucparts = array_map('ucfirst', $parts);

$replacements = array(
    'REPO' => $repo,
    'COMPONENT' => implode('-', $ucparts),
    'NAMESPACE' => implode('\\', $ucparts),
    'NAMESPACE_PATH' => implode('/', $ucparts),
    'PACKAGIST' => "{$suite}/" . implode('-', $components),
);
$replacements['JSON_NAMESPACE'] = json_encode($replacements['NAMESPACE']);

$files = array(
    '.gitignore',
    '.travis.yml',
    'LICENSE',
    'README.md',
    'composer.json',
    'phpunit.xml.dist',
  );

echo "======================================================\n";
foreach ($replacements as $key => $value) {
  echo "{$key}: {$value}\n";
}
echo "======================================================\n";

foreach ($files as $fn) {
  echo "Expanding: {$fn}\n";
  expand_tokens($fn, $replacements);
}

echo "Making directories.\n";
mkdir("src/{$replacements['NAMESPACE_PATH']}", 0755, true);
touch("src/{$replacements['NAMESPACE_PATH']}/.gitignore");
mkdir("tests/{$replacements['NAMESPACE_PATH']}", 0755, true);
touch("tests/{$replacements['NAMESPACE_PATH']}/.gitignore");

echo "Done.\n\n";
echo "Don't forget to edit README.md and composer.json to set descriptions.\n";
