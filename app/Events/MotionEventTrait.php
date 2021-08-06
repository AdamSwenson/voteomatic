<?php


namespace App\Events;


trait MotionEventTrait
{

    public function chairChannelName(){
        return 'chair.'.$this->meeting->id;
    }

    public function meetingChannelName(){
        return 'meeting.'.$this->meeting->id;
    }

    public function motionChannelName(){
        return 'motions.'.$this->motion->id;
    }


}
