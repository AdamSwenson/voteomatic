/**
 * For any component that needs access to the
 * current motion.
 *
 * @type {{computed: {}}}
 */
module.exports = {

    computed: {

        /**
         * The current global motion
         */
        motion: {
            get : function(){
                return this.$store.getters.getActiveMotion;
            },

            set : function(v){
                this.$store.commit('setMotion', v);

            }
        }
    }
};
