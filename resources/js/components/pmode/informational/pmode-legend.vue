<template>
    <div class="pmode-legend ">
        <!--       &lt;!&ndash; Button trigger modal &ndash;&gt;-->
        <!--        <button type="button" class="btn btn-primary " data-bs-toggle="modal" data-bs-target="#pmodeLegendModal">-->
        <!--            Legend-->
        <!--        </button>-->

        <!--        &lt;!&ndash; Modal &ndash;&gt;-->
        <!--        <div class="modal fade" id="pmodeLegendModal" tabindex="-1" aria-labelledby="pmodeLegendModalLabel" aria-hidden="true">-->
        <!--            <div class="modal-dialog">-->
        <!--                <div class="modal-content">-->
        <!--                    <div class="modal-header">-->
        <!--                        <h5 class="modal-title" id="pmodeLegendModalLabel">What are these symbols</h5>-->
        <!--                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>-->
        <!--                    </div>-->
        <!--                    <div class="modal-body">-->

        <!--                    <div class="pmode-legend card">-->
        <!--        <div class="card-header">-->
        <!--            <h4 class="card-title">Legend</h4>-->
        <!--        </div>-->

        <!--        <div class="card-body">-->
        <div class="row">
            <div class="col-md-6">
                <h5>
                    Insert
                </h5>
                <p>
                    <insert-pending :amendment-id="777777">The current motion would insert this text
                    </insert-pending>
                </p>

                <p>
                    <insert-pending-superseded :amendment-id="88888">Another motion has taken precedence
                        over the
                        motion to insert this text
                    </insert-pending-superseded>
                </p>

                <p>
                    <insert-passed :amendment-id="666666">This text was successfully inserted
                    </insert-passed>
                </p>

                <p>
                    Someone attempted to insert text here
                    <insert-failed :amendmentId="99999">Someone tried to insert this text but failed
                    </insert-failed>
                    <br><span class="form-text">Click the icon to see the unsuccessful amendment</span>
                </p>

            </div>


            <div class="col-md-6">
                <h4>
                    Strike
                </h4>
                <p>
                    <strike-pending :amendment-id="1777777">The current motion would strike this text
                    </strike-pending>
                </p>

                <p>
                    <strike-pending-superseded :amendment-id="188888">Another motion has taken
                        precedence over the
                        motion to strike this text
                    </strike-pending-superseded>
                </p>

                <p>
                    Text was successfully removed from here
                    <strike-passed :amendment-id="1666666">This text was successfully struck
                    </strike-passed>
                    <br><span class="form-text">Click the icon to see the successful amendment</span>
                </p>

                <p>
                    <strike-failed :amendmentId="199999">Someone tried to strike this text but failed
                    </strike-failed>
                </p>
            </div>
        </div>
    </div>
    <!--                    <div class="modal-footer">-->
    <!--                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>-->
    <!--                        &lt;!&ndash;                    <button type="button" class="btn btn-primary">Save changes</button>&ndash;&gt;-->
    <!--                    </div>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->

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
