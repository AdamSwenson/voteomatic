import Motion from "./Motion";
import HtmlDiff from 'htmldiff-js';
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Resolution extends Motion {


    /**
     *
     * @param id
     * @param content
     * @param description
     * @param requires
     * @param type Can be either resolution (if main) or amendment
     * @param info
     * @param is_complete
     * @param is_voting_allowed
     * @param is_resolution
     * @param applies_to
     * @param seconded
     * @param superseded_by
     * @param debatable
     */
    constructor({
                    id = null,
                    content = null,
                    description = null,
                    requires = 0.5,
                    type = null,
                    info = {},
                    is_complete = null,
                    is_voting_allowed = null,
                    is_resolution = null,
                    applies_to = null,
                    seconded = null,
                    superseded_by = null,
                    debatable = null,
                }) {

        super({
            id, content, description, info, requires,
            type,
            is_complete,
            is_voting_allowed,
            is_resolution,
            applies_to,
            seconded,
            superseded_by,
            debatable
        });


        this.clauses = [];
        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);

        // this.insertRegex = new RegExp('<ins class="diffins">');
        // this.strikeRegex = new RegExp('<del class="diffdel"')
    }

    /**
     * Whether this is an amendment to a resolution which
     * requires appropriate html rendering etc.
     *
     * NB, checking that the type is amendment rather than
     * looking at applies_to because the server will rely on the
     * type being amendment to respond appropriately once marked complete.
     * See VOT-193 for the issue that was caused by the type not being
     * set to amendment.
     * @returns {false|boolean|null}
     */
    get isResolutionAmendment(){
        return this.isAmendment() && this.is_resolution;
    }

    get groupId(){
        if( isReadyToRock(this.info, 'groupId')) return this.info.groupId;
    }
    set groupId(v){
        this.info.groupId = v;
    }

    get title() {
        return this.info.title;
    }

    set title(v) {
        this.info.title = v;
    }

    get resolutionIdentifier() {
        return this.info.resolutionIdentifier;
    }

    set resolutionIdentifier(v) {
        this.info.resolutionIdentifier = v;
    }

    get formattedContent(){
        return this.info.formattedContent;
    }

    set formattedContent(v){
        return this.info.formattedContent = v;
    }



}
