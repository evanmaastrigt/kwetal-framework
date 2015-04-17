<?php

namespace Framework\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigBuildCommand extends Command
{
    protected function configure()
    {
        $this->setName('config:build')
            ->setDescription('Build the app/config/config.php')
            ->setHelp('Build the app/config/config.php file for your environment');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>Did nothing</info>',
        ]);
    }
}
