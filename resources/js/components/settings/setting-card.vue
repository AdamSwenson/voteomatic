<template>
    <div class="card setting-card">

        <div class="card-body">
            <div class="form-check form-switch">
                <input class="form-check-input"
                       type="checkbox"
                       role="switch"
                       v-bind:id="controlId"
                       v-model="settingValue"
                >
                <label class="form-check-label"
                       v-bind:for="controlId"
                >{{ displayName }}</label>
            </div>


<!--            <div class="custom-control custom-switch">-->
<!--                <input type="checkbox"-->
<!--                       class="custom-control-input"-->
<!--                       v-bind:id="controlId"-->
<!--                       v-model="settingValue"-->
<!--                >-->
<!--                <label class="custom-control-label"-->
<!--                       v-bind:for="controlId"-->
<!--                >{{ displayName }}</label>-->

<!--            </div>-->
            <div class="setting-description">
                <small class="text-muted">{{ displayDescription }}</small>
            </div>


        </div>

    </div>
</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";
import Payload from "../../models/Payload";

export default {
    name: "setting-card",

    props: ['settingsObj', 'name'],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {
        controlId: function () {
            return 'setting-area-' + this.name;
        },
        displayProps: function () {
            if (isReadyToRock(this.settingsObj)) {
                return this.settingsObj.getDisplayForSetting(this.name);
            }
        },

        displayName: function () {
            if (isReadyToRock(this.displayProps)) return this.displayProps['displayName'];
            return '';
        },

        displayDescription: function () {
            if (isReadyToRock(this.displayProps)) return this.displayProps['displayDescription'];
        },


    },

    computed: {
        settingValue: {
            get: function () {
                if (!isReadyToRock(this.settingsObj[this.name])) {
                    return false;
                }
                return this.settingsObj[this.name];

            },

            set: function (v) {
                let p = Payload.factory({
                    'updateProp': this.name,
                    'updateVal': v
                });
                window.console.log('set setting', p);
                this.$store.dispatch('updateSettings', p);
            }

        }
    },

    methods: {}

}
</script>

<style scoped>

</style>
