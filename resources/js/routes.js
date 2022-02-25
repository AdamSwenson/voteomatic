/**
 * Ensure that the base url ends with a '/'
 * as expected by all the route methods
 * @param url
 */
const normalizedRouteRoot = () => {
    let url = window.routeRoot;

    if (url[url.length] === '/') return url;

    return url + '/';
}

/**
 * This holds all information about which
 * routing urls are used for what purposes.
 *
 */
module.exports = {

    auth: {
        baseUrl: () => {
            return normalizedRouteRoot();
        },
        logout: () => {
            return normalizedRouteRoot() + 'logout'
        },
        nonLTILogin: () => {
            return normalizedRouteRoot() + 'login';
        },

        waitlist: () => {
            return normalizedRouteRoot() + 'waitlist';
        }

    },

    election: {

        admin : {
            startVoting : (meetingId) => {
                return normalizedRouteRoot() + 'election/admin/start/' + meetingId;
            },

            stopVoting : (meetingId) => {
                return normalizedRouteRoot() + 'election/admin/stop/' + meetingId;
            },

            releaseResults : (meetingId) => {
                return normalizedRouteRoot() + 'election/admin/results/release/' + meetingId;
            }
        },

        resource: {
            // candidate: (candidateId) => {
            //     let r = normalizedRouteRoot() + '/election/candidate';
            //     if (!_.isNull(candidateId)) {
            //         r = r + '/' + candidateId;
            //     }
            //     return r;
            // },


            election: (meetingId = null) => {
                let r = normalizedRouteRoot() + 'elections/';

                if (!_.isNull(meetingId)) {
                    r += meetingId;
                }
                return r;
            },

            office: (motionId = null) => {
                let r = normalizedRouteRoot() + 'offices/';

                if (!_.isNull(motionId)) {
                    r += motionId;
                }
                return r;
            },

            people: (personId = null) => {
                let r = normalizedRouteRoot() + 'election/people';
                if (!_.isNull(personId)) {
                    r = r + '/' + personId;
                }
                return r;
            }


        },

        addToPool: (motionId, personId) => {
            return normalizedRouteRoot() + 'election/pool/' + motionId + '/' + personId;
        },

        addWriteIn: (motionId) => {
            return normalizedRouteRoot() + 'election/write-in/' + motionId;
        },


        // createOffice: (meetingId) => {
        //     return normalizedRouteRoot() + 'election/office/' + meetingId;
        // },

        candidates: (motionId, candidateId = null) => {
            let r = normalizedRouteRoot() + 'election/' + motionId + '/candidates';
            if (!_.isNull(candidateId)) {
                r = r + '/' + candidateId;
            }
            return r;
        },

        electionDetails: (meetingId) => {
            return normalizedRouteRoot() + 'election/' + meetingId;
        },

        getOffices: (meetingId) => {
            return normalizedRouteRoot() + 'election/office/' + meetingId;
        },

        /**
         * Everyone who could be a candidate in the election
         * @param motionId
         */
        getPool: (motionId) => {
            return normalizedRouteRoot() + 'election/pool/' + motionId;
        },
        getResults: (motionId) => {
            return normalizedRouteRoot() + 'election/' + motionId + '/results';
        },

        nominatePoolMember: (poolMemberId) => {
            return normalizedRouteRoot() + 'election/nominate/' + poolMemberId;
        },

        recordVote: (motionId) => {
            return normalizedRouteRoot() + 'election/vote/' + motionId;
        },

        removeCandidate: (candidateId) => {
            return normalizedRouteRoot() + 'election/candidate/' + candidateId;
            //     if (!_.isNull(candidateId)) {
            //         r = r + '/' + candidateId;
            //     }
            //     return r;
            // },

        }
    },

    home: () => {
        return normalizedRouteRoot() + 'home';
    },

    results: {
        getCounts: (motionId) => {
            return normalizedRouteRoot() + 'results/' + motionId + '/counts'
        },

        getResults: (motionId) => {
            return normalizedRouteRoot() + 'results/' + motionId;
        }
    },

    votes: {
        recordVote: (motionId) => {
            return normalizedRouteRoot() + 'record-vote/' + motionId;
        }
    },

    castVotes: {

        getVotedMotions: (meetingId) => {
            return normalizedRouteRoot() + 'cast-votes/' + meetingId;

        }
    },

    receipts: {
        validateReceipt: () => {
            return normalizedRouteRoot() + 'validation';
        }
    },

    meetings: {

        main: (meetingId) => {
            return normalizedRouteRoot() + 'main/' + meetingId;
        },

        /**
         * Path for the resource controller for meetings.
         * For create requests, leave the id empty
         * @param meetingId
         * @returns {string}
         */
        resource: (meetingId = null) => {
            let base = normalizedRouteRoot() + 'meetings/';
            if (_.isNull(meetingId)) {
                return base;
            }
            return base + meetingId;

        },

        getRoster: (meetingId) => {
            let base = normalizedRouteRoot() + 'roster/';
            if (_.isNull(meetingId)) {
                return base;
            }
            return base + meetingId;

        }
    },

    motions: {
        /**
         * Path for the resource controller for motions.
         *
         * In the unlikely circumstance that we want to make a create
         * request which doesn't associate with a meeting, leave the id empty
         *
         * @param motionId
         * @returns {string}
         */
        resource: (motionId = null) => {
            let base = normalizedRouteRoot() + 'motions/';
            if (_.isNull(motionId)) {
                return base;
            }
            return base + motionId;
        },

        createMotion: (meetingId) => {
            return normalizedRouteRoot() + 'motions/meeting/' + meetingId;
        },

        endVoting: (motionId) => {
            return normalizedRouteRoot() + 'motions/close/' + motionId;
        },

        getCurrentMotion: (meetingId) => {
            return normalizedRouteRoot() + 'motions/stack/' + meetingId;
        },

        setCurrentMotion: (meetingId, motionId) => {

            return normalizedRouteRoot() + 'motions/stack/' + meetingId + '/' + motionId;
        },
        getAllMotionsForMeeting: (meetingId) => {

            return normalizedRouteRoot() + 'motions/meeting/' + meetingId;
        },

        secondMotion: (motionId) => {
            return normalizedRouteRoot() + 'motions/second/' + motionId;
        },

        inOrder: (motionId) => {
            return normalizedRouteRoot() + 'motions/order/good/' + motionId;
        },

        openVoting: (motionId) => {
            return normalizedRouteRoot() + 'motions/open/' + motionId;
        },

        outOfOrder: (motionId) => {
            return normalizedRouteRoot() + 'motions/order/bad/' + motionId;
        },

        templates: () => {
            return normalizedRouteRoot() + 'motions/templates';
        },

        types: () => {
            return normalizedRouteRoot() + 'motions/types';
        },


    }

}
