<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\State;

/**
 * Description of StateInterface
 * @author jfb
 */
interface StateInterface
{
    public function setFeatureEnabled($feature, $enabled);

    public function getFeatureEnabled($feature);

    public function setFeatureVariation($feature, $variation, $suffix = null);

    public function getFeatureVariation($feature, $suffix = null);
}
