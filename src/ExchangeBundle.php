<?php

namespace Homeapp\ExchangeBundle;

use Homeapp\ExchangeBundle\DependencyInjection\ApiResponseExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExchangeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ApiResponseExtension();
    }
}
