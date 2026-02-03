<?php


namespace App\Events;


trait ChannelDefinitionTrait
{

    public function chairChannelName(){
        return 'chair.'.$this->meeting->id;
    }

    public function meetingChannelName(){
        return 'meeting.'.$this->meeting->id;
    }

//    /**
//     * Where we do not need to send the meeting object
//     * returns the value stored on meetingId
//     * dev This can be deprecated once meetings are moved to protected props
//     * @return string
//     */
//    public function meetingChannelNameFromId(){
//        return 'meeting.'.$this->meetingId;
//    }

    public function motionChannelName(){
        return 'motions.'.$this->motion->id;
    }


//    /**
//     * Where we do not need to send the whole motion object,
//     * this will send the value stored on motionId
//     * @return string
//     */
//    public function motionChannelNameFromId(){
//        return 'motions.'.$this->motionId;
//    }


}
