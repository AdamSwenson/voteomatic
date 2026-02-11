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
            'content' => "Shall the meeting be adjourned.",
            'description' => "Immediately ends the meeting.",
            'requires' => 0.5,
            'type' => 'privileged',
            'amendable' => false,
            'debatable' => false,
            'reconsiderable' => [
                'affirmative' => false,
                'negative' => true,
            ]
        ],

        [
            'name' => 'Appeal',
            'content' => "Shall the ruling of the Chair be sustained",
            'description' => "A nay vote overturns the ruling of the Chair. An aye vote sustains the ruling of the Chair.
            Debate on this motion must be on the Chair's ruling. Debate cannot involve the wisdom of the underlying motion.",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => false,
            'debatable' => true,
//            'debatable' => [
//                'motion' => true,
//                'underlyingMotion' => false
//            ],
            'reconsiderable' => [
                'affirmative' => true,
                'negative' => true,
            ]

        ],

        [
            'name' => 'Committee of the Whole',
            'content' => "Shall the body convene as a committee of the whole with this body's Chair as its Chair ",
            'description' => "The formal deliberative process is suspended. The body
                 may work informally on an issue. No votes taken while in the committee of the whole
                are binding on the main body but they may be used to advise the main body on what to do.
                To communicate from the committee of the whole, the committee
                of the whole should vote to Rise and Report",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true,
            'debatable' => true,
        ],

        [
            'name' => 'Previous Question (Call the Question)',
            'content' => "Shall the pending question be called for",
            'description' => "If this motion is approved, all debate ends on the pending motion and the body moves immediately to a vote on the pending motion.
                If this motion fails, debate continues on the pending motion",
            'requires' => 0.66,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false,
            'reconsiderable' => [
                'affirmative' => true,
                'negative' => false,
            ]

//            'debatable' => [
//                'motion' => false,
//                'underlyingMotion' => false
//            ]
        ],

        [
            'name' => 'Postpone definitely',
            'content' => "Shall the pending motion be postponed to the specified time",
            'description' => "We stop work on the main motion and any subsidiary motions (e.g., amendments) and
            come back to it at the specified time. The specified time can be amended. Debate is only
            allowed on the wisdom of postponement, not the motion itself",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => true,
            'debatable' => true
//
//            'debatable' => [
//                'motion' => true,
//                'underlyingMotion' => false
//            ]
        ],

        [
            'name' => 'Postpone indefinitely',
            'content' => "Shall the pending motion be postponed indefinitely",
            'description' => "This kills the motion without voting directly on it. Debate on this motion can
            include debate on the motion being postponed.",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => true
//
//            'debatable' => [
//                'motion' => true,
//                'underlyingMotion' => true
//            ],
//            'reconsiderable' => [
//                'affirmative' => true,
//                'negative' => false,
//            ]
        ],

        [
            'name' => 'Place on the Table',
            'content' => "Shall the pending motion be placed on the table",
            'description' => "All action on the motion is paused so the body can attend to
                other business. There is no scheduled time to resume action. Action
                will resume upon a majority vote to Take from the Table. That motion may
                be made whenever no main motion is pending",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false,
//
//            'debatable' => [
//                'motion' => false,
//                'underlyingMotion' => false
//            ],
            'reconsiderable' => [
                'affirmative' => false,
                'negative' => true,
            ]
        ],


        [
            'name' => 'Take from the Table',
            'content' => "Shall the specified motion be taken from the table.",
            'description' => "If this motion passes, the tabled motion is resumed. The state of the motion is exactly
             the same as when it was tabled. This motion may be made whenever no main motion is pending",
            'requires' => 0.5,
            'type' => 'procedural-subsidiary',
            'amendable' => false,
            'debatable' => false,
            'reconsiderable' => [
                'affirmative' => false,
                'negative' => false,
            ]
//
//            'debatable' => [
//                'motion' => false,
//                'underlyingMotion' => false
//            ],
        ],


        [
            'name' => 'Recess',
            'content' => "Shall the body recess.",
            'description' => "We take a break. This can be qualified to say how long. The how long part is amendable.",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true,
            'debatable' => true,
            'reconsiderable' => [
                'affirmative' => false,
                'negative' => false,
            ]
        ],

        [
            'name' => 'Reconsider',
            'content' => "Shall the body reconsider the specified motion ",
            'description' => "Whether to reopen a decision that has already been voted upon.
            This can only be made by a member who voted with the prevailing side.
            The motion is debatable only if the motion being reconsidered is debatable",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => false,
            'reconsiderable' => [
                'affirmative' => false,
                'negative' => false,
            ],

        [
            'name' => 'Refer',
            'content' => "Shall the pending item be referred to the specified group.",
            'description' => "Usually used to task a committee to work on the item that was
             under discussion, sometimes with additional instructions as to what do, whether / when
             to bring the item back, et cetera. Can also be used to ask a specified group of members to work
             on something and bring it back during the same meeting.",
            'requires' => 0.5,
            'type' => 'procedural-main',
            'amendable' => true,
            'debatable' => true
        ],

    ]
    ];


    static public $introTemplates = [
        //Going to use this for VOT-306 and CSUN specific motions
        ['name' => 'Request Roll Call Vote (CSUN-specific)',
            'content' => "Shall the vote on the specified question(s) be taken via roll call. The vote of each senator will be recorded in the minutes.",
            'description' => "If approved, the vote on the specified question(s) will taken by roll call. That is, the Secretary will call each
             member's name and ask for their vote. The votes will be recorded in the minutes.",
            'requires' => 0.2,
            'amendable' => false,
            'debatable' => false,
        ],


//        ['name' => 'z1 RolePlayMain',
//            'content' => "That tacos be declared the official food of the CSUN Faculty.",
//            'requires' => 0.5,
//            'amendable' => true
//        ],
//        ['name' => 'z2 RolePlayAmendment1',
//            'content' => "That burritos be declared the official food of the CSUN Faculty.",
//            'requires' => 0.5,
//            'amendable' => true],
//
//        ['name' => 'z3 RolePlayAmendment2',
//            'content' => "That burritos and hamburgers be declared the official food of the CSUN Faculty.",
//            'requires' => .5,
//            'amendable' => false],

//
//        ['name' => 'z4 RolePlayBadAmendment',
//            'content' => "That burritos, hamburgers, and French fries be declared the official food of the CSUN Faculty.",
//            'requires' => 0.5,
//            'amendable' => true],
//

    ];

}
