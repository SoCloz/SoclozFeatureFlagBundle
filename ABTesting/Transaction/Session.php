<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\ABTesting\Transaction;

use Symfony\Component\HttpFoundation\Session\Session as HttpFoundationSession;

/**
 * Description of Simple
 * @author jfb
 */
class Session implements TransactionInterface
{

    const STATE_STARTED = 0;
    const STATE_FAILURE = 1;
    const STATE_SUCCESS = 3;

    /**
     * @var HttpFoundationSession
     */
    protected $session;

    /**
     * @var int
     */
    protected $started = 0;

    /**
     * @var int
     */
    protected $failure = 0;

    /**
     * @var int
     */
    protected $aborted = 0;

    /**
     * @var int
     */
    protected $success = 0;

    /**
     * @var int
     */
    protected $successOnFailure = 0;

    /**
     * @param HttpFoundationSession $session
     */
    public function __construct($session)
    {
        $this->session = $session;
    }

    /**
     * Returns the session key
     *
     * @param string $feature
     *
     * @return string
     */
    private function getSessionKey($feature)
    {
        return "socloz_feature_flag.transaction.$feature";
    }

    /**
     * {@inheritDoc}
     */
    public function begin($feature)
    {
        $key = $this->getSessionKey($feature);
        if ($this->session->get($key) == self::STATE_STARTED) {
            $this->aborted++;
        }
        $this->session->set($key, self::STATE_STARTED);
        $this->started++;
    }

    /**
     * {@inheritDoc}
     */
    public function success($feature)
    {
        $key = $this->getSessionKey($feature);
        $state = $this->session->get($key);
        switch ($state) {
            case self::STATE_SUCCESS:
                return;
            case self::STATE_FAILURE:
                $this->successOnFailure++;
                break;
            default:
                $this->success++;
        }
        $this->session->set($key, self::STATE_SUCCESS);
    }

    /**
     * {@inheritDoc}
     */
    public function failure($feature)
    {
        $key = $this->getSessionKey($feature);
        if ($this->session->get($key) == self::STATE_STARTED) {
            $this->failure++;
            $this->session->set($key, self::STATE_FAILURE);
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getCounts()
    {
        return array(
            "started" => $this->started,
            "success" => $this->success,
            "failure" => $this->failure,
            "successOnFailure" => $this->successOnFailure
        );
    }
}
