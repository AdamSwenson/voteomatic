import er from '../../../../../../resources/js/store/modules/elections/elections.navigation';
import Election from "../../../../../../resources/js/models/Election";
let _ = require('lodash');
const sinon = require('sinon');

describe('elections.navigation actions', () => {

    beforeEach(() => {
        // er.state.shownHomeCard = [];
    });

    describe('navigateToAppropriateLocationChair', () => {

        test('setup', () => {
            let f = { commit : sinon.spy(),
                dispatch : sinon.spy()
            };

            let meeting = new Election({ phase: 'setup'});

            er.actions.navigateToAppropriateLocationChair(f, meeting);

            expect(f.commit.args).to.equal(['setShownCard', 'setup']);
        });


        test('nominations', () => {
        });

        test('voting', () => {
        });


        test('closed', () => {
        });


        test('results', () => {
        });

        //
        // test('setup', () => {
        // });

    });

    test('addResults - no preexisting', () => {
        let r = new CandidateResult({candidateId: 1});

        er.mutations.addResults(er.state, r);

        expect(er.state.electionResults.length).toEqual(1);
    });

    test('addResults - w preexisting', () => {
        let r = new CandidateResult({candidateId: 2});
        er.state.electionResults.push(r);

        er.mutations.addResults(er.state, r);

        expect(er.state.electionResults.length).toEqual(1);
    });


} );
