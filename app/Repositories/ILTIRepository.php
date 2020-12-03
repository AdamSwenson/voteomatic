<?php

namespace App\Repositories;


use App\Http\Requests\LTIRequest;
use App\Models\LTIConsumer;
use App\Models\Meeting;
use App\Models\ResourceLink;

/**
 * Class LTIRepository
 *
 * Utilities and access to LTI credentials and
 * settings
 *
 * @package App\Repositories
 */
interface ILTIRepository
{
    /**
     * Creates the credentials and database entry for a new
     * canvas/other lti instance  which will connect to the voteomatic
     *
     * @param $name
     * @return LTIConsumer
     */
    public function createLTIConsumer($name);

    /**
     * When there is an LTI launch request, this handles
     * getting the resource link object.
     *
     * Since each new assignment in Canvas will have a unique
     * resource link id, we will have to catch the incoming id
     * the first time we see the meeting id in a request. Thereafter,
     * we can just look it up.
     *
     * @param LTIRequest $request
     * @param Meeting $meeting
     * @return ResourceLink
     */
    public function getResourceLinkFromRequest(LTIRequest $request, Meeting $meeting);


    /**
     * When we get the resource link for our new assignment,
     * we add it to the database.
     *
     * @param LTIConsumer $consumer
     * @param Meeting $meeting
     * @param $resourceLinkId
     * @param null $description
     * @return ResourceLink
     */
    public function createResourceLinkEntry(LTIConsumer $consumer, Meeting $meeting, $resourceLinkId, $description = null);
  }
