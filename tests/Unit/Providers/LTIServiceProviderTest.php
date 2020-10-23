<?php


namespace App\Providers;


use App\LTI\LTI;
//use App\LTI\ToolProvider\ToolProvider;
use App\LTI\ToolProvider\ToolProvider;
use Tests\TestCase;

class LTIServiceProviderTest extends TestCase
{

    protected $object;

    public function setUp(): void
    {
        parent::setUp();
    }


    public function testServiceProviderReturnsExpectedObject()
    {
        $lti = app()->make(LTI::class);
        $this->assertInstanceOf(LTI::class, $lti, "returned correct object");

        $this->assertInstanceOf(ToolProvider::class, $lti->toolProvider(), "access to tool provider");
    }

}
