<template>
    <!--   See  https://getbootstrap.com/docs/4.5/components/card/#horizontal for layout-->

    <div class="candidate-row card mb-3" style="max-width: 540px;">
        <div class="row g-0">

            <div class="col-md-4">
                <candidate-button :candidate="candidate"></candidate-button>
            </div>

            <div class="col-md-8">
                <div class="card-body">

                    <h4 class="card-title">{{ candidateName }}</h4>

                    <candidate-info-item v-for="field in infoFields"
                                         :key="field"
                                         :field-name="field"
                                         :candidate="candidate"
                    ></candidate-info-item>

                    <p v-if="isWriteIn">
                        <write-in-badge></write-in-badge>
                    </p>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
import CandidateButton from "./candidate-button";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import WriteInBadge from "../write-in/write-in-badge";
import CandidateInfoItem from "./candidate-info-item";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";

export default {

    name: "candidate-row",

    components: {CandidateInfoItem, WriteInBadge, CandidateButton},

    props: ['candidate'],

    mixins: [MeetingMixin, MotionStoreMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        candidateName: function () {
            if (isReadyToRock(this.candidate)) return this.candidate.name;

            return '';
        },

        /**
         * These are the fields which should be shown
         *
         * dev To be fixed in VOT-141
         */
        infoFields: function () {
            //dev This should eventually be used see VOT-141
            // if(isReadyToRock(this.meeting)) return this.meeting.candidateFields;
            if (!isReadyToRock(this.candidate) || this.candidate.info.length === 0) return []

            //dev Don't need the unique part, just the sort to get the link last
            let k =  _.keys(this.candidate.info);
            k.sort();
            return k;
        },

        candidateInfo: function () {
            if (!isReadyToRock(this.candidate)) return '';

            this.candidate.info;

            return '';
        },

        isWriteIn: function () {
            return isReadyToRock(this.candidate) && this.candidate.is_write_in === true;

        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
