<?php

namespace App\Models;

use App\Models\Vote;
use Illuminate\Support\Str;
use Tests\TestCase;


class VoteTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function addReceiptHash()
    {
        $v = new Vote();
        $this->assertNull($v->receipt, "No receipt on creation");

        //call
        $v->addReceiptHash();

        //check
        $this->assertIsString($v->receipt);
    }

    /** @test */
    public function makeReceiptHash()
    {
        //call
        $re = Vote::makeReceiptHash();
        $this->assertIsString($re);
        print($re);

        //check
        $chunks = Vote::RECEIPT_LENGTH / Vote::RECEIPT_CHUNK;
        $expected = Vote::RECEIPT_LENGTH + ($chunks - 1);
        $this->assertEquals($expected, Str::length($re), "Hash expected length");
    }


    /** @test */
    public function proveProblemWithRandomBytes()
    {
        //Shows that bcrypt will get a null byte and fail
        $this->expectException(\ValueError::class);
        for ($i = 0; $i < 20; $i++) {

            $seed = random_bytes(120);
            $this->assertEquals(60, Str::length(bcrypt($seed)));
        }
    }

    /** @test */
    public function is_abstentionIsTrue()
    {
        $v = new Vote();
        $this->assertTrue($v->is_abstention());
    }

    /** @test */
    public function is_abstentionIsFalse()
    {
        $v = new Vote();
        $v->is_yay = True;
        $this->assertFalse($v->is_abstention(), "Not abstention when vote is true");

        $v->is_yay = False;
        $this->assertFalse($v->is_abstention(), "Not abstention when vote is false");
    }
}
