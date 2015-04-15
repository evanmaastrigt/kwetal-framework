<?php

namespace Framework\Config;

use Framework\Config\Exception\ConfigNotFoundException;
use Symfony\Component\Finder\Finder;
use Zend\Config\Config;
use Zend\Config\Writer\PhpArray;

class Configloader
{
    /** @var  @var string */
    protected $env;

    /** @var string */
    protected  $applicationRoot;

    public function __construct($env, $applicationRoot)
    {
        $this->env = $env;
        $this->applicationRoot = $applicationRoot;
    }

    public function load()
    {
        if ($this->configExists())
        {
            return;
        }

        $config = $this->merge(
            $this->getEnvironmentConfig('prod'),
            $this->getEnvironmentConfig($this->env)
        );

        $this->write($config);

    }

    /**
     * @return Boolean
     */
    protected function configExists()
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->applicationRoot . '/app/config')
            ->name('config.php');

        return count($finder) == 1;
    }

    protected function getEnvironmentConfig($environment)
    {
        $finder = new Finder();
        $finder
            ->files()
            ->in($this->applicationRoot . '/app/config')
            ->name(sprintf('config_%s.php', $environment));

        if (count($finder) === 0) {
            throw new ConfigNotFoundException(sprintf('config file for %s not found', $environment));
        }

        foreach ($finder as $file) {
            return new Config(include $file->getRealPath(), true);
        }
    }

    protected function write(Config $config)
    {
        $writer = new PhpArray();

        $writer->toFile($this->applicationRoot . '/app/config/config.php', $config);
    }

    protected function merge(Config $org, Config $override)
    {
        $org->merge($override);

        $org->setReadOnly();

        return $org;
    }
}
