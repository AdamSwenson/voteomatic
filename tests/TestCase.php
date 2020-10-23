<?php

namespace Tests;

use Faker\Factory;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

//use PHPUnit\Framework\TestCase;

abstract class TestCase extends BaseTestCase

//abstract class TestCase extends TestCase
{
    use CreatesApplication;

    /**
     * @var \Faker\Generator
     */
    public $faker;

    public function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();


    }

}
