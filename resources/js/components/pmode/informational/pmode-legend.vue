<template>
    <div class="pmode-legend card">
        <div class="card-header">
            <h4 class="card-title">Legend</h4>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5 class="card-subtitle">
                        Insert
                    </h5>
                    <p class="card-text">
                        <insert-pending :amendment-id="777777">The current motion would insert this text </insert-pending>
                    </p>

                    <p class="card-text">
                        <insert-pending-superseded :amendment-id="88888">Another motion has taken precedence over the
                            motion to insert this text
                        </insert-pending-superseded>
                    </p>

                    <p class="card-text">
                        <insert-passed :amendment-id="666666">This text was successfully inserted</insert-passed>
                    </p>

                    <p class="card-text">
                        Someone attempted to insert text here <insert-failed :amendmentId="99999">Someone tried to insert this text but failed</insert-failed>
                        <br><span class="form-text">Click the icon to see the unsuccessful amendment</span>
                    </p>

                </div>


                <div class="col-md-6">
                    <h4 class="card-subtitle">
                        Strike
                    </h4>
                    <p class="card-text">
                        <strike-pending :amendment-id="1777777">The current motion would strike this text</strike-pending>
                    </p>

                    <p class="card-text">
                        <strike-pending-superseded :amendment-id="188888">Another motion has taken precedence over the
                            motion to strike this text
                        </strike-pending-superseded>
                    </p>

                    <p class="card-text">
                       Text was successfully removed from here <strike-passed :amendment-id="1666666">This text was successfully struck</strike-passed>
                        <br><span class="form-text">Click the icon to see the successful amendment</span>
                    </p>

                    <p class="card-text">
                        <strike-failed :amendmentId="199999">Someone tried to strike this text but failed
                        </strike-failed>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import TextStylerFactory from "../text-stylers/text-styler-factory";
import InsertPending from "../text-stylers/insert-pending";
import InsertFailed from "../text-stylers/insert-failed";
import InsertPendingSuperseded from "../text-stylers/insert-pending-superseded";
import InsertPassed from "../text-stylers/insert-passed";
import StrikePending from "../text-stylers/strike-pending";
import StrikePendingSuperseded from "../text-stylers/strike-pending-superseded";
import StrikeFailed from "../text-stylers/strike-failed";
import StrikePassed from "../text-stylers/strike-passed";

export default {
    name: "pmode-legend",
    components: {
        StrikePassed,
        StrikeFailed,
        StrikePendingSuperseded,
        StrikePending, InsertPassed, InsertPendingSuperseded, InsertFailed, InsertPending, TextStylerFactory
    },
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
