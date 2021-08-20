<?php


namespace App\Repositories;


class MotionTemplateRepository
{

    /**
     * Returns an array of template objects
     *      'name' => String used by the client to label a button
     *      'content' => String representing what the motion is about.
     *      'description' => Optional String describing what the motion does. Usually used by procedural motions.
     *      'requires' => Float The percentage of votes cast which the vote must exceed,
     *      'type' => String The type
     *      'amendable' => Boolean whether subject to any amendments
     *      'debatable' => Boolean whether debate is allowed. Usually used for procedural motions.
     *
     * @var array[]
     */
    static public $templates = [
        [
            'name' => 'Adjourn',
            'content' => "That the meeting be adjourned.",
            'description' => "Meeting comes to an end. This is amendable with respect
             to when the next meeting will be, if specified",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true
        ],


        [
            'name' =>
                'Committee of the Whole',
            'content' => "That the body convene as a committee of the whole with this body's Chair as its Chair ",
            'description' => "The formal deliberative process is suspended. The body
                 may work informally on an issue. No votes taken while in the committee of the whole
                are binding on the main body but they may be used to advise the main body on what to do.
                To communicate from the committee of the whole, the committee
                of the whole should vote to Rise and Report",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true

        ],

        [
            'name' => 'Previous Question (Call the Question)',
            'content' => "That the pending question be called for",
            'description' => "If this motion is approved, all debate ends on the pending motion and the body moves immediately to a vote on the pending motion.
                If this motion fails, debate continues on the pending motion",
            'requires' => 0.66,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false
        ],
        [
            'name' => 'Place on the Table',
            'content' => "That the pending motion be placed on the table",
            'description' => "All action on the motion is paused so the body can attend to
                other business. There is no scheduled time to resume action. Action
                will resume upon a majority vote to Take from the Table. That motion may
                be made whenever no main motion is pending",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false,
        ],

        [
            'name' => 'Take from the Table',
            'content' => "That the specified motion be taken from the table.",
            'description' => "If this motion passes, the tabled motion is resumed. The state of the motion is exactly
             the same as when it was tabled. This motion may be made whenever no main motion is pending",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false,
        ],


        [
            'name' => 'Recess',
            'content' => "That the body recess.",
            'description' => "We take a break. This can be qualified to say how long. The how long part is amendable.",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true
        ],

        [
            'name' => 'Reconsider (with notice)',
            'content' => "That the body reconsider the motion that ",
            'description' => "",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => false
        ],

        [
            'name' => 'Reconsider (without notice)',
            'content' => "That the body reconsider the motion that ",
            'description' => "",
            'requires' => 0.66,
            'type' => 'procedural-main',
            'amendable' => false
        ]
    ];


    static public $introTemplates = [

        ['name' => 'z1 RolePlayMain',
            'content' => "That tacos be declared the official food of the CSUN Faculty.",
            'requires' => 0.5,
            'amendable' => true
        ],
//        ['name' => 'z2 RolePlayAmendment1',
//            'content' => "That burritos be declared the official food of the CSUN Faculty.",
//            'requires' => 0.5,
//            'amendable' => true],
//
//        ['name' => 'z3 RolePlayAmendment2',
//            'content' => "That burritos and hamburgers be declared the official food of the CSUN Faculty.",
//            'requires' => .5,
//            'amendable' => false],


        ['name' => 'z4 RolePlayBadAmendment',
            'content' => "That burritos, hamburgers, and French fries be declared the official food of the CSUN Faculty.",
            'requires' => 0.5,
            'amendable' => true],


    ];

}
