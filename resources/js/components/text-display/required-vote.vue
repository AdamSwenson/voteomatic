<template>

    <div class="required-vote">
        <p>This motion requires <strong>{{requiredText}}</strong> to pass</p>
    </div>

</template>

<script>
export default {
name: "required-vote",
    props: ["motion"],
    computed: {
        requiredText: function () {
            switch (this.motion.requires) {
                case 0.5:
                    return "a majority";
                    break;
                case 0.66:
                    return "two-thirds";
                    break;
            }
        }
    }
}
</script>

<style scoped>

</style>
