import IModel from "./IModel";

/**
 * The results for one motion
 *
 * NB, the passed property is set by the server. It is not computed from the yays and nays.
 * This is because we do not always want to expose the breakdown.
 *
 * Props:
 *      motionId;
 *      passed;
 *      totalVotes;
 *      nayCount;
 *      yayCount;
 */
export default class MotionResult extends IModel {


    constructor({motionId = null, passed= null, totalVotes=null, nayCount=null, yayCount= null}){
        super();
        this.motionId = motionId;
        this.passed = passed;
        this.totalVotes = totalVotes;
        this.nayCount = nayCount;
        this.yayCount = yayCount;
    }

    get motion_id() {
        return this.motionId;
    }


}
