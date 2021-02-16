
import IModel from "./IModel";

/**
 * One person running for one office.
 *
 * Importantly, the candidate knows the id of the election (motion)
 */
export default class Candidate extends IModel {


    constructor({id = null, name = null, info = null, motion_id=null, is_write_in=null}) {
        super();
        this.id = id;
        this.name = name;
        this.info = info;
        this.motion_id = motion_id;
        this.is_write_in = is_write_in;

    }


    get isWriteIn(){
        return this.is_write_in;
    }


    get motionId(){
        return motion_id;
    }
}
