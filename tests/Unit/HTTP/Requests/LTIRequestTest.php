<?php

namespace App\Http\Requests;

use Tests\TestCase;

use Tests\helpers\LTIPayloadMaker;

class LTIRequestTest extends TestCase
{

    public $object;

    public function setUp():void
    {
        parent::setUp();
        $this->object = new LTIRequest();
    }

    /** @test */
    public function get_signable_parameters()
    {
        //prep
        $payload = LTIPayloadMaker::makePayload();

        foreach ($payload as $k => $v) {
            $this->object[$k] = $v;
        }

        //call
        $result = $this->object->get_signable_parameters();

        //check

        foreach ($payload as $k => $v) {
            if ($k !== 'oauth_signature') {
                $this->assertStringContainsString($k, $result, "Result contains key $k");
            }
        }

        //we have to do it by checking for the value since
        //the expected presence of oauth_signature_method will
        //prevent us from searching the value
        $this->assertStringNotContainsString($payload['oauth_signature'], $result, "check that oauth_signature is removed");


    }

    /** @test */
    public function get_signature_base_string()
    {
        $this->markTestSkipped();
    }

    /** @test */
    public function get_normalized_http_url()
    {
        $this->markTestSkipped();
    }

}
