<template>
    <div class="card">
        <div class="card-body">
<p>current motion id : {{currentId}}</p>
            <div class="test">
                <h3>Interpolating with components</h3>

                <compiled-rezzie-text html="<p>Dog is <insert-pending text='nice'></insert-pending> and stinky</p>"></compiled-rezzie-text>

                <compiled-rezzie-text html="<p>Dog is <text-styler-factory type='insert' text='nice' v-bind:amendment-id='752'></text-styler-factory> and stinky</p>"></compiled-rezzie-text>

                <!--                <div class="ithere" v-amendmentText="ithere"></div>-->
            </div>

            <div class="current">
                <h3>Current pending amendment</h3>
                <h4>Insert</h4>
                <p>The dog
                    <insert-pending text=", who is very good,"></insert-pending>
                    likes to eat dog food. In fact, it is his favorite.
                </p>

                <compiled-rezzie-text html="<p>Dog is <text-styler-factory text='nice' amendment-id='728'></text-styler-factory> and stinky</p>"></compiled-rezzie-text>


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
import CompiledRezzieText from "../compiled-rezzie-text";
import MotionMixin from "../../../mixins/motionStoreMixin";
import MeetingMixin from "../../../mixins/meetingMixin";
import motionObjectMixin from "../../../mixins/motionObjectMixin";
import {isReadyToRock} from "../../../utilities/readiness.utilities";

export default {
    name: "text-stylers",
    components: {
        CompiledRezzieText,
        StrikePending,
        InsertPending,
        StrikePendingSuperseded,
        InsertPendingSuperseded,
        InsertFailed, InsertPassed, StrikePassed, StrikeFailed
    },
    props: [],

    mixins: [MotionMixin, MeetingMixin, motionObjectMixin],

    data: function () {
        return {}
    },

    asyncComputed: {
        currentId : function(){
            if(isReadyToRock(this.motion)){
                return this.motion.id;
            }

        }
    },

    computed: {
        ithere: function () {
            return "<p>Nice dog <insert-pending text=', who is very good,'></insert-pending> is called Carlos";
        }
    },

    methods: {},

    beforeMount() {
        this.$store.commit('setPmode');

}

}
</script>

<style scoped>

</style>
