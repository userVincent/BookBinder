<?php

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use DAMA\DoctrineTestBundle\DAMADoctrineTestBundle;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;
    public function configureBundles(): iterable
    {
        $bundles = [
            // ...
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            if ($this->getEnvironment() === 'test') {
                $bundles[] = new DAMADoctrineTestBundle();
            }
        }

        return $bundles;
    }
}
