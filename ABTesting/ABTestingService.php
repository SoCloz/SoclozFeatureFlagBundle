<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting;

use Socloz\FeatureFlagBundle\ABTesting\Splitter\SplitterInterface;
use Socloz\FeatureFlagBundle\ABTesting\Transaction\TransactionInterface;
use Socloz\FeatureFlagBundle\Feature\FeatureService;
use Socloz\FeatureFlagBundle\State\StateInterface;

/**
 * Description of ABTestingService
 * @author jfb
 */
class ABTestingService
{

    /**
     * @var FeatureService
     */
    protected $features;

    /**
     * @var StateInterface
     */
    protected $state;

    /**
     * @var SplitterInterface
     */
    protected $splitter;

    /**
     * @var TransactionInterface
     */
    protected $transaction;

    /**
     * @param FeatureService       $features
     * @param StateInterface       $state
     * @param SplitterInterface    $splitter
     * @param TransactionInterface $transaction
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
     *
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
        $variation = $this->splitter->chooseVariation($f->getVariations(), $feature);
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
     *
     * @return boolean
     */
    public function setFeatureVariation($feature, $variation)
    {
        $f = $this->features->getFeature($feature);
        if ($f == null) {
            return false;
        }

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
     * @return array
     */
    public function getCounts()
    {

    }
}
