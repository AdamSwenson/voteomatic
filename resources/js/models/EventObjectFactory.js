import Meeting from "./Meeting";
import Election from "./Election";
import {isReadyToRock} from "../utilities/readiness.utilities";

/**
 * Determines whether an incoming response from the server
 * is a meeting or an election (or other encompassing object)
 * and creates the appropriate object
 */
export default class EventObjectFactory {

    static make(data){
        // window.console.log('mainObjectFactory', data, data.is_election);
        if(data.is_election === true){
            // window.console.log('y');
            return new Election(data);
        }
        else{
            return new Meeting(data.id, data.name, data.date);
        }
    }

}
