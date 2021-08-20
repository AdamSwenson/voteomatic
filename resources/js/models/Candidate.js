
import IModel from "./IModel";

/**
 * One person running for one office.
 *
 * Importantly, the candidate knows the id of the election (motion)
 */
export default class Candidate extends IModel {


    constructor({id = null, first_name = null, last_name=null, info = null, motion_id=null, is_write_in=null, person_id=null}) {
        super();
        this.id = id;
        this.first_name = first_name;
        this.last_name = last_name;
        this.info = info;
        this.motion_id = motion_id;
        this.is_write_in = is_write_in;
        this.person_id = person_id;
        // this.pool_member_id = pool_member_id;

        // this.type = 'nominated';

    }

    get name(){
        return this.first_name + " " + this.last_name;
    }
;
    get isWriteIn(){
        return this.is_write_in;
    }


    get motionId(){
        return motion_id;
    }
}
