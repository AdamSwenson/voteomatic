import IModel from "./IModel";

export default class Motion extends IModel {

    /**
     * Create a new motion
     * NB, is_complete is the way it arrives from the server
     * @param params
     */
    constructor({id=null, content=null, description=null, requires=0.5, type=null, is_complete=null, applies_to=null, seconded=null, superseded_by=null, debatable=null}) {
        super();
        this.id = id;
        this.content = content;
        this.description = description;
        this.superseded_by = superseded_by;
        this.debatable = debatable;
        //if it is subsidiary, this is the motion
        this.appliesTo = applies_to;
        this.applies_to = applies_to;
        this.seconded = seconded;
        this.requires = _.toNumber(requires);

        this.type = type;
        this.isComplete = is_complete;

        /** If the motion is an amendment, this will
         * hold the html marked up text  */
        this.taggedAmendmentText = null;

        this.types = ['main', 'amendment'];

        /**
         * Motions with these types will be labeled as amendments
         * todo It would be better to load this from the server so stays in sync
         * @type {string[]}
         */
        this.amendmentNames = ['amendment',
            'primary-amendment',
            'amendment-secondary'];

        this.proceduralMotionNames = [
            'privileged',
            'procedural-main',
            'procedural-subsidiary',
            'incidental'
        ];

        //todo
//        this.type = 'main';


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

    isSuperseded(){
        return ! _.isNull(this.superseded_by);
    }

    isAmendment() {
        return _.includes(this.amendmentNames, this.type);
    }

    /**
     * Whether the motion type is on the procedural motion
     * names list
     */
    isProcedural(){
        return _.includes(this.proceduralMotionNames, this.type);
    }

    isProceduralSubsidiary(){
        return this.type === 'procedural-subsidiary';
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
