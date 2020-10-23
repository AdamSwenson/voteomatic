import IModel from "./IModel";

export default class Motion extends IModel {


    /**
     * Create a new motion
     * @param params
     */
    constructor(id, content, description, requires) {
        super();
        this.id = id;
        this.content = content;
        this.description = description;
        this.requires = requires;

    }
};
