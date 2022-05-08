<template>
    <div class="card">
        <div class="card-body">

            <div class="test">
                <h3>Interpolating with components</h3>
                <div class="ithere" v-html="ithere"></div>
            </div>

            <div class="current">
                <h3>Current pending amendment</h3>
                <h4>Insert</h4>
                <p>The dog
                    <insert-pending text=", who is very good,"></insert-pending>
                    likes to eat dog food. In fact, it is his favorite.
                </p>
                <h4>Strike</h4>
                <p>The dog
                    <strike-pending text=", who is very good,"></strike-pending>
                    likes to eat dog food. In fact, it is his favorite.
                </p>
            </div>

            <div class="pending">
                <div class="current">
                    <h3>Current amendment awaiting vote (during procedural)</h3>
                    <h4>Insert</h4>
                    <p>The dog
                        <insert-pending-superseded text=", who is very good,"></insert-pending-superseded>
                        likes to eat dog food. In fact, it is his favorite.
                    </p>
                    <h4>Strike</h4>
                    <p>The dog
                        <strike-pending-superseded text=", who is very good,"></strike-pending-superseded>
                        likes to eat dog food. In fact, it is his favorite.
                    </p>

                    <h3>Current amendment awaiting vote with secondary</h3>
                    <h4>Insert</h4>
                    <p>The dog
                        <insert-pending-superseded text=", who is very good,"></insert-pending-superseded>
                        likes to eat dog food. In fact, it is his favorite.
                    </p>
                    <h4>Strike</h4>
                    <p>The dog
                        <strike-pending-superseded text=", who is very good,"></strike-pending-superseded>
                        likes to eat dog food. In fact, it is his favorite.
                    </p>
                </div>

            </div>

        </div>


        <div class="pastPassed">
            <h3>Successful amendment</h3>
            <h4>Insert</h4>
            <p>The dog
                <insert-passed text=", who is very good,"></insert-passed>
                likes to eat dog food. In fact, it is his favorite.
            </p>

            <h4>Strike</h4>
            <p>The dog
                <strike-passed text=", who is very good,"></strike-passed>
                likes to eat dog food. In fact, it is his favorite.
            </p>


        </div>

        <div class="pastFailed">
            <h3>Failed amendment</h3>

            <h4>Insert</h4>
            <p>The dog
                <insert-failed text=", who is very good,"></insert-failed>
                likes to eat dog food. In fact, it is his favorite.
            </p>

            <h4>Strike</h4>
            <p>The dog
                <strike-failed text=", who is very good,"></strike-failed>
                likes to eat dog food. In fact, it is his favorite.
            </p>

        </div>


    </div>

</template>

<script>
import StrikeFailed from "./strike-failed";
import StrikePassed from "./strike-passed";
import InsertPassed from "./insert-passed";
import InsertFailed from "./insert-failed";
import InsertPendingSuperseded from "./insert-pending-superseded";
import StrikePendingSuperseded from "./strike-pending-superseded";
import InsertPending from "./insert-pending";
import StrikePending from "./strike-pending";

export default {
    name: "text-stylers",
    components: {
        StrikePending,
        InsertPending,
        StrikePendingSuperseded,
        InsertPendingSuperseded,
        InsertFailed, InsertPassed, StrikePassed, StrikeFailed
    },
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        ithere: function () {
            return "<p>Nice dog <insert-pending text=', who is very good,'></insert-pending> is called Carlos";
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
