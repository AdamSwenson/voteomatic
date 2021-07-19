<template>
    <!--   See  https://getbootstrap.com/docs/4.5/components/card/#horizontal for layout-->

    <div class="candidate-row card mb-3" style="max-width: 540px;">
        <div class="row no-gutters">
            <div class="col-md-4">

                <candidate-button :candidate="candidate"></candidate-button>

            </div>

            <div class="col-md-8">
                <div class="card-body">

                    <h5 class="card-title">{{ candidateName }}</h5>

                    <p class="card-text">
                        {{ candidateInfo }}
                    </p>
<p v-if="isWriteIn">
    <write-in-badge></write-in-badge>
</p>
                    <!--                    <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>-->
                </div>
            </div>
        </div>
    </div>
<!--    -->
<!--    <div class="card ">-->

<!--        <div class="card-body">-->


<!--        </div>-->

<!--    </div>-->
    <!--&lt;!&ndash;        &ndash;&gt;<li class="list-group-item">Cras justo odio</li>-->

</template>

<script>
import CandidateButton from "./candidate-button";
import {isReadyToRock} from "../../utilities/readiness.utilities";
import WriteInBadge from "./write-in/write-in-badge";

export default {

    name: "candidate-row",

    components: {WriteInBadge, CandidateButton},

    props: ['candidate'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        candidateName: function () {
            if (isReadyToRock(this.candidate)) return this.candidate.name;

            return '';
        },

        candidateInfo: function () {
            if (isReadyToRock(this.candidate)) return this.candidate.info;

            return '';
        },

        isWriteIn : function(){
        return isReadyToRock(this.candidate) && this.candidate.is_write_in === true;

        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
