<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Transaction;

/**
 * Interface for transaction management
 *
 * @author jfb
 */
interface TransactionInterface
{

    /**
     * Starts a transaction
     * 
     * @param string $feature
     * @param string $variation
     */
    public function begin($feature);

    /**
     * Ends a successful transaction
     * 
     * @param string $feature
     * @param string $variation
     */
    public function success($feature);

    /**
     * Ends a failed transaction
     * 
     * @param string $feature
     * @param string $variation
     */
    public function failure($feature);
    
    /**
     * Returns the counts
     * 
     * @return array
     */
    public function getCounts();
}
