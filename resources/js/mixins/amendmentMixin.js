// const insertRegex = new RegExp('<ins class="diffins">');
// const strikeRegex = new RegExp('<del class="diffdel"');
//
// /**
//  * Determines the type of amendment.
//  * Possible returns:
//  *      strike
//  *      insert
//  *      strikeinsert
//  * @returns {string|boolean}
//  */
// export const amendmentType = function(){
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
// };
//
// /**
//  * Returns the amendment text tagged with
//  * <ins class="diffins">  and
//  * <del class="diffdel">
//  * @returns {string|*|string}
//  */
// export function diffTagText(originalText, amendmentText){
//     if (_.isUndefined(originalText) || _.isNull(originalText)) return ''
//     if (_.isUndefined(amendmentText) || _.isNull(amendmentText)) return ''
//
//     let diffHtml = HtmlDiff.execute(originalText, amendmentText);
//     return diffHtml;
//     // (?<=<pre>)(.*?)(?=</pre>)
// };


module.exports = {

    asyncComputed: {
        /**
         * In most cases will return this.motion.content.
         *
         * However, it will be overridden on the setup page by amendmentTextForSetup
         * (since the amendment object hasn't been created yet and the changes are stored locally
         * on the component)
         * @returns {string|*}
         */
        amendmentText: function () {
            //Overrides on the setup page
            if (!_.isUndefined(this.amendmentTextForSetup) && !_.isNull(this.amendmentTextForSetup)) {
                return this.amendmentTextForSetup;
            }

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.content;
            }

        },

       //  /**
       //   * Determines the type of amendment.
       //   * Possible returns:
       //   *      strike
       //   *      insert
       //   *      strikeinsert
       //   * @returns {string|boolean}
       //   */
       //  amendmentType : function(){
       //      //dev also secondary amendment?
       //      if(this.type !== 'amendment') return false
       //      let out = '';
       //      if(this.strikeRegex.test(this.diffTaggedText)){
       //          out += 'strike';
       //      }
       //      if(this.insertRegex.test(this.diffTaggedText)){
       //          out += 'insert'
       //      }
       //      return out;
       //  },
       //
       //  /**
       //   * Returns the amendment text tagged with
       //   * <ins class="diffins">  and
       //   * <del class="diffdel">
       //   * @returns {string|*|string}
       //   */
       // diffTaggedText : function(){
       //      if (_.isUndefined(this.originalText) || _.isNull(this.originalText)) return ''
       //      if (_.isUndefined(this.amendmentText) || _.isNull(this.amendmentText)) return ''
       //      let me = this;
       //
       //      let diffHtml = HtmlDiff.execute(this.originalText, this.amendmentText);
       //      return diffHtml;
       //      // (?<=<pre>)(.*?)(?=</pre>)
       //  },


        isAmendment: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isAmendment();
            }
        },

        /**
         * Whether this is an amendment to an amendment.
         * todo Make sure this doesn't also catch procedural motions like tabling
         */
        isSecondOrder: function () {
            if (!this.isAmendment) return false

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.originalMotion.isAmendment();
//                return this.$store.getters.getMotionById(this.motion.applies_to);
            }

        },

        originalMotion: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.$store.getters.getMotionById(this.motion.applies_to);
            }
        },

        /**
         * In most cases will return the content stored on the motion object this
         * amendment applies to.
         * However, it will be overridden on the setup page by originalTextForSetup
         * @returns {string|*}
         */
        originalText: function () {
            //Overrides on the setup page (since the amendment object hasn't been created yet)
            if (!_.isUndefined(this.originalTextForSetup) && !_.isNull(this.originalTextForSetup)) {
                return this.originalTextForSetup;
            }

            try {
                let orig = this.$store.getters.getMotionById(this.motion.applies_to);
                return orig.content;
            } catch (e) {
                return '';
            }
        },


    }
}
