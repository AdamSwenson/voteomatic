<template>
<div class="whatis card">
    <div class="card-header">
        <h4 class="card-title">WTF am I looking at?</h4>
    </div>

    <div class="card-body">
        <p class="card-text">The ASCSU l-o-v-e-s amending amendments. That often creates confusion about where we are. This is a very early version of a way to help everyone
            follow along. It is built atop the program I created for my campus senate to securely and anonymously vote during meetings and conduct elections during the pandemic.</p>
        <p>The display on the right (Motion Stack) is the way the system normally works. It shows each motion (main, procedural, or amendment) in reverse chronological order ---the very first motion made during the plenary is at the bottom. The current
            pending motion is at the top. This display should almost always be correct (though it sometimes displays invisible html formatting tags). Unfortunately, it can be hard to interpret when things
            get complicated. </p>
        <p>The display on the left (Amendment history) tries to show at a glance every amendment which has been considered for the motion (both passed and failed). Since this is not how the system is
            designed to work, building it has been a nightmare (tons of
            regular expressions [shudder] implemented in javascript [yuck]).  </p>
    </div>
    </div>
</template>

<script>
export default {
    name: "whatis",

    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {},

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
