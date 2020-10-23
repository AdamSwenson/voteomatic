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

        switch(this.requires){
            case 0.5:
                this.englishRequires = 'Majority';
                break;
            case 0.66:
                this.englishRequires = 'Two-thirds';
                break
            default:
                this.englishRequires = '';
        }

    }
};
