import Motion from "./Motion";

export default class Resolution extends Motion {


    constructor({id = null, content = null, description = null, info = null}) {
        super({id, content, description, info});

        this.type = 'resolution';

        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);
    }


    get title(){
        return this.info.title;
    }

    set title(v){
        this.info.title=v;
    }

    get resolutionIdentifier(){
        return this.info.resolutionIdentifier;
    }

    set resolutionIdentifier(v){
        this.info.resolutionIdentifier = v;
    }



}
