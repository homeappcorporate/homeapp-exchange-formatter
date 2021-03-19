<?php

declare(strict_types=1);

namespace Homeapp\ExchangeBundle\DependencyInjection;

use Exception;
use InvalidArgumentException;
use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\Exception\LoaderLoadException;
use LogicException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException as ExceptionInvalidArgumentException;
use Symfony\Component\DependencyInjection\Exception\BadMethodCallException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use TypeError;

class ApiResponseExtension extends Extension
{
    /**
     * @throws InvalidArgumentException
     * @throws FileLocatorFileNotFoundException
     * @throws ExceptionInvalidArgumentException
     * @throws TypeError
     * @throws LoaderLoadException
     * @throws BadMethodCallException
     * @throws ServiceNotFoundException
     * @throws LogicException
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../Resources/config')
        );
        $loader->load('services.yml');
    }
}
