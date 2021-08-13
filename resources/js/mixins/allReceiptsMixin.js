/**
 * This is used by utilities which format and do something
 * with all the receipts stored on the client
 */
module.exports = {

    asyncComputed: {
        allVotes: function () {
            return this.$store.getters.getUsersCastVotes;
        },

        heading: function(){
            return `${this.meetingName}\n${this.meetingDate}\n\n`;
        },

        currentTime : function(){
            let date = new Date();
            return date.getFullYear() + "-" + (date.getMonth() + 1) + "-" + date.getDate() + " " +  date.getHours() + ":" + date.getMinutes() + ":" + date.getSeconds();
        },

        footer : function(){
            return `\n\n\nReceipts as of: ${this.currentTime}\nReceipts will be missing if the browser was refreshed.\nSince your user id is not stored with the receipt, missing receipts cannot be recovered.`;
        },

        text: function () {
            let me = this;
            let t = '';
            t += this.heading;

            _.forEach(this.allVotes, (vote) => {
                let motion = me.$store.getters.getMotionById(vote.motionId);

                let e = `\n${motion.content}\n${vote.receipt}\n`;

                t += e;
            });

            t += this.footer;

            return t;

        }

    },

    computed: {},

    methods: {}

};
