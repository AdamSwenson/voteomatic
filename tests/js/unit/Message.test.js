
let _ = require('lodash');
const { Message } = require("../../../resources/js/models/Message");

test('Make from template', () => {

    let templates = Message.templates;

    _.forEach(templates, (t) => {
       let o = Message.makeFromTemplate(t.name);
       expect(o.messageText).toBe(t.messageText);
    });


});
