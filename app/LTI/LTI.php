<?php

namespace App\LTI;

use App\LTI\ToolProvider\ToolConsumer;
use App\LTI\ToolProvider\ToolProvider;

use Illuminate\Support\Facades\DB;
use IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector;


/**
 * Class LTI
 * Copied from RobertBoes\LaravelLti\ToolProvider so can customize
 * @package App\LTI
 */
class LTI
{
    /**
     * @var \IMSGlobal\LTI\ToolProvider\DataConnector\DataConnector
     */
    protected $data_connector;

    /**
     * @var \App\LTI\ToolProvider\ToolProvider
     */
    private $toolProvider = null;

    /**
     * @var \App\LTI\ToolProvider\ToolConsumer
     */
    private $toolConsumer = null;

    public function __construct()
    {
        //renamed config path so won't collide while getting rid of huddledigital
        $db = DB::connection(config('laravelLti.database.connection'))->getPdo();
        $this->data_connector = DataConnector::getDataConnector(config('laravelLti.database.prefix'), $db, 'pdo');
    }

    public function getDataConnector() {
        return $this->data_connector;
    }

    /**
     * @return \App\LTI\ToolProvider\ToolProvider
     */
    public function toolProvider()
    {
        if($this->toolProvider instanceof ToolProvider) {
            return $this->toolProvider;
        }
        return $this->toolProvider = (new ToolProvider($this));
    }

    /**
     * @return \App\LTI\ToolConsumer
     */
    public function toolConsumer()
    {
        if($this->toolConsumer instanceof ToolConsumer) {
            return $this->toolConsumer;
        }
        return $this->toolConsumer = (new ToolConsumer($this));
    }


}
