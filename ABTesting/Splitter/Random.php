<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

/**
 * Chooses a random variation
 * @author jfb
 */
class Random implements SplitterInterface
{
    /**
     * {@inheritDoc}
     */
    public function chooseVariation($variations)
    {
        return $variations[mt_rand(0, count($variations) - 1)];
    }
}
