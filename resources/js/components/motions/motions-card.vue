<template>

    <div class="card">

        <div class="card-header">
            {{ meetingDate }}
        </div>

<!--        <div class="card-body">-->
<!--            <div class="card-text">-->
<!--                <ul class="list-group list-group-flush">-->

<!--                    <motion-select-area :motion="motion"></motion-select-area>-->

<!--                </ul>-->
<!--            </div>-->
<!--        </div>-->

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

    </div>


</template>

<script>
import MotionSelectButton from "./motion-select-button";
import MotionSelectArea from "./motion-select-area";
import EndVotingModal from "./end-voting-modal";
import MeetingMixin from '../../mixins/meetingMixin';
import MotionMixin from '../../mixins/motionMixin';

export default {
    name: "motions-card",
    components: {EndVotingModal, MotionSelectArea, MotionSelectButton},
    mixins: [MotionMixin, MeetingMixin],

    asyncComputed: {
        motions: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];

            //Display them in FILO order
            m = _.sortBy(m, ['id']);
            m = _.reverse(m);

            return m;
        },


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
