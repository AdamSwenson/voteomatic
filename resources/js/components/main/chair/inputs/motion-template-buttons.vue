<template>

    <div class="motion-template-buttons">
        <h5 class="card-subtitle">Create motion from template</h5>

        <motion-template-button v-for="t in templates"
                                :template="t"
                                :key="t.name"
                                v-on:motion-created="handleMotionCreation"
        ></motion-template-button>


    </div>


</template>

<script>
import MotionTemplateButton from "./motion-template-button";

export default {
    name: "motion-template-buttons",
    components: {MotionTemplateButton},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    computed: {
        templates: function () {
            let d = this.$store.getters.getStandardMotionDefinitions;
            return _.sortBy(d, ['name']);
        }
    },
    methods: {
        handleMotionCreation: function () {
            window.console.log('Motion created from template');
        }
    }

}
</script>

<style scoped>

</style>
