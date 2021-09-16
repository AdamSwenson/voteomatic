<template>
<div class="card settings-area">

    <div class="card-header">
        <h3 class="card-heading">Settings</h3>
    </div>

    <setting-card v-for="name in settingNames"
                  :key="name"
                  :settings-obj="settings"
                  :name="name"
    ></setting-card>

</div>


</template>

<script>
import {isReadyToRock} from "../../utilities/readiness.utilities";
import SettingCard from "./setting-card";
export default {
    name: "settings-area",
    components: {SettingCard},
    props: [],

    mixins: [],

    data: function () {
        return {}
    },

    asyncComputed: {

        settings : function(){
            return this.$store.getters.getSettings;
        },
        settingNames : function(){
            if(isReadyToRock(this.settings)) return this.settings.settingNames;
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
