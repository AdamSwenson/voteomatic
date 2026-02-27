<?php

namespace App\Models;

use App\Models\Vote;
use Illuminate\Support\Str;
use Tests\TestCase;


class VoteTest extends TestCase
{

    public function setUp():void
    {
        parent::setUp();
    }

    /** @test */
    public function addReceiptHash()
    {
//        $v = new Vote();
//        $v->addReceiptHash();
//        $this->assertIsString($v->reciept);
//        $this->assertEquals(60, Str::length($v->reciept));
    }

    /** @test */
    public function makeReceiptHash()
    {
//        $s=random_bytes(120);
//
//        $b= bcrypt($s);
//        $this->assertEquals(Str::length($b), Str::length($s));

        $re = Vote::makeReceiptHash();
        $this->assertIsString($re);
        $this->assertEquals(60, Str::length($re));
    }

    /** @test */
    public function is_abstention()
    {

    }
}
