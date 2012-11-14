<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting;

/**
 * Description of ABTestingService
 *
 * @author jfb
 */
class ABTestingService
{
    
    protected $features;
    protected $state;
    protected $splitter;
    protected $transaction;

    /**
     * @param \Socloz\FeatureFlagBundle\Feature\FeatureService $features
     * @param \Socloz\FeatureFlagBundle\State\StateInterface $state
     * @param \Socloz\FeatureFlagBundle\ABTesting\Splitter\SplitterInterface $splitter
     * @param \Socloz\FeatureFlagBundle\ABTesting\Transaction\TransactionInterface $transaction
     */
    public function __construct($features, $state, $splitter, $transaction)
    {
        $this->features = $features;
        $this->state = $state;
        $this->splitter = $splitter;
        $this->transaction = $transaction;
    }
    
    /**
     * Returns the feature variation for the current user
     * 
     * @param string $feature
     * @return boolean
     */
    public function getFeatureVariation($feature)
    {
        $f = $this->features->getFeature($feature);
        if ($f == null || count($f->getVariations()) == 0) return null;

        if ($this->state) {
            $variation = $this->state->getFeatureVariation($feature);
            if ($variation !== null) {
                return $variation;
            }
        }
        $variation = $this->splitter->chooseVariation($f->getVariations());
        if ($this->state) {
            $this->state->setFeatureVariation($feature, $variation);
        }
        return $variation;
    }

    /**
     * Sets the feature variation for the current user
     * 
     * @param string $feature
     * @param string $variation
     * @return boolean
     */
    public function setFeatureVariation($feature, $variation)
    {
        $f = $this->features->getFeature($feature);
        if ($f == null) return false;

        $variations = $f->getVariations();
        if (in_array($variation, $variations) && $this->state) {
            $this->state->setFeatureVariation($feature, $variation);
        }
    }
    
    /**
     * Starts a transaction
     * 
     * @param string $feature
     */
    public function begin($feature)
    {
        $this->transaction->begin($feature);
    }

    /**
     * Ends a successful transaction
     * 
     * @param string $feature
     */
    public function success($feature)
    {
        $this->transaction->success($feature);
    }

    /**
     * Ends a failed transaction
     * 
     * @param string $feature
     */
    public function failure($feature)
    {
        $this->transaction->failure($feature);
    }
    
    /**
     * Returns the counts
     * 
     * @return array
     */
    public function getCounts() {
        
    }
}
