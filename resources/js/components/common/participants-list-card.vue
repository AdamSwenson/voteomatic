<template>

    <div class="participants-list-card card">

        <div class="card-header">
            <h4 class="card-title">{{title}}</h4>
        </div>

        <div class="card-body">
            <p><strong>ToDo</strong></p>
        </div>

    </div>


</template>

<script>
import Payload from "../../models/Payload";
import * as routes from "../../routes";
import MeetingMixin from '../../mixins/meetingMixin';
import ChairMixin from '../../mixins/chairMixin';
import ModeMixin from "../../mixins/modeMixin";

export default {
name: "participants-list-card",

props : [],

    mixins: [MeetingMixin, ChairMixin, ModeMixin],


    data : function(){
    return {}
},

asyncComputed : {
    title: function(){
        if(this.isElection) return "Voters";
        if(this.isMeeting) return "Members";
    },

    participants : function(){
        return [];
    }
},

computed : {},

methods : {}

}
</script>

<style scoped>

</style>
