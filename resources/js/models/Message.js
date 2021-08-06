import IModel from "./IModel";

export default class Message extends IModel {


    constructor({id = null, messageText = null, messageStyle = null, displayTime = 0, motion = null}) {
        super();
        this.id = id;
        this.messageText = messageText;
        this.messageStyle = messageStyle;
        this.displayTime = displayTime;
        this.motion = motion;


    }


    static get templates() {
        return [
            {
                id: 1,
                name: 'pendingApproval',
                messageText: "The Chair has been asked to verify that your motion is in order.",
                messageStyle: 'primary',
                displayTime: 2000
            },

            {
                id: 2,
                name: 'notApproved',
                messageText: `The Chair has ruled that this motion is out of order.`,
                messageStyle: 'danger',
                displayTime: 20000
            },

            {
                id: 3,
                name: 'noSecond',
                messageText: "The proposed motion did not receive a second.",
                messageStyle: 'warning',
                displayTime: 0
            },

        ]
    }

    static makeFromTemplate(name, motion = null) {
        let m = _.filter(Message.templates, (t) => {
            return t.name === name;
        });
        m = m[0];
        m['motion'] = motion;
        return new Message(m);
    }

}
