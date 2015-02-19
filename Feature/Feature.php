<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Feature;

/**
 * Description of Feature
 * @author jfb
 */
class Feature
{
    const STATE_ENABLED = 0;
    const STATE_ENABLED_ALWAYS = 1;
    const STATE_DISABLED = 2;
    const STATE_DISABLED_HIDDEN = 4;
    const STATE_DISABLED_ALWAYS = 4;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $state;

    /**
     * @var array
     */
    protected $variations;

    /**
     * @var string
     */
    protected $description;

    /**
     * @param string $name
     * @param int    $state
     * @param array  $variations
     * @param string $description
     */
    public function __construct($name, $state, $variations, $description = null)
    {
        $this->name = $name;
        $this->state = $state;
        $this->variations = $variations;
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param $state
     *
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getVariations()
    {
        return $this->variations;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * It it enabled ?
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->state == self::STATE_ENABLED || $this->state == self::STATE_ENABLED_ALWAYS;

    }

    /**
     * Is it possible to change the state of a feature ?
     * @return boolean
     */
    public function canChange()
    {
        return $this->state == self::STATE_DISABLED || $this->state == self::STATE_DISABLED_HIDDEN || $this->state == self::STATE_ENABLED;
    }

    /**
     * Has the general population access to the feature ?
     * @return boolean
     */
    public function mayBeVisible()
    {
        return $this->state == self::STATE_ENABLED || $this->state == self::STATE_ENABLED_ALWAYS || $this->state == self::STATE_DISABLED;
    }
}
