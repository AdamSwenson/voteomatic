import IModel from "./IModel";

export default class Candidate extends IModel {


    constructor({id = null, name = null, info = null, motion_id=null}) {
        super();
        this.id = id;
        this.name = name;
        this.info = info;
        this.motion_id = motion_id;

    }


    get motionId(){
        return motion_id;
    }
}
