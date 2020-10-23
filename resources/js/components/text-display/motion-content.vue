<template>
    <div class="motion-content">
        <h3>{{ content }}</h3>

    </div>
</template>

<script>
export default {
    name: "motion-content",
    props: ["motion"],
    computed: {
        content: function () {
            return this.motion.content;


        }
    }
}
</script>

<style scoped>

</style>
