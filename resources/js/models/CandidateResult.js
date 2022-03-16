import IModel from "./IModel";
import {isReadyToRock} from "../utilities/readiness.utilities";
import Candidate from "./Candidate";

/**
 * The results for one person running for one office.
 *
 */
export default class CandidateResult extends IModel {


    /**
     *
     * @param motionId
     * @param candidateId
     * @param candidateName
     * @param voteCount
     * @param pctOfTotal
     * @param isWinner
     * @param isRunoffParticipant
     * @param person
     */
    constructor({motionId = null, candidateId = null, candidateName = null, voteCount = null, pctOfTotal = null, isWinner=null, isRunoffParticipant=null, person=null}) {
        super();
        this.motionId = motionId;
        this.candidateId = candidateId;
        this.candidateName = candidateName;
        this.voteCount = voteCount;
        this.pctOfTotal = pctOfTotal;
        this.isWinner = isWinner;
        this.isRunoffParticipant = isRunoffParticipant;
        this.person = isReadyToRock(person) ? new Candidate(person) : null;

    }

    get motion_id() {
        return this.motionId;
    }

    /**
     * Returns the vote share as a 2 digit integer
     * @returns {number}
     */
    get voteShareAsPercentage(){
        let s = this.pctOfTotal;
        s = s * 100;
        s = s.toFixed(2);
        // return s;
        s = Number(s);
        return s;
    }
    //
    // get isMajorityWinner(){
    //     return this.pctOfTotal > 0.5;
    // }
}
