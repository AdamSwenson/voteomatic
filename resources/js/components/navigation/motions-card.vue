<template>

    <div class="card">

        <div class="card-header">
            Motions
        </div>

        <div class="card-body">

            <ul class="list-group list-group-flush">
                <motion-select-button v-for="m in motions" :motion="m" :key="m.id"></motion-select-button>

<!--                <li class="list-group-item">-->
<!--                    <button class="btn btn-outline-info"-->
<!--                            v-on:click="setMotion(m)"-->
<!--                    >Select-->
<!--                    </button>-->
<!--                    {{ m.content }}-->
<!--                </li>-->
            </ul>

        </div>
    </div>


</template>

<script>
import MotionSelectButton from "../controls/motion-select-button";
export default {
    name: "motions-card",
    components: {MotionSelectButton},
    computed: {
        motions: function () {
            let m = this.$store.getters.getStoredMotions;
        if(_.isUndefined(m)) return [];
        return m;
        }
        // default: []

        // motions: {
        //     get: function () {
        //
        //         return this.$store.getters.getMotions;
        //     },
        //     // default: []
        // }
    },

    methods: {
        setMotion: function (motion) {
            this.$store.commit('setMotion', motion);
        }

    },

    mounted() {
        // this.$store.dispatch('loadMotionsForMeeting', )
    }
}
</script>

<style scoped>

</style>
