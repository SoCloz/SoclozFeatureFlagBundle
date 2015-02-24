<?php

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

use Socloz\FeatureFlagBundle\ABTesting\Splitter\Range\Range;

class Percentage implements SplitterInterface
{
    /**
     * @var Range[][]
     */
    private $config = array();

    /**
     * @var array
     * eg: array('featureName' => array(
     *  'variationNameA' => array('upper_bound' => 0, 'lower_bound' => 70),
     *  'variationNameB' => array('upper_bound' => 71, 'lower_bound' => 100),
     * ))
     */
    public function __construct(array $config = array())
    {
        foreach ($config as $featureName => $variations) {
            foreach ($variations as $variationName => $percentage) {
                $this->config[$featureName][$variationName] = new Range($percentage['lower_bound'], $percentage['upper_bound']);
            }
        }
    }

    /**
     * @param string  $featureName
     * @param Range[] $percentages
     *
     * @throws \Exception
     */
    public function replaceFeaturePercentages($featureName, array $percentages = array())
    {
        if (isset($this->config[$featureName])) {
            $this->config[$featureName] = $percentages;
            return;
        }
        throw new \Exception('Feature doesn\'t exist');
    }

    /**
     * {@inheritDoc}
     */
    public function chooseVariation($variations, $featureName = null)
    {
        $generatedNumber = rand(0, 100);
        foreach ($this->config[$featureName] as $variation => $percentage) {
            if ($percentage->isBounded($generatedNumber)) {
                return $variation;
            }
        }
        throw new \Exception('Bad percentage limit for variations of feature');
    }
}