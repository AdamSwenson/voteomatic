<template>
<div class="whatis ">
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#whatisModal">
        WTF am I looking at?
    </button>

    <!-- Modal -->
    <div class="modal fade" id="whatisModal" tabindex="-1" aria-labelledby="whatisModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="whatisModalLabel">What is this?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <h4>Why</h4>
                    <p>The ASCSU l-o-v-e-s amending amendments. That creates confusion about where we are and what's
                        in order. I've been using the app I created for my senate to keep my records as parliamentarian. This attempts
                        to share those records with everyone in real time.
                        <p>Adapting it for this purpose has been tricky. Please
                        understand that this is a <i>very</i> early version.</p>

                    <h4>What</h4>
                    <h5>Motion Stack</h5>
                    <p>The display on the right (Motion Stack) shows every motion made during the plenary
                        in reverse chronological order. The very first motion is at the bottom;
                        the current pending motion is at the top.</p>
                    <p>When the resolution is pending as the main motion, this only shows the unedited title. For amendments, it shows the changed text
                    with 2-3 words before and after for context</p>
                    <p>This is the way the system normally works. Thus this display should almost always be correct.
                        Though if you're not used to looking at it, it can be hard to interpret. (It sometimes
                        displays normally invisible html formatting tags, please ignore them).
                    </p>

                    <h5>Amendment History</h5>
                    <p>The display on the left (Amendment History) tries to show at a glance every amendment
                        which has been considered for the current resolution. Unlike the Secretary's copy, it shows
                        both passed and failed amendments.</p>
                    <p>This part is extremely buggy. I have no doubt that it will get tangled up and fail spectacularly
                        with the amendment warrens we construct. The system was not designed to enable this sort of
                        display, so building it has been a nightmare (tons of
                        regular expressions [shudder] implemented in javascript [yuck]). I hope it will nonetheless be
                    of some use.</p>

                    <h4>Disclaimers</h4>
                    <p>This is not an official record. The ASCSU's Secretary's version is the source of truth ---if it disagrees with what
                        you see here, what you see here is wrong.</p>

                    <p>This is a prototype built atop a system that wasn't designed for this use. There will likely be
                        errors. If things seem wrong, try refreshing the page. If they are still wrong, please ignore this
                        until the next resolution.</p>

                </div>

            </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
<!--                    <button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
<!--    <div class="card-header">-->
<!--        <h4 class="card-title">WTF am I looking at?</h4>-->
<!--    </div>-->

<!--    <div class="card-body">-->
<!--        <p class="card-text">The ASCSU l-o-v-e-s amending amendments. That often creates confusion about where we are. This is a very early version of a way to help everyone-->
<!--            follow along. It is built atop the program I created for my campus senate to securely and anonymously vote during meetings and conduct elections during the pandemic.</p>-->
<!--        <p>The display on the right (Motion Stack) is the way the system normally works. It shows each motion (main, procedural, or amendment) in reverse chronological order -&#45;&#45;the very first motion made during the plenary is at the bottom. The current-->
<!--            pending motion is at the top. This display should almost always be correct (though it sometimes displays invisible html formatting tags). Unfortunately, it can be hard to interpret when things-->
<!--            get complicated. </p>-->
<!--        <p>The display on the left (Amendment history) tries to show at a glance every amendment which has been considered for the motion (both passed and failed). Since this is not how the system is-->
<!--            designed to work, building it has been a nightmare (tons of-->
<!--            regular expressions [shudder] implemented in javascript [yuck]).  </p>-->
<!--    </div>-->
<!--    </div>-->
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
