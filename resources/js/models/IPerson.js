import {isReadyToRock} from "../utilities/readiness.utilities";
import IModel from "./IModel";
/**
 * The base class for all models
 */
export default class IPerson extends IModel {

    get name() {
        return this.first_name + " " + this.last_name ;
    };

    get nameAndInfo(){
        let n =  this.name ;

        if(! isReadyToRock(this.info)) return n;

        let me = this;

        let other = ''

        _.forEach(this.info, (v, k) => {
            other += me.info[k]
            other += ' '
        });
        if (other.length > 0){
            return n + " (" + other + ")";
        }
        return n;
    };

    get nameAndInfoHTML(){
        let n = '<span class="personName">' + this.name + '</span>';

        if(! isReadyToRock(this.info)) return n;

        let me = this;

        let other = ''

        _.forEach(this.info, (v, k) => {
            other += me.info[k]
            other += '  <br>'
        });
        if (other.length > 0){
            return n + "<br>" + other;
            // return n + "" + other + "";

        }
        return n;
    };

    /**
     * Returns a list of all the values
     * in the info dict.
     */
    get infoAsList(){
        return _.values(this.info);
        // _.forEach(this.info, (v, k) => {
        //     other += me.info[k]
        //     other += ' '
        // });
    }


    getInfoField(fieldName){
        if(! isReadyToRock(this.info) || !isReadyToRock(this.info[fieldName])) return ''
        return this.info[fieldName];
    }

    setInfoField(fieldName, val){
        this.info[fieldName] = val;
    }


};
