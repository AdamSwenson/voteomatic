<template>
    <div class="waitlist-form">

        <div class="success-message"
             v-if="isSuccess">
            <p>Thank you. You will be notified when the Voteomatic is ready for public use.</p>

        </div>

        <form
            v-else
            id="waitlist-form">
            <!--          action="{{ route('waitlist') }}"-->
            <!--          method="POST"-->
            <!--    >-->

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" class="form-control"
                v-model="name"
                >
            </div>


            <div class="form-group">
                <label for="email">Email address</label>
                <input type="email"
                       id="email"
                       class="form-control"
                       aria-describedby="emailHelp"
                       v-model="email"
                >
                <small id="emailHelp"
                       class="form-text text-muted"
                >We'll never share your email with anyone else.</small>
            </div>


            <div class="form-group">
                <label for="organization">Organization</label>
                <input type="text" id="organization" class="form-control"
                v-model="organization">
            </div>

            <div class="form-group">
                <label for="notes">Notes</label>
                <textarea id="notes"
                          class="form-control" v-model="notes"
                ></textarea>
            </div>

            @csrf

            <button type='submit' class="btn btn-light" v-on:click="handleClick"
            >Add me to the waitlist
            </button>
        </form>
    </div>

</template>

<script>
import * as routes from "../routes";

export default {
    name: "waitlist",

    props: [],

    mixins: [],

    data: function () {
        return {
            isSuccess: false,
            name: '',
            email: '',
            organization: '',
            notes: ''
        }
    },

    computed: {},

    methods: {
        handleClick: function () {
            let pl = {
                name: this.name,
                email: this.email,
                organization: this.organization,
                notes: this.notes
            };

            let url = routes.auth.waitlist();
            return Vue.axios.post(url)
                .then((response) => {
                    window.console.log(response.status);
                    me.isSuccess = true;
                });
        }
    }

}
</script>

<style scoped>

</style>
