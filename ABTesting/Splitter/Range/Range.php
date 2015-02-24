<?php

namespace Socloz\FeatureFlagBundle\ABTesting\Splitter\Range;

class Range
{
    /**
     * @var int
     */
    protected $min;

    /**
     * @var int
     */
    protected $max;

    /**
     * @param int $min
     * @param int $max
     */
    function __construct($min, $max)
    {
        $this->min = $min;
        $this->max = $max;
    }

    /**
     * @return int
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * @param int $min
     */
    public function setMin($min)
    {
        $this->min = $min;
    }

    /**
     * @return int
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * @param int $max
     */
    public function setMax($max)
    {
        $this->max = $max;
    }

    /**
     * @param $number
     *
     * @return bool
     */
    public function isBounded($number)
    {
        if ($number >= $this->min && $number <= $this->max) {
            return true;
        }
        return false;
    }
}