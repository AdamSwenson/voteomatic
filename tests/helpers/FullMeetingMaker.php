<?php


namespace Tests\helpers;


use App\Models\Meeting;

class FullMeetingMaker
{

    public static $tree = [
        [
            'content' => "RESOLVED That the dog be given tacos",
            'type' => 'main',
//        'passed' => true
        ],
        [
            'content' => "RESOLVED That the dog be given all tacos",
            'type' => 'amendment-primary',
//            'passed' => true
        ],
        [
            'content' => "RESOLVED That the dog be given no tacos",
            'type' => 'amendment-secondary',
//            'passed' => false
        ],
        [
            'content' => "That the pending motion be tabled",
            'type' => 'procedural',
//            'passed' => true
        ],
    ];

    public function make(Meeting $meeting)
    {
        $meeting->resetAssignments();

        //passes
        $mainMotion1 = Motion::create([
            'content' => "RESOLVED That the dog be given tacos",
            'type' => 'main',
            'requires' => 0.5,
        ]);

        //passes
        $amendment1 = Motion::create(
            [
                'content' => "RESOLVED That the dog be given all tacos",
                'type' => 'amendment-primary',
                'requires' => 0.5,
                'applies_to' => $mainMotion1->id
            ]);

        //fails
        $amendment1a = Motion::create([
                'content' => "RESOLVED That the dog be given no tacos",
                'type' => 'amendment-secondary',
                'requires' => 0.5,
                'applies_to' => $amendment1->id
            ]);


        $procedural1 = Motion::create([
            'content' => "That the pending motion be tabled",
            'type' => 'procedural',
            'requires' => 0.5,
            'applies_to' => $amendment1a->id
        ]);

        //another motion needs to be dealt with first.

        $mainMotion2 = Motion::create([
            'content' => "RESOLVED That Senators be given tacos",
            'type' => 'main',
            'requires' => 0.5,
        ]);

        //passes
        $amendment2 = Motion::create([
            'content' => "RESOLVED That Senators be given tacos everyday",
            'type' => 'amendment-primary',
            'requires' => 0.5,
            'applies_to' => $mainMotion2->id
        ]);

        //fails
        $amendment2a = Motion::create([
            'content' => "RESOLVED That Senators, except Adam, be given tacos everyday",
            'type' => 'amendment-secondary',
            'requires' => 0.5,
            'applies_to' => $amendment2->id
        ]);

        //Now that the senators have tacos, back to the dog

        $procedural2 = Motion::create(
            [
                'content' => "Take from the table",
                'type' => 'procedural',
                'requires' => 0.66,
                'applies_to' => $amendment1a->id
            ]);

        $amendment1b = Motion::create(
            [
                'content' => "RESOLVED That the dog be given all tacos everyday",
                'type' => 'amendment-primary',
                'requires' => 0.5,
                'applies_to' => $mainMotion1->id
            ]);

        $procedural2 = Motion::create(
            [
                'content' => "Call for the question",
                'type' => 'procedural',
                'requires' => 0.66,
                'applies_to' => $amendment1a->id
            ]);







    }

}
