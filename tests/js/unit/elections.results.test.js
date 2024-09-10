import er from '../../../resources/js/store/modules/elections/elections.results';
import CandidateResult from "../../../resources/js/models/CandidateResult";
let _ = require('lodash');

describe('elections.results mutations', () => {

    beforeEach(() => {
        er.state.electionResults = [];
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
