<?php

namespace App\Exceptions;


class ExcessCandidatesSelected extends ClientVisibleException
{

    const ERROR_CODE = 521;

    const MESSAGE = "Too many candidates selected for office";

    const MESSAGE_STYLE = 'danger';

    const DISPLAY_TIME = 5000;

    const BLOCKING_MESSAGE = true;
    //
}
