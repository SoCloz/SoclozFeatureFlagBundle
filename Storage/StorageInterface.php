<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Storage;

/**
 * Description of StorageInterface
 * @author jfb
 */
interface StorageInterface
{

    public function setFeatureEnabled($feature, $state);

    public function getFeatureEnabled($feature);

}
