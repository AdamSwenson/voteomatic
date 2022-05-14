<?php

namespace Database\Seeders;

use App\Models\Meeting;
use App\Models\Motion;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Seeder;

/**
 *
 *
 * dev NB, needs to be updated to keep the clean and formatted content separate
 */
class ResolutionWithAmendmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $titleBase = 'ESTABLISHING CORE COMPETENCIES FOR CSU GENERAL EDUCATION (GE)AREAS A1,A2,A3,AND B4,COMMONLY REFERRED TO AS THE “GOLDEN FOUR"';
//        $title = "<h4 class='rezzieTitle'>{$titleBase}</h4>";
        $rezzieIdentifier = 'AS-3515-21/APEP';

        $originalText = <<<HTML
<div class="rezzie">
<h4 class='rezzieTitle'>$titleBase</h4>
<p class='resolvedClause'>RESOLVED: That the Academic Senate of the California State University (ASCSU)
 reaffirm the primacy of the faculty role in curricular matters as specified in the Higher
 Education Employer-Employee Relations Act (HEERA),
  articulated in the “Report of the Board of Trustees Ad Hoc Committee on Governance,
  Collegiality and Responsibility in the California State University,” and embodied
  in accepted California State University (CSU) shared governance practices; and be it further</p>
  <p class='resolvedClause'>RESOLVED: That the ASCSU recommend the development of
  core competencies in order to establish clear and uniform college level standards
  for the “golden four”; and be it further</p>
  <p class='resolvedClause'>RESOLVED: That the ASCSU, in collaboration with the appropriate
  disciplinary experts, develop proposed core competencies foreach of the “golden four”
  General Education (GE) elements: Oral Communication(CSU GE Area A1), Written
  Communication (CSU GE Area A2), Critical Thinking (CSU GE Area A3), and
  Mathematics/Quantitative Reasoning (CSU GE Area B4); and be it further </p>
<p class="resolvedClause">RESOLVED:That the ASCSU distribute this resolution
to: </p>
<ul>
<li>CSU Board ofTrustees,</li>
<li>CSU campusProvosts,</li>
<li>CSU campus SenateChairs,</li>
<li>California State StudentAssociation (CSSA),</li>
 </ul>
