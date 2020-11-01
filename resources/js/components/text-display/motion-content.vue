<template>

    <div class="motion-content">

        <blockquote class="blockquote mb-0">
        {{ content }}
        </blockquote>

    </div>
</template>

<script>
export default {
    name: "motion-content",
    props: ["motion", "isReady"],
    computed: {
        content: function () {
            if(! this.isReady){
                return "Please wait while your ballot loads....."
            }

            return this.motion.content;
        }
    }
}
</script>

<style scoped>

</style>
