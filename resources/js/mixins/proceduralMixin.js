module.exports = {

    asyncComputed : {

        /**
         * If the motion is a subsidiary procedural one,
         * this will return the degree of the motion it
         * applies to as follows:
         *      0 : It applies to a main motion
         *      1 : It applies to a primary amendment or procedural motion
         *      2 : It applies to a secondary amendment or procedural motion
         *
         * todo Other possibilities e.g., a procedural motion on an amendment to a procedural motion
         */
        pendingMotionDegree: function(){
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion) && this.isProcedural) {

                let pendingMotion = this.$store.getters.getMotionById(this.motion.applies_to);

                //It isn't attached to another motion so it must be main
                if(_.isUndefined(pendingMotion)) return 0;

                if(pendingMotion.isProcedural()){
                    //it is a second order procedural motion
                    return 2;
                }

                if(pendingMotion.isAmendment()){

                    //we need to check if it is primary or secondary
                    let amendmentParent = this.$store.getters.getMotionById(pendingMotion.applies_to);
                    if(amendmentParent.isAmendment()){
                        //it is secondary
                        //todo sure would be nice to do this centrally or have it set on the model
                        return 2
                    }
                    // it is primary
                    return 1
                }

                // it is presumably main or procedural main
                return 0;

            }

        },

        isProcedural: function () {
            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isProcedural();
            }
        },

        /**
         * Whether this is a procedural motion which applies to a
         * particular motion (e.g., tabling)
         */
        isProceduralSubsidiary : function(){
            if(! this.isProcedural ) return false

            if (!_.isUndefined(this.motion) && !_.isNull(this.motion)) {
                return this.motion.isProceduralSubsidiary();
            }

        },


    }
}
