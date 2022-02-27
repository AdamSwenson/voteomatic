<template>
    <div class="max-winners-instruction">
        <div v-show="showInstructions"
             class="alert alert-primary"
             role="alert"
        >Please vote for up to {{ maxWinners }}
        </div>
    </div>
</template>

<script>
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MotionStoreMixin from "../../../mixins/motionStoreMixin"

export default {
    name: "max-winners-instruction",

    props: [],

    mixins: [MotionStoreMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        maxWinners: {
            get: function () {
                if (isReadyToRock(this.motion)) return this.motion.max_winners;
            },

            default: null

        },

        showInstructions: function () {
            return isReadyToRock(this.maxWinners) && ! this.$store.getters.showOverSelectionWarningForActiveMotion;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
