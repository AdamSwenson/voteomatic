import IModel from "./IModel";

export default class Vote extends IModel {

    /**
     * Create a new motion
     * @param params
     */
    constructor(isYay, receipt) {
        super();
        this.isYay = isYay;
        this.receipt = receipt;

    }

    voteEnglish(){
        if(this.isYay) return 'Yay';

        if(! this.isYay) return 'Nay';

        //no abstentions!
    }

}
