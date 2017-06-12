<?php

namespace AppBundle\Service\Strategy;

/**
 * Interface StrategyInterface
 * @package AppBundle\Form\Strategy
 */
interface FormStrategyInterface {

    /**
     * @return string
     */
    public function getFormType();

    /**
     * @return string
     */
    public function getTwigTemplate();

    /**
     * @param $plan
     * @return $plan
     */
    public function handleSpecificFields($plan);

}