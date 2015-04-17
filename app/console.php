<?php

//chdir(dirname(__DIR__));

$app = require 'app/bootstrap.php';

use Framework\Command;
use Symfony\Component\Console\Application AS Console;

$console = new Console('KLF', '1.0');

$commands[] = new Command\ConfigBuildCommand();

$console->addCommands($commands);

$console->run();
