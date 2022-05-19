

export default class ResolvedClause{
    text;
    clauseId;
    amendedText;

    constructor({originalText=null, amendedText=null, clauseId=null, displayOrder=null}) {
        this.amendedText = amendedText;
        this.originalText = originalText;
        this.clauseId = clauseId;
        this.displayOrder = displayOrder;

    }



}
