<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

//use PHPUnit\Framework\TestCase;

abstract class TestCase extends BaseTestCase

//abstract class TestCase extends TestCase
{
    use CreatesApplication;

    //Mainly using so don't get false failures when
    //faker runs out of, e.g., email addresses
    //Also speeds up a bit
    use RefreshDatabase;

    /**
     * The object under test
     * @var
     */
    public $object;

    /**
     * @var \Faker\Generator
     */
    public $faker;


    public $election;

    public $meeting;
    public $motion;
    public $office;
    public $owner;

    /**
     * @var string
     */
    public $url;

    public $user;

    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     */
    public $regularUserMember;


    public function setUp():void
    {
        parent::setUp();

        $this->faker = Factory::create();


    }

    public function tearDown(): void
    {
        parent::tearDown();

    }

}
