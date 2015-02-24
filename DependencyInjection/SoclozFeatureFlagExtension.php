<?php

namespace Socloz\FeatureFlagBundle\DependencyInjection;

use Socloz\FeatureFlagBundle\Feature\Feature;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class SoclozFeatureFlagExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        if (isset($config['options'])) {
            foreach ($config['options'] as $key => $subconfig) {
                foreach ($subconfig as $subkey => $value) {
                    $container->setParameter($this->getAlias().'.'.$key.'.'.$subkey, $value);
                }
            }
        }

        if (isset($config['features'])) {
            foreach ($config['features'] as $name => &$featureConfig) {
                switch ($featureConfig['state']) {
                    case 'enabled':
                        $featureConfig['state'] = Feature::STATE_ENABLED;
                        break;
                    case 'enabled-always':
                        $featureConfig['state'] = Feature::STATE_ENABLED_ALWAYS;
                        break;
                    case 'disabled':
                        $featureConfig['state'] = Feature::STATE_DISABLED;
                        break;
                    case 'disabled-hidden':
                        $featureConfig['state'] = Feature::STATE_DISABLED_HIDDEN;
                        break;
                    case 'disabled-always':
                        $featureConfig['state'] = Feature::STATE_DISABLED_ALWAYS;
                        break;
                }
                $container->setParameter($this->getAlias().'.feature_flag.features', $config['features']);
            }
        }
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load("services.xml");

        $container->setDefinition('socloz_feature_flag.storage', new DefinitionDecorator($config['services']['storage']));
        $container->setDefinition('socloz_feature_flag.splitter', new DefinitionDecorator($config['services']['splitter']));
        $container->setDefinition('socloz_feature_flag.transaction', new DefinitionDecorator($config['services']['transaction']));
        $container->setDefinition('socloz_feature_flag.analytics', new DefinitionDecorator($config['services']['analytics']));
        $container->getDefinition('socloz_feature_flag.state')->replaceArgument(1, $config['services']['state']);

        $percentages = $this->assignPercentage($config['features'], $config['services']['percentage_feature']);
        $container->getDefinition('socloz_feature_flag.splitter.percentage')->replaceArgument(0, $percentages);
    }

    private function assignPercentage($features, $percentages)
    {
        foreach ($features as $featureName => $feature) {
            if (!isset($percentages[$featureName])) {
                $upperLimit = 100;
                $lowerLimit = 100;
                if (count($feature['variations']) === 0) {
                    continue;
                }
                $val = 100 / count($feature['variations']);
                foreach ($feature['variations'] as $variation) {
                    $lowerLimit -= $val;
                    $percentages[$featureName][$variation] = array('upper_bound' => $lowerLimit, 'lower_bound' => $upperLimit);
                    $upperLimit-= $val + 1;
                }
            }
        }
        return $percentages;
    }

    /**
     * {@inheritDoc}
     */
    public function getAlias()
    {
        return 'socloz_feature_flag';
    }

}
