<?php

namespace App\Repositories;

use App\Models\Meeting;
use App\Models\Assignment;
use App\Models\Motion;
use Tests\helpers\AssignmentDataMaker;
use Tests\TestCase;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed meeting
 */
class AssignmentRepositoryTest extends TestCase
{

    public $rootAssignment;
    public $rootMotion;

    /**
     * @var AssignmentDataMaker
     */
    public $dataMaker;

    public function setUp(): void
    {
        parent::setUp();

        $this->meeting = Meeting::factory()->create();
        $this->meeting->initializeAssignmentRoot();
        $this->rootAssignment = $this->meeting->getAssignmentRoot();
        $this->rootMotion = $this->rootAssignment->motion;
        $this->motion1 = Motion::factory()->create();
        $this->object = new AssignmentRepository;

        //Was getting problem with the recursive function
        //inside static methods, so (re)instantiating the class.
        $this->dataMaker = new AssignmentDataMaker();

    }

    // ----------------------------------- static methods

    /** @test */
    public function addChildren()
    {
        $this->markTestSkipped('likely unused method');
    }

    /** @test */
    public function testRecursiveMaker()
    {
        $this->markTestSkipped('likely unused method');
    }


    // --------------------------------- Others

    /** @test */
    public function canBeSynced()
    {
        $this->markTestSkipped('Possible gradeomatic relic?');
    }

    /** @test */
    public function getMotionTreeForClient()
    {
        $this->markTestIncomplete('problem with redeclaring recursiveAdder in data maker');

        //prep
        $motion0 = $this->rootMotion;
//        $motion0 = factory(Motion::class)->create(); //sib
        $motion1 = Motion::factory()->create(); //sib
        $motion2 = Motion::factory()->create(); //sib
        $motion3 = Motion::factory()->create(); //child of 1
//        meetingId: 9, motionId: 81, parentId: 9, position: 0}
        $order = [
            ['meetingId' => $this->meeting->id, 'parentId' => $motion0->id, 'motionId' => $motion1->id, 'position' => 0],
            ['meetingId' => $this->meeting->id, 'parentId' => $motion0->id, 'motionId' => $motion2->id, 'position' => 1],
            ['meetingId' => $this->meeting->id, 'parentId' => $motion1->id, 'motionId' => $motion3->id, 'position' => 0]];
        foreach ($order as $o) {
            $parentAssign = Assignment::firstOrCreate(
                [
                    'meeting_id' => $o['meetingId'],
                    'motion_id' => $o['parentId']
                ]);

            //Now we can make the actual meeting entry
            $assignment = Assignment::firstOrCreate([
                'meeting_id' => $o['meetingId'],
                'motion_id' => $o['motionId']
            ]);
            $parentAssign->addChild($assignment, $o['position']);

            //add assignment id to expected array
            $order['motionAssignmentId'] = $assignment->id;

        }

        //call
        $result = $this->object->getMotionTreeForClient($this->meeting);

        //check

        $this->assertEquals(sizeof($order), sizeOf($result));

//        $this->assertEquals(sizeof($order) + 1, sizeOf($result['position']));

        //starting at 1 so that we can just use the order array
        // without having to shoehorn the root element in
        for ($i = 1; $i < sizeof($order); $i++) {
            //todo changed $result['order'] to $result['position'] to get working not sure if matters
            $this->assertEquals($order[$i], $result[$i]);
        }

        //check that the root is there
        $root = $result[0];
//        $root = $result['position'][0];
        $this->assertEquals($root['parentId'], null, "parent id of root is null");
        $this->assertEquals($this->rootMotion, $root);
    }

    /** @test */
    public function getMotionTreeForClientForClient2()
    {
        $this->markTestSkipped('problem with redeclaring recursiveAdder in data maker');

        $numLevels = 3;
        $numChildren = 3;

        //Was getting problem with the recursive function
        //inside static methods, so (re)instantiating the class.
        $dataMaker = new AssignmentDataMaker();

        $d = $dataMaker->makeMeetingData($this->meeting, $numLevels, $numChildren);
        $children = $d->getChildren();

        //call
        $result = $this->object->getMotionTreeForClient($this->meeting);

        //check
//NB, we are skipping the root motion
        $this->assertEquals(sizeof($children) + 1, sizeOf($result['position']));

        //starting at 1 so that we can just use the order array
        // without having to shoehorn the root element in
        for ($i = 0; $i < sizeof($children); $i++) {
            $this->assertEquals($children[$i]->motion_id, $result['position'][$i]['motionId']);
        }

//        //check that the root is there
//        $root = $result['position'][0];
//        $this->assertEquals($root['parentId'], null, "parent id of root is null");
//        $this->assertEquals($this->rootMotion, $root);

    }


