<template>
    <div class="motion-info-card  card router-tab-touching-card">

        <div class="card-header motion-info-buttons">
            <h5>Click the buttons below for information on common motions</h5>
            <p>

                <motion-info-button v-for="t in templates"
                                    :key="t.name"
                                    :template="t"
                                    :selected-template="template"
                                    v-on:template-select="setTemplate"
                ></motion-info-button>

            </p>
        </div>

        <div v-if="showInfo"
            v-bind:id="collapseId"
        >
            <div class="card card-body">
                <h4 class="card-header">{{ name }}</h4>

                <div class="card-text p-5">
                    {{ content }}
                </div>

                <div class="card-text">
                    <span class="ms-1">
                        <motion-type-badge v-if="showBadges" :motion="dummyMotion"></motion-type-badge>
                    </span>

                    <span class="ms-2">
                        <required-vote-badge v-if="showBadges" :motion="dummyMotion"></required-vote-badge>
                    </span>

                    <span class="ms-2">
                        <debatable-badge v-if="showBadges" :motion="dummyMotion"></debatable-badge>
                    </span>

                </div>

            </div>
        </div>
    </div>
</template>

<script>

import DebatableBadge from "../badges/debatable-badge.vue";
import MotionTypeBadge from "../badges/motion-type-badge.vue";
import RequiredVoteBadge from "../badges/required-vote-badge.vue";
import MotionInfoButton from "./motion-info-button.vue";
import {isReadyToRock} from "../../../utilities/readiness.utilities";
import MotionObjectFactory from "../../../models/MotionObjectFactory";

export default {
    name: "motion-info-card",

    components: {MotionInfoButton, RequiredVoteBadge, MotionTypeBadge, DebatableBadge},

    props: [],

    mixins: [],

    data: function () {
        return {
            template: null,
            collapseId: "motionInfoCollapse"
        }
    },


    computed: {

        dummyMotion: function () {
            if (isReadyToRock(this.template)) {
                return MotionObjectFactory.make(this.template);
            }
        },
        showInfo: function () {
            return isReadyToRock(this.template);
        },
        templates: function () {
            let d = this.$store.getters.getStandardMotionDefinitions;
            return _.sortBy(d, ['name']);
        },
        name: function () {
            if (isReadyToRock(this.template)) return this.template.name;
            return "";
        },
        content: function () {
            if (isReadyToRock(this.template)) return this.template.description;
            return "";
        },
        showBadges: function () {
            return isReadyToRock(this.template);
        }
    },

    methods: {
        setTemplate: function (template) {
            this.template = template;
        },
        closeInfo: function () {
            this.template = null;
        }
    }
}

</script>

<style scoped>
.v-enter-active,
.v-leave-active {
    transition: opacity 0.5s ease;
}

.v-enter-from,
.v-leave-to {
    opacity: 0;
}
</style>
