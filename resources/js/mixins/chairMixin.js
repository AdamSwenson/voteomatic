/**
 * For any component that needs access to the
 * properties and other things related to
 * the user (not) being the chair.
 *
 * @type {{computed: {}}}
 */
module.exports = {

    asyncComputed: {

        isChair: function () {
            return this.$store.getters.getIsAdmin;
        },
    }

};
