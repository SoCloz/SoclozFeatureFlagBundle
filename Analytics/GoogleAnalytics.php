<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Analytics;

use Socloz\FeatureFlagBundle\State\StateInterface;

/**
 * GoogleAnalytics tracking code
 * The function adds a session-level variable to the page.
 * A single variable is used => it is required that :
 * - all features have the same variations
 * - the shared random splitter is used
 * @author jfb
 */
class GoogleAnalytics implements AnalyticsInterface
{

    /**
     * @var StateInterface
     */
    protected $state;

    /**
     * @var string
     */
    protected $variableId;

    /**
     * @var string
     */
    protected $variableName;

    /**
     * @param StateInterface $state
     * @param string         $variableId
     * @param string         $variableName
     */
    public function __construct($state, $variableId, $variableName)
    {
        $this->state = $state;
        $this->variableId = $variableId;
        $this->variableName = $variableName;
    }

    /**
     * Returns the tracking code for A/B tests
     *
     * @param string $feature
     *
     * @return string
     */
    public function getTrackingCode($feature)
    {
        if ($this->state) {
            $variation = $this->state->getFeatureVariation($feature);
            return <<< EOF
<script type="text/javascript">
    var _gaq = _gaq || [];
    _gaq.push(['_setCustomVar', $this->variableId, '$this->variableName', '$variation', 2]);
</script>
EOF;
        }
    }
}
