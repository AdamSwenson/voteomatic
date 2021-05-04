import Motion from "./Motion";

/**
 * A Motion to elect some folks
 */
export default class Office extends Motion {


    constructor({id = null, content = null, description = null})
    {
        super({id, content, description});

        this.type = 'office';

        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);


    }
}

