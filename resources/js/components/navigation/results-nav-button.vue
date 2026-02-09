<template>
    <div class="d-grid gap-2">
        <button class="btn btn-warning btn-lg "
                tabindex="0"
                v-on:click="handleClick"
        >Results
        </button>
    </div>
</template>

<script>
import navigationButtonMixin from '../../mixins/navigationButtonMixin';

export default {
    name: "results-nav-button",

    props: ['motion'],

    mixins: [navigationButtonMixin],

    data: function () {
        return {}
    },

    computed: {},

    methods: {


        handleClick: function () {
            this.setMotionLocal();
            this.$router.push('results');
        }

    }
}
</script>

<style scoped>

</style>
