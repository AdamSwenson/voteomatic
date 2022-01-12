import Meeting from "./Meeting";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Election extends Meeting {

    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor({id=null, name=null, date=null, info= {}}) {
        super(id, name, date);
        this.info = info;

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

}
