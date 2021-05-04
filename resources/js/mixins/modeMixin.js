module.exports = {

    asyncComputed: {

        mode: function () {
            return this.$store.getters.getMode;
        },

        // mode: {
        //     get: function () {
        //         return this.$store.getters.getMode;
        //     },
        //     // set: function (v) {
        //     //     this.$store.commit('setMode', v);
        //     // }
        // },

        isElection: function () {
            return this.$store.getters.isElection;
        },

        isInEventEditingMode: function () {
            return this.showArea === 'create' || this.showArea === 'edit';
        },

        isMeeting: function () {
            return this.$store.getters.isMeeting;
        },

        eventType: function () {
            return this.$store.getters.getEventType;
        },

        eventTypeCapitalized: function () {
            return _.capitalize(this.eventType);
        },

        subsidiaryType: function () {
            return this.$store.getters.getEventSubsidiaryType;
        },

        subsidiaryTypeCapitalized: function () {
            return _.capitalize(this.subsidiaryType);
        }

    },


    computed: {
        showArea: {
            get: function () {
                return this.$store.getters.showArea;
            }, set: function (v) {
                this.$store.commit('setShowArea', v);
            }
        }
    },

    methods: {
        setMeeting: function () {
            this.$store.dispatch('setMeetingMode').then(() => {
                // this.$store.dispatch('loadAllEvents').then(() => {
                    window.console.log('meeting mode set');
                // });
            });
        },

        setElection: function () {
            this.$store.dispatch('setElectionMode').then(() => {
                // let election = this.$store.getters.getActiveMeeting;
                    // .then(() => {
                    window.console.log('election mode set ', this.eventType);
                // });
            });
        },

        setSetup: function () {
            this.$store.commit('setIsSetup', true);
        },
        setShowArea: function (v) {
            this.$store.commit('setShowArea', v);
        },

    }

};
