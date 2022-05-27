import Meeting from "./Meeting";
import Election from "./Election";
import Office from "./Office";
import Motion from "./Motion";
import {isReadyToRock} from "../utilities/readiness.utilities";
import Proposition from "./Proposition";
import MotionObjectFactory from "./MotionObjectFactory";

/**
 * Determines whether an incoming response from the server
 * is a meeting or an election (or other encompassing object)
 * and creates the appropriate object
 */
export default class BallotObjectFactory {

    static make(data, meeting){

        // window.console.log('mainObjectFactory', data, data.is_election);
        if(meeting.is_election === true){
            if(data.type === 'proposition') return new Proposition(data);

            // window.console.log('y');
            return new Office(data);
        }
        else{
            return MotionObjectFactory.make(data);
            return new Motion(data);
        }
    }

}
