import IModel from "./IModel";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Motion extends IModel {


    /**
     * Create a new motion
     * NB, is_complete is the way it arrives from the server
     * @param params
     */
    constructor({id=null, content=null, description=null,
                    requires=0.5,
                    type=null,
        info=null,
                    is_complete=null,
                    is_voting_allowed=null,
                    is_resolution=null,
                    applies_to=null,
                    seconded=null,
                    superseded_by=null,
                    debatable=null,
                    max_winners=null}) {
        super();
        this.id = id;
        this.info = info;

        //todo hack because seem to be having trouble typecasting to boolean when get from server
        this.is_resolution =  is_resolution === 1 ? true : is_resolution;

        //if it is subsidiary, this is the motion
        this.appliesTo = applies_to;
        this.applies_to = applies_to;
        /** The text of the motion */
        this.content = content;
        /** Optional information about it*/
        this.description = description;
        this.debatable = debatable;
        /** Whether voting is complete */
        this.isComplete = is_complete;
        /** Whether members may vote on it at the current time*/
        this.is_voting_allowed = is_voting_allowed;
        /** Only used in elections */
        this.max_winners = max_winners;
        this.requires = _.toNumber(requires);
        this.seconded = seconded;
        this.superseded_by = superseded_by;
        /** If the motion is an amendment, this will
         * hold the html marked up text  */
        this.taggedAmendmentText = null;
        this.type = type;

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

    /**
     * Whether this is a multiple line html formatted text object
     * where formatting is important
     * @returns {boolean}
     */
    get isResolution(){
        return isReadyToRock(this.is_resolution) && this.is_resolution === true;
    }

    set isResolution(v){
        this.is_resolution = v;
     }

    /**
     * Whether users are currently allowed to vote
     * @returns {boolean}
     */
    get isVotingAllowed(){
        return isReadyToRock(this.is_voting_allowed) && this.is_voting_allowed === true;
    }


    set isVotingAllowed(v){
        return this.is_voting_allowed = v;
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

    /**
     * Since the thing we'll want to display in things like the downloaded
     * receipts differs depending on
     * the sort of thing voted upon, this returns the relevant text
     */
    get displayName(){
        if(isReadyToRock(this.type) && this.type === 'proposition' && isReadyToRock(this.info, 'name')) return this.info.name;

        return this.content;
    }
};
