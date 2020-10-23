<?php

namespace App\LTI\ToolProvider;


use App\LTI\LTI;

/**
 * Class ToolBase
 *
 * Copied from RobertBoes\LaravelLti\ToolProvider so can customize
 *
 */
class ToolBase
{
    /**
     * @var LTI
     */
    private $lti;

    /**
     * ToolBase constructor.
     * @param LTI $lti
     */
    public function __construct(LTI $lti)
    {
        $this->lti = $lti;
    }

    /**
     * Get the DataConnector
     * @return \IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector
     */
    protected function dataConnector() {
        return $this->lti->getDataConnector();
    }
}