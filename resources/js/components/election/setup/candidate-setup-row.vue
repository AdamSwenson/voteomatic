<template>
    <li class="list-group-item"
    v-if="showRow"
    >
        <button class="btn "
                v-bind:class="styling"
                v-on:click="handleClick"
        >{{ label }}
        </button>
        {{ candidate.name }}
    </li>


</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import {getById} from "../../../utilities/object.utilities";

export default {
    name: "candidate-setup-row",

    props: ['candidate', 'isPool'],

    mixins: [],

    data: function () {
        return {
            events: 0
        }
    },

    asyncComputed: {
        label: function () {
            if (this.isPool) {
                return this.selected ? 'Selected' : 'Select';
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
            let c = getById(this.candidates, this.candidate.id);
        return isReadyToRock(c);
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

        showRow : function (){
            if(this.isPool){
              return !  this.isCandidate;
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

            if (!this.selected) {
                //Make them into a candidate
                // let data = {name: this.candidate.name, info: this.candidate.info, motionId : this.candidate}
                window.console.log('add', 'candidate-setup-row button clicked for ', this.candidate.name);

                this.$store.dispatch('addOfficialCandidateToOfficeElection', this.candidate);
            } else {
                //remove them as a candidate
                window.console.log('remove', 'candidate-setup-row button clicked for ', this.candidate.name);
                this.$store.dispatch('removeCandidate', this.candidate);
            }
            this.events += 1;
            this.$emit('selection');

        }
    }
}
</script>

<style scoped>

</style>
