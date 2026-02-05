import er from '../../../../../../resources/js/store/modules/elections/elections.navigation';
import Election from "../../../../../../resources/js/models/Election";
import CandidateResult from "../../../../../../resources/js/models/CandidateResult";

// const sinon = require('sinon');

import {createStore} from 'vuex';
import router from "../../../../../../resources/js/router";


/**
 * Updated for new view as part of VOT-288
 */
describe('elections.navigation ', () => {
    // let store;
    let meeting;
    // let storeSpy;
    let routerSpy = sinon.spy(router, 'push');
    //let storeSpy = sinon.spy(store);


    let store = createStore(er);
    let storeSpy = sinon.spy(store, 'commit');

// let routerSpy;
    describe('actions', () => {

        beforeEach(() => {
            //
            // store = createStore(er);
            // storeSpy = sinon.spy(store, 'commit');
            meeting = new Election(3);
        });

        describe('forceNavigationToElectionHome', () => {

            test('not on election-home', () => {

                store.dispatch('forceNavigationToElectionHome').then(() => {
                    expect(routerSpy.called).toBe(true);
                    expect(routerSpy.calledWith('election-home')).toBe(true);
                });
            });

            test('On election-home', () => {
                router.currentRoute.name = 'election-home';
                store.dispatch('forceNavigationToElectionHome').then(() => {
                    expect(routerSpy.called).toBe(true);
                    expect(routerSpy.calledWith('election-home')).toBe(true);

                    //dev An actual test seems to require mounting the app and dealing with
                    // the async way vue-router handles navigation

//                    expect(router.currentRoute.name).toEqual('election-home');
                });
            });
        });

        describe('forceNavigationToElectionResults', () => {
            test('not on election-results', () => {
                router.currentRoute.name = 'election-home';
                store.dispatch('forceNavigationToElectionResults').then(() => {
                    expect(routerSpy.called).toBe(true);
                    expect(routerSpy.calledWith('election-home')).toBe(true);

                    //dev An actual test seems to require mounting the app and dealing with
                    // the async way vue-router handles navigation
                    // expect(router.currentRoute.name).toEqual('election-results');
                });
            });

            test('On election-results', () => {
                router.currentRoute.name = 'election-results';
                store.dispatch('forceNavigationToElectionResults').then(() => {
                    expect(routerSpy.called).toBe(true);
                    expect(routerSpy.calledWith('election-results')).toBe(true);

                    // expect(router.currentRoute.name).toEqual('election-results');
                });
            });
        });


        describe('navigateToAppropriateLocationChair', () => {
            beforeEach(() => {
                store.getters.getIsAdmin = () => {
                    return true
                };
            });

            test('setup', () => {
                //prep
                meeting.phase = 'setup';
                expect(meeting.phase).toEqual('setup');

                //call
                store.dispatch('navigateToAppropriateLocationChair', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('setup');
                });

            });

            test('nominations', () => {
                //prep
                meeting.phase = 'nominations';

                //call
                store.dispatch('navigateToAppropriateLocationChair', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('setup');
                });

            });

            test('voting', () => {
                //prep
                meeting.phase = 'voting';

                //call
                store.dispatch('navigateToAppropriateLocationChair', meeting).then(() => {

                    //check
                    expect(store.state.shownHomeCard).toEqual('instructions');

                });

            });


            test('closed', () => {
                //prep
                meeting.phase = 'closed';

                //call
                store.dispatch('navigateToAppropriateLocationChair', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('admin');
                });
            });


            test('results', () => {
                //prep
                meeting.phase = 'results';

                //call
                store.dispatch('navigateToAppropriateLocationChair', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('results');
                });

            });


        });

        describe('navigateToAppropriateLocationRegularUser', () => {
            beforeEach(() => {
                store.getters.getIsAdmin = () => {
                    return true
                };
            });

            test('setup', () => {
                //prep
                meeting.phase = 'setup';

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('premature');
                });

            });

            test('nominations', () => {
                //prep
                meeting.phase = 'nominations';

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('premature');
                });

            });

            test('voting - user has not voted', () => {
                //prep
                meeting.phase = 'voting';

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('instructions');
                });

            });

            test('voting - user has voted', () => {
                //prep
                meeting.phase = 'voting';
                store.getters.isVotingComplete = sinon.fake.returns(true);

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {

                    //check
                    expect(store.state.shownHomeCard).toEqual('complete');

                });

            });


            test('closed', () => {
                //prep
                meeting.phase = 'closed';

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('closed');
                });
            });

            test('results', () => {
                //prep
                meeting.phase = 'results';

                //call
                store.dispatch('navigateToAppropriateLocationRegularUser', meeting).then(() => {
                    //check
                    expect(store.state.shownHomeCard).toEqual('results');
                });

            });


        });

        describe('nextOffice', () => {
        });

        describe('nextOfficeInStack', () => {
        });

        describe('previousOffice', () => {
        });

        describe('setOfficeForVoting', () => {

        });

    });

    describe('getters', () => {
        test('isInstructionsCardVisible', () => {
            store.state.shownHomeCard = 'instructions';
            expect(store.getters.isInstructionsCardVisible).toEqual(true);
        });

    });


});
