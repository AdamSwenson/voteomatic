<template>

    <div class="motion-results">


        {{ results }}

        <button type="button" class="btn btn-warning"
                v-on:click="handleClick"
        >Release results</button>
    </div>


</template>

<script>
import * as routes from "../routes";

export default {
    name: "results-page",

    asyncComputed: {

        results: function () {

            let url = routes.results.getResults(this.motion.id);

            return new Promise((resolve, reject) => {
                Vue.axios.get(url, data).then((response) => {
                    return resolve(response.data);
                });

            });
        }
    },

    methods : {
        releaseResults : function(){
        }

    }

}
</script>

<style scoped>

</style>
