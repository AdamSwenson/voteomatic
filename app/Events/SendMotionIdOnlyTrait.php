<?php

namespace App\Events;

/**
 * Used to lower size of pusher payloads
 * when the client won't need more than just the motion id
 */
trait SendMotionIdOnlyTrait
{

    public function broadcastWith()
    {
        return [
            "motion_id" => $this->motion->id,

            "motion" => [
//                "content" => $this->motion->content,
//                "description" => $this->motion->description,
//                "requires" => $this->motion->requires,
//                "type" => $this->motion->type,
//                "is_complete" => $this->motion->is_complete,
//                "is_current" => $this->motion->is_current,
//                "meeting_id" => $this->motion->meeting_id,
                "id" => $this->motion->id,
//                "applies_to" => $this->motion->applies_to,
//                "seconded" => $this->motion->seconded,
//                "amendable" => $this->motion->is_amendable,
//                "superseded_by" => $this->motion->superseded_by,
//                "debatable" => $this->motion->debatable,
//                "max_winners" => $this->motion->max_winners,
//                "author_id" => $this->motion->author_id,
//                "seconder_id" => $this->motion->seconder_id,
//                "approver_id" => $this->motion->approver_id,
//                "is_in_order" => $this->motion->is_in_order,
//                "is_voting_allowed" => $this->motion->is_voting_allowed,
//                "is_resolution" => $this->motion->is_resolution
            ]
        ];

    }
}
