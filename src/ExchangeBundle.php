<?php

namespace Homeapp\Exchange;

use Homeapp\Exchange\DependencyInjection\ApiResponseExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class ExchangeBundle extends Bundle
{
    public function getContainerExtension()
    {
        return new ApiResponseExtension();
    }
}
