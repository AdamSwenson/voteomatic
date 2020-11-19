<template>

        <!-- Modal -->
        <div class="modal fade"
             id="confirmMotionModal"
             tabindex="-1"
             aria-labelledby="confirmMotionModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="confirmMotionModalLabel">Please confirm that this is the motion you wish to make. You will not be able to edit it after you click 'Make motion'</h5>
                        <button type="button"
                                class="close"
                                data-dismiss="modal"
                                aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <p>{{motion.content}}</p>
                        <p><strong>Requires:</strong> {{motion.requires}}</p>
                        <p>Type: {{motion.type}}</p>
                        <p>Description: {{motion.description}}</p>
                    </div>

                    <div class="modal-footer">
                        <button type="button"
                                class="btn btn-secondary"
                                data-dismiss="modal"
                        >Continue editing</button>

                        <button type="button"
                                class="btn btn-primary"
                                data-dismiss="modal"
                                v-on:click="handleClick"
                        >Make motion</button>

                    </div>
                </div>
            </div>
        </div>

    </template>


<script>
export default {
name: "create-motion-modal",

props : ['motion'],

mixins : [],

data : function(){
    return {}
},

computed : {},
methods: {
    handleClick : function(){
        this.$emit('confirmed');
    }
}
}
</script>

<style scoped>

</style>
