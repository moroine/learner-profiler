<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{

    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Pfe\Bundle\MoocLoginBundle\PfeMoocLoginBundle(),
            new Pfe\Bundle\GPlaceApiBundle\PfeGPlaceApiBundle(),
            new Sensio\Bundle\BuzzBundle\SensioBuzzBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Pfe\Bundle\CollectorBundle\PfeCollectorBundle(),
            new Pfe\Bundle\CoreBundle\PfeCoreBundle(),
            new Pfe\Bundle\ProviderBundle\PfeProviderBundle(),
            new Pfe\Bundle\GeonamesApiBundle\PfeGeonamesApiBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new Pfe\Bundle\AnalyserBundle\PfeAnalyserBundle(),
            new Pfe\Bundle\FreeGeoIpBundle\PfeFreeGeoIpBundle(),
            new Pfe\Bundle\BuilderBundle\PfeBuilderBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    public function getCharset()
    {
        return 'UTF-8';
    }

}
