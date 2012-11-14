<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Analytics;

/**
 * Interface for all analytics classes
 *
 * @author jfb
 */
interface AnalyticsInterface
{

    public function getTrackingCode($feature);

}
