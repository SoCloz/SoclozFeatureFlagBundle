<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter;

/**
 * Interface for variation selecting splitters
 *
 * @author jfb
 */
interface SplitterInterface
{

    /**
     * Chooses a variation
     * 
     * @param array $variations - list of variations
     * @return string $variation
     */
    public function chooseVariation($variations);
}
