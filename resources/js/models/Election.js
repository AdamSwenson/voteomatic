import Meeting from "./Meeting";

export default class Election extends Meeting {

    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor({id=null, name=null, date=null}) {
        super(id, name, date);

        /** The string used on buttons etc */
        this.type = 'election';

        /**
         * What the basic things we operate on are called.
         * Again used for buttons etc
         */
        this.subsidiaryType = 'office';
    }

}
