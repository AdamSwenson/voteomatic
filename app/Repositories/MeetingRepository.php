<?php


namespace App\Repositories;


use App\Models\LTIConsumer;
use App\Models\Meeting;

class MeetingRepository
{


    /**
     * @var ILTIRepository|mixed
     */
    public $ltiRepo;

    public function __construct()
    {

        $this->ltiRepo = app()->make(ILTIRepository::class);
    }


    /**
     * Creates a new meeting along with the LTI
     * credentials used to access it
     * @param $consumerKey
     * @param array $attrs
     * @return mixed
     */
    public function createWithResourceLink( $consumerKey, $attrs=[]){
        $meeting = Meeting::create($attrs);

        $consumer = LTIConsumer::where('consumer_key', $consumerKey)->firstOrFail();

        $link = $this->ltiRepo->createResourceLinkEntry($consumer, $meeting,  $consumer->resourceLink->id, $meeting->name);

        return $meeting;

    }

}
