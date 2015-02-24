<?php

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

use Socloz\FeatureFlagBundle\ABTesting\Splitter\Range\Range;

class Weight implements SplitterInterface
{
    /**
     * @var Range[][]
     */
    private $config = array();

    /**
     * @var array
     * eg: array('featureName' => array(
     *  'variationNameA' => 1,
     *  'variationNameB' => 1000,
     * ))
     */
    public function __construct(array $config = array())
    {
        $lowerBound = 0;
        $upperBound = 0;
        foreach ($config as $featureName => $variations) {
            $sumArray = array_sum($variations);
            foreach ($variations as $variationName => $percentage) {
                $upperBound += ( $percentage / $sumArray) * 100;
                $this->config[$featureName][$variationName] = new Range($lowerBound, $upperBound);
                $lowerBound = $upperBound + 1;
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function chooseVariation($variations, $featureName = null)
    {
        $generatedNumber = rand(0, 100);
        foreach ($this->config[$featureName] as $variation => $range) {
            dump($generatedNumber);
            if ($range->isBounded($generatedNumber)) {

                return $variation;
            }
        }
        throw new \Exception('Feature doesn\'t exist');

    }
}