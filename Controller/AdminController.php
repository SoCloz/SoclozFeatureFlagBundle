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
class AdminController extends Controller
{

    /**
     * @return Response
     */
    public function viewFeaturesAction()
    {
        $service = $this->get('socloz_feature_flag.feature');
        return $this->render("SoclozFeatureFlagBundle:admin:editFeatures.html.twig", array("feature_service" => $service));
    }

    /**
     * @param $feature
     *
     * @return RedirectResponse
     */
    public function enableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->enable($feature);
        return $this->redirect($this->get('router')->generate('feature_flag_admin_view_features'));
    }

    /**
     * @param $feature
     *
     * @return RedirectResponse
     */
    public function disableFeatureAction($feature)
    {
        $this->get('socloz_feature_flag.feature')->disable($feature);
        return $this->redirect($this->get('router')->generate('feature_flag_admin_view_features'));
    }
}
