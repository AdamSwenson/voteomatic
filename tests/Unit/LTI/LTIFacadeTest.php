<?php


namespace App\LTI;

use App\LTI\ToolProvider\ToolProvider;
use Tests\TestCase;


class LTIFacadeTest extends TestCase
{

    public $object;

    public function setUp():void
    {
        parent::setUp();

    }


    /**
     *
     */
    public function testLTIFacade()
    {
        $this->markTestSkipped('unneeded');

        $prov = LTIFacade::toolProvider();
        $this->assertInstanceOf(ToolProvider::class, $prov, $prov);

    }
}
