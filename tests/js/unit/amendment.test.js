
let _ = require('lodash');
const {findMaxSize, checkChanges, findChangeStart, getTaggedChanges} =require('../../../resources/js/utilities/amendment.utilities');


test('getTaggedChanges --- insert in middle', () => {
    let orig = "Dog eats tacos";
    let amend = "Dog eats delicious tacos";
    //i.e., we would put the tag immediately before each of these indexes
    expect(getTaggedChanges(orig, amend)).toStrictEqual({startIndex: 9, stopIndex: 17});
})



test('check changes --- no change', () => {
let orig = "Dog eats tacos";
let amend = orig;

expect(checkChanges(orig, amend)).toBeFalsy();
})


test('check changes --- insert in middle', () => {
    let orig = "Dog eats tacos";
    let amend = "Dog eats delicious tacos";
    //i.e., we would put the tag immediately before each of these indexes
    expect(checkChanges(orig, amend)).toStrictEqual({startIndex: 9, stopIndex: 17});
})


test('check changes --- non-contiguous ', () => {
    let orig = "Dog eats tacos";
    let amend = "Dog eats delicious tacos everyday";

    // let x = 0;
    // let changes = [];
    // while (x <=amend.length){

        let changeSet = checkChanges(orig, amend);
    //     if(changeSet){
    //         changes.push(changeSet);
    //         x = changeSet.stopIndex;
    //     }else{
    //         //if there were no changes
    //         x = amend.length;
    //     }
    // }

    //Amendments must be contiguous. Thus attempted non-contiguous amendments should
    //just extend to the end of the insertion.
    expect(checkChanges(orig, amend)).toStrictEqual({startIndex: 9, stopIndex: 33});

    // expect(changes.length).toBe(2);


    //i.e., we would put the tag immediately before each of these indexes
    // expect(checkChanges(orig, amend)).toStrictEqual({startIndex: 9, stopIndex: 17});
});


test('finds change start', () => {
    let orig = [0, 1, 2, 3, 4]; //orig
    let amend = [0, 1, 5, 6, 2, 3, 4]; //new

    expect(findChangeStart(0, orig, amend)).toStrictEqual(2);
});


//-----------------

test('findChangeEnd finds change stop -- single word insert', ()=>{
    let orig = [0, 1, 2, 3, 4]; //orig
    let amend = [0, 1, 5, 2, 3, 4]; //new

    expect(findChangeEnd(2, orig, amend)).toBe(2);

});

test('findChangeEnd finds change stop -- multiple word insert', ()=>{
    let orig = [0, 1, 2, 3, 4]; //orig
    let amend = [0, 1, 5, 6, 2, 3, 4]; //new

    expect(findChangeEnd(2, orig, amend)).toBe(3);
});


test('findChangeEnd finds change stop -- strike and insert', ()=>{
    let orig = [0, 1, 2, 3, 4]; //orig
    let amend = [0, 1, 5, 6, 3, 4]; //new

    expect(findChangeEnd(2, orig, amend)).toBe(3);
});


test('arrays?', () => {
    expect(_.isEqual([0, 1],[0,1])).toBeTruthy();
});



    // //push in start tag
    // // let c = {
    // //     startIndex : i,
    // //     stopIndex : null
    // // }
    // function findChangeEnd(searchStart) {
    //     let idx = searchStart;
    //     let otl = me.splitOrigText.length;
    //     let t = me.splitOrigText;
    //     for (idx; idx <= otl; idx++) {
    //         t.splice(idx, 0, me.splitNewText[idx]);
    //         if (t === me.splitNewText) {
    //             return idx;
    //             //if mere addition, returns the start index
    //         }
    //     }
    //
    //     //
    //     // let chunkSize = otl - idx;
    //     // let origChunk = _.takeRight(me.splitOrigText, chunkSize);
    //     // let newChunk = _.takeRight(me.splitNewText, chunkSize);
    //
    // }

    //
    //     let chunkSize = otl - searchStart;
    //     let origChunk = _.takeRight(me.splitOrigText, chunkSize);
    //     //now move it down the new amend to see if matches
    //     for(let i=searchStart +1; i<=me.splitNewText.length; i++){
    //         let newChunk = _.slice(me.splitNewText, i, chunkSize + 1);
    //     }
    // }
    //
    // //start index = 2
    // //stop index = 4
    // let tc1 = [0, 1, 2, 3, 4]; //orig
    // let tc2 = [0, 1, 5, 6, 2, 3, 4]; //new


    // for (let i = 0; i < maxIdx; i++) {
    //     if (this.splitNewText[i] !== this.splitOrigText[i]) {
    //we've found something changed
    //             //push in start tag
    // let c = {
    //     startIndex : i,
    //     stopIndex : null
    // }
    // }
    //     //raise flag
    // }
    //
    //     _.takeRightWhile(users, function(o) { return !o.active; });
    //

// }