</div>
HTML;
        $meeting = Meeting::factory()->create();
        $realUsers = User::all();

        $meeting = Meeting::factory()->create();
        foreach ($realUsers as $user) {
            $meeting->addUserToMeeting($user);
        }
        $adminUser = User::where('email', env('DEV_USER_ADMIN_EMAIL'))->first();
        $meeting->setOwner($adminUser);


        $mainMotion = Motion::factory()->create([
            'content' => $originalText,
            'seconded' => true,
            'is_complete' => true,
            'is_current' => false,
            'is_resolution' => true,
            'meeting_id' => $meeting->id,
            'info' => [
                'title' => $titleBase,
                'resolutionIdentifier' => $rezzieIdentifier,
                'formattedContent' => $originalText

            ],
        ]);

        $mainMotion['info']['groupId'] = $mainMotion->id;
        $mainMotion->save();


        $erfsaInsert = Motion::factory()->create([
            'applies_to' => $mainMotion->id,
            'meeting_id' => $meeting->id,
            'content' => '',
            'type' => 'amendment',
            'seconded' => true,
//            'is_complete' => true,
            'is_current' => false,
            'is_resolution' => true,
            'info' => $mainMotion->info //[
//                'title™' => $titleBase,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        $erfsaText = <<<HTML
<div class="rezzie">
<p class='resolvedClause'>RESOLVED: That the Academic Senate of the California State University (ASCSU)
 reaffirm the primacy of the faculty role in curricular matters as specified in the Higher
 Education Employer-Employee Relations Act (HEERA),
  articulated in the “Report of the Board of Trustees Ad Hoc Committee on Governance,
  Collegiality and Responsibility in the California State University,” and embodied
  in accepted California State University (CSU) shared governance practices; and be it further</p>
  <p class='resolvedClause'>RESOLVED: That the ASCSU recommend the development of
  core competencies in order to establish clear and uniform college level standards
  for the “golden four”; and be it further</p>
  <p class='resolvedClause'>RESOLVED: That the ASCSU, in collaboration with the appropriate
  disciplinary experts, develop proposed core competencies foreach of the “golden four”
  General Education (GE) elements: Oral Communication(CSU GE Area A1), Written
  Communication (CSU GE Area A2), Critical Thinking (CSU GE Area A3), and
  Mathematics/Quantitative Reasoning (CSU GE Area B4); and be it further </p>
<p class="resolvedClause">RESOLVED:That the ASCSU distribute this resolution
to: </p>
<ul>
<li>CSU Board ofTrustees,</li>
 <text-styler-factory
 type='insert'
 text='<li>CSU Emeritus and Retired Faculty & Staff Association(CSU-ERFSA),</li>'
 v-bind:amendment-id='$erfsaInsert->id'></text-styler-factory>
<li>CSU campusProvosts,</li>
<li>CSU campus SenateChairs,</li>
<li>California State StudentAssociation (CSSA),</li>

 </ul>
</div>
HTML;
        $erfsaInsert->content = $erfsaText;
        $erfsaInsert->info['formattedContent'] = $erfsaText;
        $erfsaInsert->save();
        Vote::factory(['motion_id' => $erfsaInsert->id])->affirmative()->count(8)->create();
        Vote::factory(['motion_id' => $erfsaInsert->id])->negative()->count(2)->create();
        $erfsaInsert->is_complete = true;
        $erfsaInsert->save();
//        $m1a32->markSuperseded($m1a2b);


        $main2 = Motion::factory()->create([
            'meeting_id' => $meeting->id,
            'content' => $erfsaText,
            'seconded' => true,
            'is_complete' => false,
            'is_resolution' => true,
            'is_current' => true,
            'info' => $erfsaInsert->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        $mainMotion->superseded_by = $main2->id;
        $mainMotion->save();

        //=========================== Failed strike
        $failedStrike = Motion::factory()->create([
            'applies_to' => $main2->id,
            'meeting_id' => $meeting->id,
            'content' => '',
            'type' => 'amendment',
            'seconded' => true,
//            'is_complete' => true,
            'is_current' => false,
            'is_resolution' => true,
            'info' => $mainMotion->info
//
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        $failedStrikeText = <<<HTML
<div class="rezzie">
<p class='resolvedClause'>RESOLVED: That the Academic Senate of the California State University (ASCSU)
 reaffirm the primacy of the faculty role in curricular matters as specified in the Higher
 Education Employer-Employee Relations Act (HEERA),
  articulated in the “Report of the Board of Trustees Ad Hoc Committee on Governance,
  Collegiality and Responsibility in the California State University,” and embodied
  in accepted California State University (CSU) shared governance practices; and be it further</p>
  <text-styler-factory
 type='strike'
 v-bind:amendment-id='$failedStrike->id'
 text='<p class="resolvedClause">RESOLVED: That the ASCSU recommend the development of
  core competencies in order to establish clear and uniform college level standards
  for the “golden four”; and be it further</p>'></text-styler-factory>
  <p class='resolvedClause'>RESOLVED: That the ASCSU, in collaboration with the appropriate
  disciplinary experts, develop proposed core competencies foreach of the “golden four”
  General Education (GE) elements: Oral Communication(CSU GE Area A1), Written
  Communication (CSU GE Area A2), Critical Thinking (CSU GE Area A3), and
  Mathematics/Quantitative Reasoning (CSU GE Area B4); and be it further </p>
<p class="resolvedClause">RESOLVED:That the ASCSU distribute this resolution
to: </p>
<ul>
<li>CSU Board ofTrustees,</li>
 <text-styler-factory
 type='insert'
 text='<li>CSU Emeritus and Retired Faculty & Staff Association(CSU-ERFSA),</li>'
 v-bind:amendment-id='$erfsaInsert->id'></text-styler-factory>
<li>CSU campusProvosts,</li>
<li>CSU campus SenateChairs,</li>
<li>California State StudentAssociation (CSSA),</li>

 </ul>
</div>
HTML;
        $failedStrike->content = $failedStrikeText;
        $failedStrike->info['formattedContent'] = $failedStrikeText;
        Vote::factory(['motion_id' => $failedStrike->id])->affirmative()->count(2)->create();
        Vote::factory(['motion_id' => $failedStrike->id])->negative()->count(8)->create();
        $failedStrike->is_complete = true;
        $failedStrike->save();

        $main3 = Motion::factory()->create([
            'meeting_id' => $meeting->id,
            'content' => $failedStrike->content,
            'seconded' => true,
            'is_complete' => false,
            'is_resolution' => true,
            'is_current' => false,
            'info' => $failedStrike->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        // ======================== Successful strike insert

        $si = Motion::factory()->create([
            'applies_to' => $main3->id,
            'meeting_id' => $meeting->id,
            'content' => '',
            'type' => 'amendment',
            'seconded' => true,
//            'is_complete' => true,
            'is_current' => false,
            'is_resolution' => true,
            'info' => $mainMotion->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        //dev For VOT-190 the compiler needs to run recursively so the change to the second resolved shows up correctly
        $siText = <<<HTML
<div class="rezzie">
<p class='resolvedClause'>RESOLVED: That the Academic Senate of the California State University (ASCSU)
 reaffirm the primacy of the faculty role in curricular matters as specified in the Higher
 Education Employer-Employee Relations Act (HEERA),
  articulated in the “Report of the Board of Trustees Ad Hoc Committee on Governance,
  Collegiality and Responsibility in the California State University,” and embodied
  in accepted California State University (CSU) shared governance practices; and be it further</p>
  <text-styler-factory
 type='strike'
 v-bind:amendment-id='$failedStrike->id'
 text='<p class="resolvedClause">RESOLVED: That the ASCSU recommend the development of
  core competencies in order to establish clear and uniform college level standards
  for the <text-styler-factory type="strike" v-bind:amendment-id="$si->id" text="golden four"></text-styler-factory> <text-styler-factory type="insert"
 v-bind:amendment-id="$si->id" text="four which are distinctive but not better than others"></text-styler-factory>; and be it further</p>'></text-styler-factory>
  <p class='resolvedClause'>RESOLVED: That the ASCSU, in collaboration with the appropriate
  disciplinary experts, develop proposed core competencies foreach of the <text-styler-factory type='strike' v-bind:amendment-id='$si->id'
 text='"golden four"'></text-styler-factory> <text-styler-factory
 type='insert'
 v-bind:amendment-id='$si->id'
 text='“four which are distinctive but not better than others”'></text-styler-factory>
  General Education (GE) elements: Oral Communication(CSU GE Area A1), Written
  Communication (CSU GE Area A2), Critical Thinking (CSU GE Area A3), and
  Mathematics/Quantitative Reasoning (CSU GE Area B4); and be it further </p>
<p class="resolvedClause">RESOLVED:That the ASCSU distribute this resolution
to: </p>
<ul>
<li>CSU Board ofTrustees,</li>
 <text-styler-factory
 type='insert'
 text='<li>CSU Emeritus and Retired Faculty & Staff Association(CSU-ERFSA),</li>'
 v-bind:amendment-id='$erfsaInsert->id'></text-styler-factory>
<li>CSU campusProvosts,</li>
<li>CSU campus SenateChairs,</li>
<li>California State StudentAssociation (CSSA),</li>

 </ul>
</div>
HTML;
        $si->content = $siText;
        $si->info['formattedContent'] = $siText;
        Vote::factory(['motion_id' => $si->id])->affirmative()->count(7)->create();
        Vote::factory(['motion_id' => $si->id])->negative()->count(2)->create();
        $si->is_complete = true;
        $si->save();

        $main4 = Motion::factory()->create([
            'meeting_id' => $meeting->id,
            'content' => $si->content,
            'seconded' => true,
            'is_complete' => false,
            'is_resolution' => true,
            'is_current' => false,
            'info' => $mainMotion->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);


        // ================ Failed insert

        $failedInsert = Motion::factory()->create([
            'applies_to' => $main4->id,
            'meeting_id' => $meeting->id,
            'content' => '',
            'type' => 'amendment',
            'seconded' => true,
//            'is_complete' => true,
            'is_current' => false,
            'is_resolution' => true,
            'info' => $mainMotion->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);

        //dev For VOT-190 the compiler needs to run recursively so the change to the second resolved shows up correctly
        $failedInsertText = <<<HTML
<div class="rezzie">
<p class='resolvedClause'>RESOLVED: That the Academic Senate of the California State University (ASCSU)
 reaffirm the primacy of the faculty role in curricular matters as specified in the Higher
 Education Employer-Employee Relations Act (HEERA),
  articulated in the “Report of the Board of Trustees Ad Hoc Committee on Governance,
  Collegiality and Responsibility in the California State University,” and embodied
  in accepted California State University (CSU) shared governance practices; and be it further</p>
  <text-styler-factory
 type='strike'
 v-bind:amendment-id='$failedStrike->id'
 text='<p class="resolvedClause">RESOLVED: That the ASCSU recommend the development of
  core competencies in order to establish clear and uniform college level standards
  for the <text-styler-factory type="strike" v-bind:amendment-id="$si->id" text="golden four"></text-styler-factory> <text-styler-factory type="insert"
 v-bind:amendment-id="$si->id" text="four which are distinctive but not better than others"></text-styler-factory>; and be it further</p>'></text-styler-factory>
  <p class='resolvedClause'>RESOLVED: That the ASCSU, in collaboration with the appropriate
  disciplinary experts, develop proposed core competencies foreach of the <text-styler-factory type='strike' v-bind:amendment-id='$si->id'
 text='"golden four"'></text-styler-factory> <text-styler-factory
 type='insert'
 v-bind:amendment-id='$si->id'
 text='“four which are distinctive but not better than others”'></text-styler-factory>
  General Education (GE) elements: Oral Communication(CSU GE Area A1), Written
  Communication (CSU GE Area A2), Critical Thinking (CSU GE Area A3), and
  Mathematics/Quantitative Reasoning (CSU GE Area B4); and be it further </p>
<p class="resolvedClause">RESOLVED:That the ASCSU distribute this resolution
to: </p>
<ul>
<li>CSU Board ofTrustees,</li>
 <text-styler-factory
 type='insert'
 text='<li>CSU Emeritus and Retired Faculty & Staff Association(CSU-ERFSA),</li>'
 v-bind:amendment-id='$erfsaInsert->id'></text-styler-factory>
<li>CSU campusProvosts,</li>
<li>CSU campus SenateChairs,</li>
 <text-styler-factory
 type='insert'
 text='<li>Yo mama,</li>'
 v-bind:amendment-id='$failedInsert->id'></text-styler-factory>
<li>California State StudentAssociation (CSSA),</li>

 </ul>
</div>
HTML;
        $failedInsert->content = $failedInsertText;
        $failedInsert->info['formattedContent'] = $failedInsertText;
        Vote::factory(['motion_id' => $failedInsert->id])->affirmative()->count(2)->create();
        Vote::factory(['motion_id' => $failedInsert->id])->negative()->count(9)->create();
        $failedInsert->is_complete = true;
        $failedInsert->save();

        $main5 = Motion::factory()->create([
            'meeting_id' => $meeting->id,
            'content' => $failedInsert->content,
            'seconded' => true,
            'is_complete' => false,
            'is_resolution' => true,
            'is_current' => true,
            'info' => $mainMotion->info
//            'info' => [
//                'title' => $title,
//                'resolutionIdentifier' => $rezzieIdentifier
//            ],
        ]);
        $main5->info['formattedContent'] = $failedInsert->content;
        $main5->save();

//        $mainMotion->superseded_by = $main2->id;
//        $mainMotion->save();


        $this->command->line("\n Meeting with amended resolutions: {$meeting->id}");
        $this->command->line("\n Root Rezzie id : {$mainMotion->id}");


    }
}
