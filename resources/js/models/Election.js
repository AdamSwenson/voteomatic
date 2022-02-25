import Meeting from "./Meeting";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Election extends Meeting {
    is_voting_available;

    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor({id=null, name=null, date=null, info= {}, is_voting_available=null, is_complete=null_}) {
        super(id, name, date);
        this.info = info;
        this.is_voting_available = is_voting_available;
        this.is_complete = is_complete;

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

}
