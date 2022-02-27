const {isReadyToRock} = require("../utilities/readiness.utilities");
/**
 * This is used by utilities which format and do something
 * with all the receipts stored on the client
 */
module.exports = {

    asyncComputed: {
        allVotes: function () {
            return this.$store.getters.getUsersCastVotes;
        },

        heading: function () {
            return `${this.meetingName}\n${this.meetingDate}\n\n`;
        },

        currentTime: function () {
            let date = new Date();
            return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " + date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
        },

        footer: function () {
            return `\n\n\nReceipts as of: ${this.currentTime}\nReceipts will be missing if the browser was refreshed.\nSince your user id is not stored with the receipt, missing receipts cannot be recovered.`;
        },

        /**
         * Used to govern things like whether to show the download
         * receipts buttons.
         * Returns true if there's at least one receipt available
         */
        isReceiptsAvailable: function () {
            if(! isReadyToRock(this.allVotes)) return false;

            let val = false;
            if (this.allVotes.length > 0) {

                _.forEach(this.allVotes, (v) => {
                    if (isReadyToRock(v, 'receipt')) {
                        val = true;
                    }
                });

            }
            return val
        },

        /**
         * The formatted text that will be downloaded, copied to clipboard, etc.
         * @returns {string}
         */
        text: function () {
            let me = this;
            let t = '';
            t += this.heading;

            _.forEach(this.allVotes, (vote) => {
                let motion = me.$store.getters.getMotionById(vote.motionId);

                let e = `\n${motion.displayName}\n${vote.receipt}\n`;

                t += e;
            });

            t += this.footer;

            return t;

        }

    },

    computed: {},

    methods: {

        // /**
        //  * Since the thing we'll want to list differs depending on
        //  * the sort of thing voted upon, this returns the relevant text
        //  * @param motion
        //  * @returns {*}
        //  */
        // formatName : function(motion){
        //     if(motion.type === 'proposition' && isReadyToRock(motion, 'info')) return motion.info.name;
        //
        //     return motion.content
        //
        // }
    }

};
