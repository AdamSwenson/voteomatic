<template>
    <div class="info-item" v-if="isShown">
        <p class="card-text">
            <span v-html="info"></span>
        </p>
    </div>
</template>

<script>
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

/**
 * This holds one piece of information about
 * a candidate, aside from their name.
 * It mainly exists so we can control the formatting of
 * how everything displays on the vote page.
 *
 * This is also in charge of policing whether an info
 * row displays as could be the case if a field is removed
 * in the election configuration after the candidate list has been populated.
 * That's why we pass in both the candidate and the field name.
 *
 * dev Will also handle different formatting depending on item priority once that has been added
 *
 * See VOT-252, VOT-141, VOT-252
 */
export default {
    name: "candidate-info-item",

    props: ['candidate', 'fieldName'],


    mixins: [MeetingMixin, MotionStoreMixin],
    data: function () {
        return {}
    },

    asyncComputed: {


        info: function () {
            let info = this.candidate.getInfoField(this.fieldName);

            if(this.isLink) return `<a href='${info}' target="_blank" rel="noopener noreferrer">${info}</a>`;

            return this.candidate.getInfoField(this.fieldName);
        },

        /**
         * Returns boolean of whether the item is a
         * url.
         * dev Everything around this (including display order is very hacky)
         */
        isLink: function(){
            return this.fieldName === 'link';
        },

        /**
         * Whether to display the particular item.
         * dev This should be sorted out in VOT-141
         *
         * @returns {boolean}
         */
        isShown: function () {
           return true;
           //dev Enable in VOT-141
            if (!isReadyToRock(this.candidate) || !isReadyToRock(this.fieldName)) return false;

            return _.includes(this.meeting.candidateFields, this.fieldName);

        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
