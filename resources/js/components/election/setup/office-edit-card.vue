<template>
    <div class="office-edit">

        <div class="card-header">
            <h4 class="card-title">{{ title }}</h4>
        </div>

        <div class="card-body">
            <label for="office-name">{{ subsidiaryTypeCapitalized }} name</label>

            <div class="input-group mb-3">
                <input type="text" class="form-control" id="office-name" v-model="officeName">
            </div>

            <label for="office-max-winners">Max winners</label>
            <div class="input-group mb-3">
                <input type="number" class="form-control" id="office-max-winners" v-model="maxWinners">
            </div>

            <p class="text-muted">Your entries are automatically saved on the
                server as you type. You don't need to click anything when you are done.</p>
            <p class="text-muted">If you do not type anything, there will be a blank {{ type }}. Use the delete
                button below to fix this.</p>

        </div>


        <!--    <div class="card" style="width: 18rem;">-->
        <!--        <ul class="list-group list-group-flush">-->
        <!--            <li class="list-group-item">Cras justo odio</li>-->
        <!--            <li class="list-group-item">Dapibus ac facilisis in</li>-->
        <!--            <li class="list-group-item">Vestibulum at eros</li>-->
        <!--        </ul>-->
        <!--    </div>&lt;!&ndash;&ndash;&gt;-->

    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import CandidateRow from "../candidate-row";
import Payload from "../../../models/Payload";

export default {
    name: "office-edit-card",
    components: {CandidateRow},
    props: [],

    mixins: [MeetingMixin, MotionStoreMixin],


    data: function () {
        return {}
    },

    asyncComputed: {
        // officeName :

        candidates: {

            get: function () {
                let me = this;
                if (isReadyToRock(this.meeting) && isReadyToRock(this.motion)) {
                    return me.$store.getters.getCandidatesForOffice(me.motion);
                }
                return []
            },

            watch: ['motion'],

            default: []

        },

        candidatePool: {
            get: function () {
                let me = this;
                if (isReadyToRock(this.meeting) && isReadyToRock(this.motion)) {
                    return me.$store.getters.getCandidatePoolForOffice(me.motion);
                }
                return []

            },

            watch: ['motion'],

            default: []

        },

    },

    computed: {

        maxWinners: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.max_winners;
                // return ''
            },

            set: function (v) {
                window.console.log('max winners set', v);
                let pl = Payload.factory({
                    updateProp : 'max_winners',
                    updateVal : v
                });
                this.$store.dispatch('updateMotion', pl);
            }

        },


        officeName: {
            get: function () {

                if (isReadyToRock(this.motion)) return this.motion.content;

                // if (isReadyToRock(this.motion) && this.motion.content.length >0) return this.motion.content;
            // return "Set up office"
                },
            set: function (v) {
                let pl = Payload.factory({
                    updateProp : 'content',
                    updateVal : v
                });
                this.$store.dispatch('updateMotion', pl);
            }
        },

        title : function(){
            let defaultTitle = "Position/Office election setup";

            if (! isReadyToRock(this.motion) || ! isReadyToRock(this.motion.content)) return defaultTitle

            let title = "Election for ";
            return title +  this.motion.content;

        }
    },


    methods: {}

}
</script>

<style scoped>

</style>
