<template>

    <div class="card">

        <div class="card-header">
            Motions
        </div>

        <div class="card-body">
            <div class="card-text">
                <ul class="list-group list-group-flush">

                    <motion-select-area v-for="m in motions"
                                        :motion="m"
                                        :key="m.id"
                    ></motion-select-area>

                </ul>
            </div>

            <end-voting-modal></end-voting-modal>

        </div>

        <!--        <svg class="bi" width="32" height="32" fill="currentColor">-->
        <!--            <use xlink:href="bootstrap-icons.svg#heart-fill"/>-->
        <!--        </svg>-->
    </div>


</template>

<script>
import MotionSelectButton from "./motions/motion-select-button";
import MotionSelectArea from "./motions/motion-select-area";
import EndVotingModal from "./motions/end-voting-modal";
// import MeetingMixin from '../storeMixins/meetingMixin';
// import MotionMixin from '../storeMixins/motionMixin';

export default {
    name: "motions-card",
    components: {EndVotingModal, MotionSelectArea, MotionSelectButton},
    // mixins : [MotionMixin],

    asyncComputed: {
        motions: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            //Display them in FILO order
            m = _.sortBy(m, ['id']);
            m = _.reverse(m);

            return m;
        }
        // default: []

        // motions: {
        //     get: function () {
        //
        //         return this.$store.getters.getMotions;
        //     },
        //     // default: []
        // }
    },

    methods: {
        // setMotion: function (motion) {
        //     this.$store.commit('setMotion', motion);
        // }

    },

    mounted() {
        // this.$store.dispatch('loadMotionsForMeeting', )
    }
}
</script>

<style scoped>

</style>
