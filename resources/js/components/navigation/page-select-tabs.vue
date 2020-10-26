<template>

    <template>
        <div class="page-select-tabs">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#">Create motion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Vote</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Results</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#">Meeting</a>
                </li>

            </ul>
        </div>
    </template>

    <script>

        import * as routes from "../../routes";
        import Meeting from '../../models/Meeting';

        export default {
            name: "page-select-tabs",
            props: [],
            data: function () {
                return {}
                         },


            computed: {

            },

            // asyncComputed : {
            //   meeting : function(){
            //
            //   }
            // },
            methods: {}
        }

    </script>

    <style scoped>

    </style>

</template>

<script>
export default {
name: "page-select-tabs"
}
</script>

<style scoped>

</style>