    /** @test */
    public function getPositionForDraft()
    {
        $this->markTestSkipped('Refers to no longer existing method....');

        $meeting = Meeting::factory()->create();
        $numLevels = 3;
        $numChildren = 3;
        $expectedDescendants = 24;

        //Was getting problem with the recursive function
        //inside static methods, so (re)instantiating the class.
        $this->dataMaker = new AssignmentDataMaker();

        //call
        $assigned = $this->dataMaker->makeMeetingData($meeting, $numLevels, $numChildren);

        $tree = $this->object->getPositionForDraft($meeting);

        $this->assertNotEmpty($tree);
        $this->assertEquals($expectedDescendants, sizeof($tree), "correct number returned");

        //todo check the details

    }


    /** @test */
    public function makeAssignment()
    {
        $depth = 5;
        $result = AssignmentRepository::makeAssignment($this->meeting, $this->motion1, $this->rootAssignment->id, $depth);

        $this->assertInstanceOf(Assignment::class, $result);
        $this->assertEquals($this->rootAssignment->id, $result->parent_id);
        $this->assertEquals($depth, $result->depth);

    }


    /** @test */
    public function processIncoming()
    {
        $numLevels = 3;
        $numAtLevel = 3;
        $motion0 = Motion::factory()->create(); //sib
        $motion1 = Motion::factory()->create(); //sib
        $motion2 = Motion::factory()->create(); //sib
        $motion3 = Motion::factory()->create(); //child of 1
//        meetingId: 9, motionId: 81, parentId: 9, position: 0}
        $order = [
            ['meetingId' => $this->meeting->id, 'parentId' => $motion0->id, 'motionId' => $motion1->id, 'position' => 0],
            ['meetingId' => $this->meeting->id, 'parentId' => $motion0->id, 'motionId' => $motion2->id, 'position' => 1],
            ['meetingId' => $this->meeting->id, 'parentId' => $motion1->id, 'motionId' => $motion3->id, 'position' => 0]];

        //call
        $this->object->processIncoming($this->meeting, $order);

        //check
        $assignments = Assignment::where('meeting_id', $this->meeting->id)->get();

        //Check that the desired records are present
        $this->assertDatabaseHas('assignments', [
            'meeting_id' => $this->meeting->id,
            'motion_id' => $motion1->id,
            'position' => 0]);
        $this->assertDatabaseHas('assignments', [
            'meeting_id' => $this->meeting->id,
            'motion_id' => $motion2->id,
            'position' => 1]);
        $this->assertDatabaseHas('assignments', [
            'meeting_id' => $this->meeting->id,
            'motion_id' => $motion3->id,
//            'parent_id' => Assignment::where('meeting_id', $this->meeting1->id)->
            'position' => 0]);

        //Check tha these are the only records for the meeting1
        $this->assertEquals(5, $assignments->count());
    }

    /** @test */
    public function sadPathProcessIncomingWhereUnsetMotionIdsHaveSnuckIn()
    {
        $this->markTestSkipped('Possible gradeomatic relic?');
    }

    /** @test */
    public function doesNotDeleteExistingOrderOnLoad()
    {
        $this->markTestSkipped('Possible gradeomatic relic?');
    }

    /** @test */
    public function verifyDataFactoryWorks()
    {
        $this->markTestSkipped('problem with redeclaring recursiveAdder in data maker');


        $meeting = Meeting::factory()->create();
        $numLevels = 3;
        $numChildren = 3;
        $expectedDescendants = 24;

        //Was getting problem with the recursive function
        //inside static methods, so (re)instantiating the class.
        $this->dataMaker = new AssignmentDataMaker();

        //call
        $result = $this->dataMaker->makeMeetingData($meeting, $numLevels, $numChildren);

        //check
        $this->assertInstanceOf(Assignment::class, $result);
        $children = $result->getChildren();
        $this->assertEquals(sizeof($children), $numChildren, "root has expected number of children");
        $this->assertEquals($expectedDescendants, $result->countDescendants(), "Total number of descendants is correct");

//
//        for ( $j = 0; $j < $numLevels; $j++ ) {
//            $child = $children[$j]->getChildren();
//            for ( $i = 0; $i < $numLevels; $i++ ) {
//                $c = $child->getChildren();
//                $this->assertEquals(sizeof($c), $numChildren);
//            }
//        }
    }


}
