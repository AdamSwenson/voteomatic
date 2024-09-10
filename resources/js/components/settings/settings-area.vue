<template>
    <div class="card settings-area">

        <div class="card-header">
            <h3 class="card-heading">Settings (In development: settings don't yet do anything)</h3>
        </div>

        <setting-card v-for="name in settingNames"
                      :key="name"
                      :settings-obj="settingsObject"
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

        objectKeys : function(){
          if(isReadyToRock(this.settingsObject)){
              return _.keys(this.settingsObject.settings);
          }
          return [];
        },

        settingsObject: function () {
            return this.$store.getters.getSettings;
        },

        settingNames: function () {
            if (!isReadyToRock(this.settingsObject)) return [];

            /* dev The following is what we have to do to
                list the settings in order of their display name.
                This is very stupid because I am sending all the info about display names separately
                 from the actual settings .
            */
            let me = this;
            //make an array
            let o = [];
            _.forEach(_.keys(this.settingsObject.display), (k) => {
                if(_.includes(me.objectKeys, k)){
                    //Need to make sure only have the ones for the type of event
                    o.push({settingName: k, displayName: me.settingsObject.display[k].displayName});
                }
            });

            //Sort the hybrid list of setting name and display names by display name
            o = _.orderBy(o, ['displayName']);
            //Now we have the sorted names (keys), so we pluck those and return them
            return _.map(o, 'settingName');
        }
    },

    computed: {},

    methods: {}

}
</script>

<style scoped>

</style>
