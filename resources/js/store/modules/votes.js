import * as routes from "../../routes";
import Vote from "../../models/Vote";

const actions = {

    /**
     * DEV NOT READY FOR USE
     */
    castRegularMotionVote({dispatch, commit, getters}, motion) {

        let url = routes.votes.recordVote(motion.id);
        let data = {
            motionId: motion.id,
            vote: voteType,
        };

        return new Promise((resolve, reject) => {
            let me = this;
            return Vue.axios.post(url, data)
                .then((response) => {
                    console.log(response.data);
                    me.vote = new Vote(response.data.isYay, response.data.receipt, response.data.id);
                    me.voteRecorded = true;
                    me.showButtons = false;
                    //todo once receives notification that vote has been recorded, should set voteRecorded to true so inputs can be disabled.

                    me.$store.commit('addVotedUponMotion', me.motion.id);
                    resolve();
                })
                .catch(function (error) {
                    // error handling
                    if (error.response) {
                        // The request was made and the server responded with a status code
                        // that falls out of the range of 2xx
                        console.log(error.response.data);
                        console.log(error.response.status);
                        if (error.response.status === 501) {
                            me.voteRecorded = true;
                            me.showButtons = false;
                        }

                    }
                    // reject();
                });

        });
    },


    castElectionVote({dispatch, commit, getters}, {motionId, candidateId}) {
        window.console.log('wag', motionId, candidateId);

        let url = routes.election.recordVote(motionId);
        let data = {
            candidate_id: candidateId
        };
        return new Promise(((resolve, reject) => {

            let me = this;

            return Vue.axios.post(url, data)
                .then((response) => {

                    console.log(response.data);
                    return resolve();
                });
        }));


    }


};

export default {
    actions,
    // getters,
    // mutations,
    // state,
}

