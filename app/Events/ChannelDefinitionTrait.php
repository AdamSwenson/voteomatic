<?php


namespace App\Events;


trait ChannelDefinitionTrait
{

    public function chairChannelName(){
//        return 'chair.'.$this->meeting_id;
        return 'chair.'.$this->meeting->id;
    }

    public function meetingChannelName(){
//        return 'meeting.'.$this->meeting_id;
        return 'meeting.'.$this->meeting->id;
    }

    public function motionChannelName(){
//        return 'motions.'.$this->motion_id;
        return 'motions.'.$this->motion->id;
    }


    /**
     * Where we do not need to send the whole motion object,
     * this will send the value stored on motionId
     * @return string
     */
    public function motionChannelNameFromId(){
//        return 'motions.'.$this->motion_id;
        return 'motions.'.$this->motionId;
    }


}
