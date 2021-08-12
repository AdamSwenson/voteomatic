import IModel from "./IModel";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Vote extends IModel {
    motionId;

    /**
     * Create a new motion
     * @param params
     */
    constructor({isYay=null, receipt=null, id=null, motionId=null}) {
        super();
        this._isYay = isYay;
        this.receipt = receipt;
        this.id = id;
        this.motionId = motionId;
    }

    get isYay(){
        return this._isYay;
    }

    set isYay(v){
        if(v === true) this._isYay = true;
        if(v === false) this._isYay = false;

        if(! isReadyToRock(v)) this._isYay =null;

        if(_.lowerCase(v) === 'yay') this._isYay = true;
        if(_.lowerCase(v) === 'nay') this._isYay = false;

    }

    voteDisplayEnglish(){
        if(this._isYay) return 'Yay';

        if(! this._isYay) return 'Nay';
    }


    /**
     * For some reason I'm sticking with sending a string to the
     * server. This sets it.
     * @returns {string}
     */
    get voteServerString(){
        if(this._isYay) return 'yay';

        if(! this._isYay) return 'nay';
    }



}
