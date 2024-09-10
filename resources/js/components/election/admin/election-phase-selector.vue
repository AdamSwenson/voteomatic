<template>
    <div class="btn-group"
         role="group"
         aria-label="Toggle buttons for controlling election phase"
    >

        <input type="radio" class="btn-check" name="setup" id="phaseSetup" value="setup" v-model='selectedPhase'
               autocomplete="off">
        <label class="btn btn-outline-primary" for="phaseSetup">Setup</label>

        <input type="radio" class="btn-check" name="nominations" id="phaseNominations" value="nominations"
               v-model='selectedPhase' autocomplete="off">
        <label class="btn btn-outline-primary" for="phaseNominations">Nominations</label>

        <input type="radio" class="btn-check" name="voting" id="phaseVoting" value="voting" v-model='selectedPhase'
               autocomplete="off">
        <label class="btn btn-outline-primary" for="phaseVoting">Voting</label>

        <input type="radio" class="btn-check" name="closed" id="phaseClosed" value="closed" v-model='selectedPhase'
               autocomplete="off">
        <label class="btn btn-outline-primary" for="phaseClosed">Closed</label>

        <input type="radio" class="btn-check" name="results" id="phaseResults" value="results" v-model='selectedPhase'
               autocomplete="off">
        <label class="btn btn-outline-primary" for="phaseResults">Results</label>

    </div>
</template>

<script>
import Payload from "../../../models/Payload";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "election-phase-selector",

    props: ['meeting'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        selectedPhase: {
            get: function () {
                if (isReadyToRock(this.meeting)) {
                    return this.meeting.phase
                }
            },
            set: function (v) {
                // dev NB we are not using the dedicated election admin controller to
                // change phase. Not sure if this is a problem.
                this.$store.dispatch('updateMeeting', Payload.factory(
                    {
                        'updateProp': 'phase',
                        'updateVal': v
                    }));
            }
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
