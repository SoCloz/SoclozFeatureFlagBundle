<?php

/*
 * Copyright CloseToMe 2011/2012
 */

namespace Socloz\FeatureFlagBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller to switch to a specific variation for a feature
 * @author jfb
 */
class ABController extends Controller
{

    /**
     * @param $feature
     * @param $variation
     *
     * @return RedirectResponse
     */
    public function changeVariationAction($feature, $variation)
    {
        $this->get('socloz_feature_flag.abtesting')->setFeatureVariation($feature, $variation);
        return $this->redirect(
            $this->get('router')->generate($this->container->getParameter("socloz_feature_flag.base.redirect"))
        );
    }

    /**
     * @param $feature
     *
     * @return Response
     */
    public function statusAction($feature)
    {
        $variation = $this->get('socloz_feature_flag.abtesting')->getFeatureVariation($feature);
        return $this->render('SoclozFeatureFlagBundle:status:status.html.twig', array(
            "status" => array($feature => ($variation ? $variation : "features.status.none"))
        ));
    }
}
