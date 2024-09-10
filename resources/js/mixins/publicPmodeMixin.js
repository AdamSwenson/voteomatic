/**
 *
 */
module.exports = {

    asyncComputed: {
        isInPublicPmode : function(){
            return this.$store.getters.isInPublicPmode;
        },

        /**
         * Id of the motion whose accordion is open
         */
        openMotionId: function () {
            return this.$store.getters.getOpenMotionId;
        },

    },

    // computed: {},

    // methods: {}

};
