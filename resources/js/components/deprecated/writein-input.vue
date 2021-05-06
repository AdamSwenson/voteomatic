<template>


    <div class="form-group">
        <label for="writeIn">Write-in candidate name</label>
        <input type="text"
               class="form-control"
               id="writeIn"
               aria-describedby="writeInHelp"
               v-model="name"
        >
        <small id="writeInHelp" class="form-text text-muted">Enter the person's name here. If you change your mind, de-select them using the button to the left </small>
    </div>
</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";
import Payload from "../../models/Payload";

export default {
    name: "writein-input",

    props: ['candidate'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {
        name: {
            get: function () {
                if(isReadyToRock(this.candidate)) return this.candidate.name;

            },
            set: function (v) {
                let payload =  Payload.factory({
                    id : this.candidate.id,
                    updateProp : 'name',
                    updateVal : v
                })
                this.$store.dispatch('updateCandidate', payload );


            }
        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
