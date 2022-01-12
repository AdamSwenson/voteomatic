<?php

namespace App\Repositories;

use App\Exceptions\IneligibleSecondAttempt;
use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use App\Repositories\MotionRepository;
use Tests\TestCase;

class MotionRepositoryTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->object = new MotionRepository();
    }

    /** @test */
    public function handleApprovedAmendment()
    {
        $original = Motion::factory()->create();
        $amendment = Motion::factory()->amendment()->create(['applies_to' => $original]);

        $superseding = $this->object->handleApprovedAmendment($original, $amendment);

        //check
        //verify new motion
        $this->assertInstanceOf(Motion::class, $superseding, "Returns a motion");
        $this->assertEquals($amendment->content, $superseding->content, "Amended text set");
        $this->assertEquals($original->type, $superseding->type);
        $this->assertEquals($original->requires, $superseding->requires, "Original requires set");
        $this->assertEquals($original->description, $superseding->description, "Description set");
        $this->assertEquals($original->seconded, $superseding->seconded, "Seconded set");
        $this->assertEquals($original->applies_to, $superseding->applies_to, "Original applies to set");

        //verify status changed on the original
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }



    /** @test */
    public function handlePotentialAmendment(){
        $original = Motion::factory()->majority()->create();
        $amendment = Motion::factory()->amendment()->create(['applies_to' => $original]);

        Vote::factory()->affirmative()->count(5)->create(['motion_id' => $amendment->id]);
        Vote::factory()->negative()->count(1)->create(['motion_id' => $amendment->id]);

        $this->assertTrue($amendment->isAmendment(), "preflight check is amendment");
        $this->assertTrue($amendment->passed, "preflight check passed");

        //call
        $superseding = $this->object->handlePotentialAmendment($amendment);

        //check
        //verify new motion
        $this->assertInstanceOf(Motion::class, $superseding, "Returns a motion");
        $this->assertEquals($amendment->content, $superseding->content, "Amended text set");
        $this->assertEquals($original->type, $superseding->type);
        $this->assertEquals($original->requires, $superseding->requires, "Original requires set");
        $this->assertEquals($original->description, $superseding->description, "Description set");
        $this->assertEquals($original->seconded, $superseding->seconded, "Seconded set");
        $this->assertEquals($original->applies_to, $superseding->applies_to, "Original applies to set");

        //verify status changed on the original
        $original = $original->fresh();
        $this->assertEquals($original->superseded_by, $superseding->id, "Superseded by set on the original");

    }


    /** @test */
    public function handlePotentialAmendmentReturnsFalseIfNotAmendment()
    {
        $amendment = Motion::factory()->create();

        $superseding = $this->object->handlePotentialAmendment($amendment);

        //check
        $this->assertFalse($superseding, "Returned false because not amendment");
    }

    /** @test */
    public function handlePotentialAmendmentReturnsFalseIfNotPassed()
    {
        $amendment = Motion::factory()->create();
        Vote::factory()->affirmative()->count(1)->create(['motion_id' => $amendment->id]);
        Vote::factory()->negative()->count(5)->create(['motion_id' => $amendment->id]);

        $superseding = $this->object->handlePotentialAmendment($amendment);

        //check
        $this->assertFalse($superseding, "Returned false because not passed");
    }




    /** @test */
    public function secondMotionRegularUser()
    {
        $maker = User::factory()->create();
        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
//        $meeting->setOwner($m)
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $second);

        $this->assertTrue($motion->seconded);
        $this->assertEquals($second->id, $motion->seconder_id);
    }

    /** @test */
    public function secondMotionChair()
    {
        $maker = User::factory()->create();
//        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $meeting->setOwner($maker);
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $maker);

        $this->assertTrue($motion->seconded);
        $this->assertEquals($maker->id, $motion->seconder_id);
    }


    /** @test */
    public function secondMotionRejectsSelfSeconding()
    {
        $this->expectException(IneligibleSecondAttempt::class);
        $maker = User::factory()->create();
        $second = User::factory()->create();
        $meeting = Meeting::factory()->create();
        $motion = Motion::factory()->create([
            'author_id' => $maker->id,
            'meeting_id' => $meeting->id
        ]);

        $motion = $this->object->secondMotion($motion, $maker);

    }

    /** @test */
    public function isPusherCompatible(){
        $meeting = Meeting::factory()->create();
        $d = [
            "is_resolution"=> true,
    "applies_to"=> null,
    "content"=> "<p class=\"MsoBodyText2\" align=\"center\" style=\"margin=> 0in 0in 10pt; font-size=> 14pt; font-family=> &quot;Times New Roman&quot;, serif; font-weight=> bold; color=> rgb(0, 0, 0); text-align=> center;\"><span class=\"MsoLineNumber\"><span style=\"font-size=> 15pt; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Academic Freedom and Teaching Modality in the Covid-19 Pandemic<o=>p></o=>p></span></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 8pt 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 32px;\"><b><span style=\"font-family=> Garamond, serif;\">1.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 37.3333px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 36px; font-family=> Garamond, serif;\">=>&nbsp; That the Academic Senate of the California State University (ASCSU) recognize that we are still dealing with the COVID-19 pandemic and the very contagious Delta variant; and be it further<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 8pt 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 32px;\"><b><span style=\"font-family=> Garamond, serif;\">2.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 37.3333px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 36px; font-family=> Garamond, serif;\">=>&nbsp; That the faculty have a right to make decisions as to what pertains to their teaching environment and their personal health; and be it further<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 8pt 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 32px;\"><b><span style=\"font-family=> Garamond, serif;\">3.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 37.3333px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 36px; font-family=> Garamond, serif;\">=>&nbsp; That to avoid canceling classes, faculty have the ad hoc flexibility to rapidly pivot face-to-face courses temporarily to virtual instruction during acute or dynamic transitory extenuating circumstances such as sudden COVID-19 spikes, childcare, elder care, and for physical and/or mental health management; and be it further<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 8pt 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 32px;\"><b><span style=\"font-family=> Garamond, serif;\">4.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 37.3333px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 36px; font-family=> Garamond, serif;\">=>&nbsp; That the ASCSU request that the Chancellor’s Office (CO) declare that, for as long as COVID-19 remains a concern, course modality be determined by the faculty member; and be it further<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 8pt 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 32px;\"><b><span style=\"font-family=> Garamond, serif;\">5.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 37.3333px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 36px; font-family=> Garamond, serif;\">=>&nbsp; That ASCSU urge individual campuses to accept instructor-initiated changes in the mode of instruction in response to the changing conditions of the pandemic; and be it further<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 0.25in; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><b><span style=\"font-family=> Garamond, serif;\">6.<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-weight=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span></b><span dir=\"LTR\"></span><b><span style=\"font-size=> 14pt; line-height=> 28px; font-family=> Garamond, serif; font-variant-numeric=> normal; font-variant-east-asian=> normal; font-variant-caps=> small-caps;\">Resolved</span></b><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">=>&nbsp; </span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">That </span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">the ASCSU distribute this resolution to the=><o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU Board of Trustees,<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU Chancellor,<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU campus Presidents,<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU campus Senate Chairs,<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU campus Senate Executive Committees,<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 0in 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">CSU Provosts/Vice Presidents of Academic Affairs, and<o=>p></o=>p></span></p><p class=\"MsoListParagraph\" style=\"margin=> 0in 0in 12pt 58.3pt; font-size=> 12pt; font-family=> &quot;Times New Roman&quot;, serif; color=> rgb(0, 0, 0); text-indent=> -0.25in; line-height=> 24px;\"><span style=\"font-family=> &quot;Noto Sans Symbols&quot;;\">●<span style=\"font-variant-numeric=> normal; font-variant-east-asian=> normal; font-stretch=> normal; font-size=> 7pt; line-height=> normal; font-family=> &quot;Times New Roman&quot;;\">&nbsp;&nbsp;&nbsp;&nbsp; </span></span><span dir=\"LTR\"></span><span style=\"font-size=> 13.5pt; line-height=> 27px; font-family=> Garamond, serif;\">President of California Faculty Association (CFA).<o=>p></o=>p></span></p>",
    "description"=> null,
    "debatable"=> true,
    "is_voting_allowed"=> null,
    "max_winners"=> null,
    "requires"=> 0.5,
    "seconded"=> true,
    "superseded_by"=> null,
    "type"=> "main",
    "id"=> 632,
//    "meeting_id"=> $meeting->id,
    "meeting_id"=> 3,

    "author_id"=> 1,
    "seconder_id"=> 1,
    "approver_id"=> 1,
    "is_in_order"=> true,
    "is_current"=> true,
    ];
$motion = Motion::factory()->create($d);
$motion->fresh();

$this->assertFalse(MotionRepository::isPusherCompatible($motion));
    }

}
