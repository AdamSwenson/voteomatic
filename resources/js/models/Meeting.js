import IModel from "./IModel";

export default class Meeting extends IModel {

    /**
     * Create a new motion
     * @param id
     * @param name
     * @param date
     */
    constructor(id, name, date ) {
        super();
        this.name = name;
        this.id = id;
        this.date = date;

    }
};
