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
    constructor({id=null, name=null, date=null, info= {}, is_voting_available=null, is_complete=null, election_phase=nll}) {
        super(id, name, date);
        this.info = info;
        this.is_voting_available = is_voting_available;
        this.is_complete = is_complete;
        this.election_phase = election_phase;

        /** The string used on buttons etc */
        this.type = 'election';

        /**
         * What the basic things we operate on are called.
         * Again used for buttons etc
         */
        this.subsidiaryType = 'office';
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
        return this.is_complete;
    }

    set isComplete(v){
        this.is_complete = v;
    }

    get isVotingAvailable(){
        return this.is_voting_available;
    }

    set isVotingAvailable(v){
        this.is_voting_available = v;
    }

    get electionPhase(){
        return this.election_phase;
    }

    /**
     * Whether all users are able to view results
     */
    get isResultsAvailable(){
        if(! this.is_complete ) return false;
        if( this.is_voting_available ) return false;
        return this.info.is_results_available;
    }

    set isResultsAvailable(v){
        this.info.is_results_available = v;
    }

}
