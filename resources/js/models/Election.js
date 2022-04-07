import Meeting from "./Meeting";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Election extends Meeting {
    election_phase;


    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor({id=null, name=null, date=null, info= {}, is_voting_available=null, is_complete=null, phase=null, }) {
        super(id, name, date);
        this.info = info;
        this.is_voting_available = is_voting_available;
        this.is_complete = is_complete;
        this.phase = phase;

        //dev deprecated
        // this.election_phase = this.phase;

        /** The string used on buttons etc */
        this.type = 'election';

        /**
         * What the basic things we operate on are called.
         * Again used for buttons etc
         */
        this.subsidiaryType = 'office';

        /**
         * List of valid phases
         * @type {*[]}
         */
        this.phases = ['setup', 'nominations', 'voting', 'closed', 'results']
    }

    /**
     * Returns list of fields [] which should be
     * displayed
     *
     * dev Ideally should return:
     *   [
     *      {fieldName : '' , fieldType : '', displayOrder},
     *      {fieldName : '' , fieldType : ''},
     *   ]
     * @returns {{}|{}|{}|*|{}|{}}
     */
    get candidateFields(){
        if(! isReadyToRock(this.info) || ! isReadyToRock(this.info.candidateFields)) return [];

        return this.info.candidateFields;
    }

    get isComplete(){

        return this.phase === 'closed' || this.phase === 'results';

        //dev Remove after VOT-177
        // return this.is_complete;
    }

    //dev Remove after VOT-177
    // set isComplete(v){
    //
    //
    //     this.is_complete = v;
    // }

    get isVotingAvailable(){
        return this.phase === 'voting';

        //dev Remove after VOT-177
        // return this.is_voting_available;
    }

    //dev Remove after VOT-177
    // set isVotingAvailable(v){
    //     this.is_voting_available = v;
    // }

    get election_phase(){
        return this.phase;
    }

    get electionPhase(){
        return this.phase;
    }

    set electionPhase(v){
        this.phase = v;
    }

    /**
     * Whether all users are able to view results
     */
    get isResultsAvailable(){
        return this.phase === 'results';

       //dev Remove after VOT-177
        // if(! this.is_complete ) return false;
        // if( this.is_voting_available ) return false;
        // return this.info.is_results_available;
    }

    //dev Remove after VOT-177
    // set isResultsAvailable(v){
    //     this.info.is_results_available = v;
    // }

}
