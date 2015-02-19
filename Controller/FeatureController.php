<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to enable/disable dynamically a feature
 * @author jfb
 */
class FeatureController extends Controller
{

    /**
     * @param $feature
     *
     * @return RedirectResponse
     */
    public function enableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->enableForUser($feature);
        return $this->redirect($this->get('router')->generate($this->container->getParameter("socloz_feature_flag.base.redirect")));
    }

    /**
     * @param $feature
     *
     * @return RedirectResponse
     */
    public function disableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->disableForUser($feature);
        return $this->redirect($this->get('router')->generate($this->container->getParameter("socloz_feature_flag.base.redirect")));
    }

    /**
     * @param $feature
     *
     * @return Response
     */
    public function statusAction($feature)
    {
        $enabled = $this->get('socloz_feature_flag.feature')->isEnabled($feature);
        return $this->render('SoclozFeatureFlagBundle:status:status.html.twig', array(
            "status" => array($feature => ($enabled ? "features.status.enabled" : "features.status.disabled"))
        ));
    }
}
