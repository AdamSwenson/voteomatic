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
        </div>
    </div>


</template>

<script>
import MotionSelectButton from "./motions/motion-select-button";
import MotionSelectArea from "./motions/motion-select-area";
// import MeetingMixin from '../storeMixins/meetingMixin';
// import MotionMixin from '../storeMixins/motionMixin';

export default {
    name: "motions-card",
    components: {MotionSelectArea, MotionSelectButton},
    // mixins : [MotionMixin],
    computed: {
        motions: function () {
            let m = this.$store.getters.getStoredMotions;
            if (_.isUndefined(m)) return [];
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
