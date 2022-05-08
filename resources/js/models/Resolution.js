import Motion from "./Motion";
import HtmlDiff from 'htmldiff-js';

export default class Resolution extends Motion {


    constructor({
                    id = null,
                    content = null,
                    description = null,
                    requires = 0.5,
                    type = null,
                    info = null,
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

        this.type = 'resolution';

        this.clauses = [];
        // this.id = id;
        // this.content = content;
        // this.description = description;
        // super(id, content, description);

        this.insertRegex = new RegExp('<ins class="diffins">');
        this.strikeRegex = new RegExp('<del class="diffdel"')
    }

    initializeClauses(){
        // (?<=<pre>)(.*?)(?=</pre>)
    }
    //
    // get diffTaggedText(){
    //     if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
    //     if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
    //     let me = this;
    //
    //     let diffHtml = HtmlDiff.execute(this.originalText, this.amendmentText);
    //     return diffHtml;
    //     // (?<=<pre>)(.*?)(?=</pre>)
    // }

    // /**
    //  * Determines the type of amendment.
    //  * Possible returns:
    //  *      strike
    //  *      insert
    //  *      strikeinsert
    //  * @returns {string|boolean}
    //  */
    // get amendmentType(){
    //     //dev also secondary amendment?
    //     if(this.type !== 'amendment') return false
    //     let out = '';
    //     if(this.strikeRegex.test(this.diffTaggedText)){
    //         out += 'strike';
    //     }
    //     if(this.insertRegex.test(this.diffTaggedText)){
    //         out += 'insert'
    //     }
    //     return out;
    // }

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


}
