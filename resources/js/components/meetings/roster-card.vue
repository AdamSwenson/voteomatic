<template>

    <div class="roster-area card">
        <div class="card-header">
            <h4 class="card-title">Meeting roster  <span class="text-danger">(Chair only)</span></h4>
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

export default {
name: "roster-card",

props : [],

mixins : [MeetingMixin],

data : function(){
    return {}
},

asyncComputed : {},

computed : {},

methods : {}

}
</script>

<style scoped>

</style>
