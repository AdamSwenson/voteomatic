import Motion from "./Motion";

/**
 * A Motion to elect some folks
 */
export default class Office extends Motion {


    constructor({id = null, content = null, description = null, max_winners=null})
    {
        super({id, content, description, max_winners});

        this.type = 'election';
        //Otherwise the controller will not send the office
        //when we ask for all motions
        this.seconded = true;

        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);


    }
}

