import IModel from "./IModel";

export default class Vote extends IModel {

    /**
     * Create a new motion
     * @param params
     */
    constructor(isYay, receipt, id=null) {
        super();
        this.isYay = isYay;
        this.receipt = receipt;
        this.id = id;

    }

    voteEnglish(){
        if(this.isYay) return 'Yay';

        if(! this.isYay) return 'Nay';

        //no abstentions!
    }

}
