<template>
    <div class="pmode-legend card">
        <div class="card-headerq">
            <h4 class="card-title">WTF am I looking at?</h4>
        </div>

        <div class="card-body">
            <h5 class="card-subtitle">
                Insert
            </h5>
            <p class="card-text">
                <insert-pending :amendment-id="777777">This text is waiting to be inserted</insert-pending>
            </p>

            <p class="card-text">
                <insert-pending-superseded :amendment-id="88888">Another motion has taken precedence over the motion to insert this text </insert-pending-superseded>
            </p>

            <p class="card-text">
                <insert-passed :amendment-id="666666">This text was successfully inserted</insert-passed>
            </p>

            <p class="card-text">
                <insert-failed :amendmentId="99999">Someone tried to insert this text but failed</insert-failed>
            </p>

            <p class="card-text">

                <text-styler-factory
                    type="insert"
                    :amendment-id="759"
                >
                    This text is waiting to be inserted

                    <!--                <template v-slot:text>This text is waiting to be inserted</template>-->
                </text-styler-factory>

            </p>


        </div>

        <div class="card-body">
            <h4 class="card-subtitle">
                Strike
            </h4>
            <p class="card-text">
                <strike-pending :amendment-id="1777777">This text is waiting to be struck</strike-pending>
            </p>

            <p class="card-text">
                <strike-pending-superseded :amendment-id="188888">Another motion has taken precedence over the motion to strike this text </strike-pending-superseded>
            </p>

            <p class="card-text">

                <strike-passed :amendment-id="1666666">This text was successfully struck</strike-passed>
            </p>

            <p class="card-text">
                <strike-failed :amendmentId="199999">Someone tried to strike this text but failed</strike-failed>
            </p>

        </div>

    </div>
</template>

<script>
import TextStylerFactory from "./text-stylers/text-styler-factory";
import InsertPending from "./text-stylers/insert-pending";
import InsertFailed from "./text-stylers/insert-failed";
import InsertPendingSuperseded from "./text-stylers/insert-pending-superseded";
import InsertPassed from "./text-stylers/insert-passed";
import StrikePending from "./text-stylers/strike-pending";
import StrikePendingSuperseded from "./text-stylers/strike-pending-superseded";
import StrikeFailed from "./text-stylers/strike-failed";
import StrikePassed from "./text-stylers/strike-passed";
export default {
    name: "pmode-legend",
    components: {
        StrikePassed,
        StrikeFailed,
        StrikePendingSuperseded,
        StrikePending, InsertPassed, InsertPendingSuperseded, InsertFailed, InsertPending, TextStylerFactory},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
