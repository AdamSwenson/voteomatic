<template>
    <li class="list-group-item"
        v-if="showRow"
    >
        <button class="btn "
                v-bind:class="styling"
                v-on:click="handleClick"
        >{{ label }}
        </button>
        {{ candidate.nameAndInfo }}
    </li>


</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import {getById} from "../../../utilities/object.utilities";
import MeetingMixin from "../../../mixins/meetingMixin";
import MotionStoreMixin from "../../../mixins/motionStoreMixin";
import MeetingPropertiesMixin from "../../../mixins/meetingPropertiesMixin";

export default {
    name: "candidate-setup-row",

    props: ['candidate', 'isPool'],

    mixins: [MotionStoreMixin],


    data: function () {
        return {
            events: 0
        }
    },

    asyncComputed: {
        label: function () {
            if (this.isPool) {
                return this.selected ? 'Selected' : 'Nominate';
            }
            //If we are just the list of candidates
            return 'Remove'

        },

        candidates: {
            get: function () {

                return this.$store.getters.getCandidatesForOffice(this.candidate.motion_id);
            },
            default: []
        },

        isCandidate: function () {
            return this.$store.getters.isPoolMemberACandidate(this.motion, this.candidate);
            // let c = getById(this.candidates, this.candidate.id);
            // return isReadyToRock(c);
        },

        selected: {
            get: function () {
                if (!isReadyToRock(this.candidates)) return false;

                // window.console.log('selected', this.selectedCandidates);
                if (this.candidates.length === 0) return false;
// return _.includes(selectedCandidates, this.candidate);
                //window.console.log(this.candidates.indexOf(this.candidate) > -1);
                return this.candidates.indexOf(this.candidate) > -1;

            },
            watch: ['motion', 'candidates', 'events'],
            // default: false
        },

        showRow: function () {
            if (this.isPool) {
                return !this.$store.getters.isPoolMemberACandidate(this.motion, this.candidate);
//              return !  this.isCandidate;
            }
            return true;
        },

        styling: {
            get: function () {
                //If looking at the list of actual candidates
                if (!this.isPool) return 'btn-danger'

                //If looking at pool
                if (this.selected) return "btn-info";

                return "btn-outline-info";
            },
            // watch: ['selected']
        },
    },


    methods: {

        handleClick: function () {
let me = this;
            if (!this.selected) {
                //Make them into a candidate
                // let data = {name: this.candidate.name, info: this.candidate.info, motionId : this.candidate}
                window.console.log('add', 'candidate-setup-row button clicked for ', this.candidate.name);

                this.$store.dispatch('addCandidate', this.candidate);
            } else {
                //remove them as a candidate
                window.console.log('remove', 'candidate-setup-row button clicked for ', this.candidate.name);
                this.$store.dispatch('removeCandidate', this.candidate).then(function(){
                    me.events += 1;

                });
            }
            this.events += 1;

            this.$emit('selection');

        }
    }
}
</script>

<style scoped>

</style>
