<?php

namespace App\Repositories;


use App\Models\LTIConsumer;
use App\Models\Meeting;

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
     */
    public function createLTIConsumer($name);

    /**
     * @param LTIConsumer $consumer
     * @param Meeting $meeting
     * @param null $description
     * @return
     */
    public function createResourceLink(LTIConsumer $consumer, Meeting $meeting, $description = null);
}
