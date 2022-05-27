<template>

    <button
        class="btn btn-lg"
        v-bind:class="styling"
        v-on:click="handleClick"
    >{{label}}
    </button>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "candidate-button",


    props: ['candidate'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        label: function(){
            return  this.selected ? 'Selected'  : 'Select';
        },

        // selectedCandidates: function(){
        //     return this.$store.getters.getSelectedCandidatesForActiveMotion;
        // },

        selected: {
            get:function () {
                return this.$store.getters.isCandidateSelectedInActiveMotion(this.candidate);

//             // let selectedCandidates =
//                 if(!  isReadyToRock(this.selectedCandidates) ) return false;
//
//             // window.console.log('selected', this.selectedCandidates);
//             if(this.selectedCandidates.length === 0) return false;
// // return _.includes(selectedCandidates, this.candidate);
//            return this.selectedCandidates.indexOf(this.candidate) > -1;

        },
            // watch :['selectedCandidates'],
        default : []
        },

        styling: {
            get: function () {
                if (this.selected) return "btn-info";

                return "btn-outline-info";
            },
            // watch: ['selected']
        },
    },

    computed: {},

    methods: {

        handleClick: function () {
            window.console.log('Vote button clicked for ', this.candidate.name);
            let me = this;

            if(this.selected){

                this.$store.dispatch('unselectCandidate', this.candidate);
                window.console.log('unselect');
            }else{
                window.console.log('select');
                this.$store.dispatch('selectCandidate', this.candidate);
            }

// });
// let pl = {
//     motionId: this.candidate.motion_id,
//     candidateId : this.candidate.id
// };
//             this.$store.dispatch('castElectionVote', pl)
//                 .then((response) => {
//                 window.console.log(response.data);
//
//             });
//
        }
    }

}
</script>

<style scoped>
.btn-outline-info {
    color : #385c79;
    border-color :  #385c79;
}
</style>
