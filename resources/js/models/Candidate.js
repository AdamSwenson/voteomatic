import IPerson from "./IPerson";
import {isReadyToRock} from "../utilities/readiness.utilities";

/**
 * One person running for one office.
 *
 * Importantly, the candidate knows the id of the election (motion)
 */
export default class Candidate extends IPerson {


    constructor({
                    id = null,
                    first_name = null,
                    last_name = null,
                    info = {},
                    motion_id = null,
                    is_write_in = null,
                    person_id = null
                }) {
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

    /**
     * This is used in checking for duplicates. Since a person
     * may be a candidate in multiple elections, we have to check both
     * the person id and the motion id.
     *
     * We cannot rely just on the id since the candidate objects if separately
     * created will always have different ids
     *
     * @param candidate
     */
    isIdentical(candidate) {
        return this.person_id === candidate.person_id && this.motion_id === candidate.motion_id;
    }
    //
    // get name() {
    //     let n =  this.first_name + " " + this.last_name ;
    //     let me = this;
    //     let other = ''
    //     _.forEach(this.info.keys(), (k) => {
    //         other += me.info[k]
    //         other += ' '
    //     });
    //     if (other.length > 0){
    //         return n + " (" + other + ")";
    //     }
    //     return n;
    // };

    get isWriteIn() {
        return this.is_write_in;
    }


    get motionId() {
        return motion_id;
    }

    // getInfoField(fieldName){
    //     if(! isReadyToRock(this.info) || !isReadyToRock(this.info[fieldName])) return ''
    //     return this.info[fieldName];
    // }
    //
    // setInfoField(fieldName, val){
    //     this.info[fieldName] = val;
    // }

}
