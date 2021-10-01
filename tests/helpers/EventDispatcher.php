<?php

namespace Tests\helpers;

class EventDispatcher
{

    public function dispatchEvent($event, $params){
        $event::dispatch($params);
    }
}
