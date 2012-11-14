<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Controller to enable/disable dynamically a feature
 *
 * @author jfb
 */
class FeatureController extends Controller
{

    public function enableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->enableForUser($feature);
        return $this->redirect($this->get('router')->generate($this->container->getParameter("socloz_feature_flag.base.redirect")));
    }
    
    public function disableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->disableForUser($feature);
        return $this->redirect($this->get('router')->generate($this->container->getParameter("socloz_feature_flag.base.redirect")));
    }
    
    public function statusAction($feature)
    {
        $enabled = $this->get('socloz_feature_flag.feature')->isEnabled($feature);
        return $this->render('SoclozFeatureFlagBundle:status:status.html.twig', array("status" => array($feature => ($enabled ? "features.status.enabled" : "features.status.disabled"))));
    }

}
