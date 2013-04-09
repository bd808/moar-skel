<?php
/**
 * Bootstrap a new Moar module project.
 *
 * @package Moar\Skel
 * @copyright 2013 Bryan Davis and contributors. All Rights Reserved.
 */

namespace Moar\Skel;


/**
 * Simple mustache-esque template expansion.
 *
 * Replaces `{{...}}` tokens in the provided file with token expansions from
 * the provided array.
 *
 * @param string $file File to expand
 * @param array $tokens Token => replace pairs to substitute in file
 * @return void
 */
function expand_tokens ($file, $tokens) {
  $guts = file_get_contents($file);
  $keys = array_map(
      function ($val) { return '{{' . $val . '}}'; },
      array_keys($tokens));
  $new = str_replace($keys, array_values($tokens), $guts);
  file_put_contents($file, $new);
} //end expand_tokens


/**
 * Setup the current directory as a new Moar module.
 *
 * @param string $repo Repository name
 * @return void
 */
function main ($repo) {

  echo <<<END
===========================================================================
    __  __    _______
    \ \ \ \  | __ __ | ___  _____  ____
     \ \ \ \ | ||_|| |/ _ \(____ |/ ___)
     / / / / | |   | | |_| / ___ | |        BOOTSTRAP
    /_/ /_/  |_|   |_|\___/\_____|_|

===========================================================================

Bootstrapping: {$repo}

END;

  $parts = explode('-', $repo);
  $suite = $parts[0];
  $module = array_slice($parts, 1);
  $ucparts = array_map('ucfirst', $parts);

  $tokens = array(
      'REPO'           => $repo,
      'COMPONENT'      => implode('-', $ucparts),
      'NAMESPACE'      => implode('\\', $ucparts),
      'NAMESPACE_PATH' => implode('/', $ucparts),
      'PACKAGIST'      => "{$suite}/" . implode('-', $module),
    );
  $tokens['JSON_NAMESPACE'] = json_encode($tokens['NAMESPACE']);

  $files = array(
      '.gitignore',
      '.travis.yml',
      'LICENSE',
      'README.md',
      'composer.json',
      'phpunit.xml.dist',
    );

  echo " - Tokens:\n";
  foreach ($tokens as $key => $value) {
    echo "    {$key} => {$value}\n";
  }

  echo " - Expanding files:\n";
  foreach ($files as $fn) {
    echo "    {$fn}\n";
    expand_tokens($fn, $tokens);
  }

  echo " - Making directories:\n";
  $dirs = array(
      "src/{$tokens['NAMESPACE_PATH']}",
      "tests/{$tokens['NAMESPACE_PATH']}",
      );
  foreach ($dirs as $dir) {
    echo "    {$dir}\n";
    mkdir($dir, 0755, true);
    touch("{$dir}/.gitkeep");
  }

  echo <<<END

Bootstrap done.

Check to see if you like the changes:
    git diff

If you want to try again:
    git checkout -- .
    php moar-start-project.php

When you're satisfied:
    rm -rf .git
    git init
    git add .
    git commit -m "Bootstrap."

Don't forget to edit README.md and composer.json to set descriptions.

When you are ready to publish:

    hub create {$repo}
    git remote add origin git@github.com:bd808/{$repo}.git
    git push -u origin master
    git branch --set-upstream-to origin/master

You should also:
  - Setup CI job at https://travis-ci.org/profile
  - Add the module to https://packagist.org/packages/submit
  - Link to the new module in https://github.com:bd808/moar/README.md

END;
} //end main


if (!debug_backtrace()) {
  // this file is being called as the interpreter entry point.
  main(($argc > 1)? $argv[1]: basename(dirname(__FILE__)));
}
