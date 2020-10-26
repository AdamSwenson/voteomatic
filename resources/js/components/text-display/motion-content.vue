<template>
    <div class="motion-content">
        {{ content }}

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
