import IModel from "./IModel";

export default class Motion extends IModel {

    /**
     * Create a new motion
     * NB, is_complete is the way it arrives from the server
     * @param params
     */
    constructor({id, content, description, requires, type, is_complete}) {
        super();
        this.id = id;
        this.content = content;
        this.description = description;
        this.requires = _.toNumber(requires);
        this.type = type;
        this.isComplete = is_complete;

        this.types = ['main', 'amendment'];

        //todo
        this.type = 'main';


        this.requirementMap = [
            {'percentage': 0.5, 'english': 'Majority'},
            {'percentage': 0.66, 'english': 'Two-thirds'}
            ];


        //used for selectors in creating motion
        this.requiredVotes = {
            0.5: 'Majority', 0.66: 'Two-thirds'
        };

        switch (this.requires) {
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

    getEnglishRequiresForNumeric(num){
        return this.requirementMap.indexOf((d) => {
            return d.percentage === num;
        })
    }

    getNumericRequiresFromEnglish(text){
        return this.requirementMap.indexOf((d) => {
            return d.english === text;
        })

    }
};
