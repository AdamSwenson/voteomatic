import IModel from "./IModel";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Message extends IModel {


    constructor({id = null, messageText = null, messageStyle = null, displayTime = 0, motion = null, showToChair=null}) {
        super();
        /** Whether to display the message to the chair */
        this._showToChair = showToChair;
        //We add a bit of entropy so vue won't get confused by multiple
        //instances of same message
        this.id = 'message-' + id + '-' + _.random(3,9999);
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
                displayTime: 5000,
                showToChair: false
            },

            {
                id: 2,
                name: 'notApproved',
                messageText: `The Chair has ruled that this motion is not in order:`,
                messageStyle: 'danger',
                displayTime: 5000
            },

            {
                id: 3,
                name: 'noSecond',
                messageText: "This proposed motion did not receive a second:",
                messageStyle: 'warning',
                displayTime: 5000
            },

        ]
    }

    get showToChair(){
        //Shows to chair by default
        if(! isReadyToRock(this._showToChair)) return true;

        return this._showToChair;
    }

    set showToChair(v){
        this._showToChair = v;
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
