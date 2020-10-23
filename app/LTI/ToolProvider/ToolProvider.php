<?php

namespace App\LTI\ToolProvider;

//use IMSGlobal\LTI\ToolProvider\ToolProvider as LTIToolProvider;
use App\LTI\LTI;
use App\LTI\Exceptions\ToolProviderNotSetException;

class ToolProvider extends ToolBase
{
    /**
     * @var \RobertBoes\LaravelLti\ToolProvider\ToolProviderBase
     */
    private $provider;

    public function __construct(LTI $lti)
    {
        parent::__construct($lti);
        $this->provider = new ToolProviderBase($this->dataConnector());
    }

    private function checkToolProvider()
    {
        if (!($this->provider instanceof ToolProviderBase)) {
            throw new ToolProviderNotSetException();
        }
    }

    public function handleRequest() {
        $this->checkToolProvider();
        $this->provider->handleRequest();
    }

    public function getToolProvider() {
        return $this->provider;
    }
}
