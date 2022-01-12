
import IPerson from "./IPerson";
import {isReadyToRock} from "../utilities/readiness.utilities";

/**
 * One person running for one office.
 *
 * Importantly, the candidate knows the id of the election (motion)
 */
export default class PoolMember extends IPerson {


    constructor({id = null, first_name = null, last_name=null, info = {}, motion_id=null, person_id=null }) {
        super();
        this.id = id;
        this.first_name = first_name;
        this.last_name = last_name;
        this.info = info;
        this.motion_id = motion_id;
        this.person_id = person_id;

        this.type = 'pool';

    }

    // getInfoField(fieldName){
    //     if(! isReadyToRock(this.info) || !isReadyToRock(this.info[fieldName])) return ''
    //     return this.info[fieldName];
    // }
    //
    // setInfoField(fieldName, val){
    //     this.info[fieldName] = val;
    // }

    // get name(){
    //     return this.first_name + " " + this.last_name;
    // }

    get motionId(){
        return motion_id;
    }
}
