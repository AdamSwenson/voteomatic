import Motion from "./Motion";

/**
 * A Motion to elect some folks
 */
export default class Proposition extends Motion {


    constructor({id = null, content = null, description = null, info = null}) {
        //If we have a brand new proposition, initialize
        //the name so that can update it
        if(_.isNull(info)){
            info = {name : ''}
        }

        super({id, content, description, info});

        //will have html fields
        this.is_resolution = true;

        this.type = 'proposition';

        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);

    }

    get name() {
        return this.info.name;
    }

    set name(v) {
        this.info.name = v;
    }
}

