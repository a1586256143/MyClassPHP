#!/usr/bin/php
<?php
if (PHP_SAPI !== 'cli') {
    exit('Please run in CLI mode');
}
require_once 'index.php';
$commands = new system\Command\Command($argv);
echo $commands::exec($argv);