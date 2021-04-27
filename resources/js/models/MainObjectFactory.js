import Meeting from "./Meeting";
import Election from "./Election";
import {isReadyToRock} from "../utilities/readiness.utilities";

/**
 * Determines whether an incoming response from the server
 * is a meeting or an election (or other encompassing object)
 * and creates the appropriate object
 */
export default class MainObjectFactory {

    static make(response){
        window.console.log('mainObjectFactory', response.data, response.data.is_election);
        if(response.data.is_election === true){
            window.console.log('y');
            return new Election(response.data);
        }

        else{
            return new Meeting(response.data.id, response.data.name, response.data.date);
        }
    }

}
