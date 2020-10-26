<template>

    <div class="description">
        <p>{{ description }}</p>
    </div>

</template>

<script>

//Handles any additional instructions particular to this motion
export default {
    name: "motion-description",
    props: ["motion"],
    computed: {
        description: function () {
            return this.motion.description;


        }
    }
}
</script>

<style scoped>

</style>
