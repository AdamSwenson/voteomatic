import IModel from "./IModel";
import {isReadyToRock} from "../utilities/readiness.utilities";

export default class Message extends IModel {


    constructor({id = null, messageText = null, messageStyle = null, displayTime = 0, motion = null, showToChair=null, chairOnly=null, blockingMessage=null}) {
        super();
        /** Whether to display the message to the chair (to avoid annoying them)*/
        this._showToChair = showToChair;
        /** Whether to only show the message to the chair */
        this._chairOnly = chairOnly;

        /** Whether the message should demand dismissal */
        this.blockingMessage = blockingMessage;

        //We add a bit of entropy so vue won't get confused by multiple
        //instances of same message
        this.id = 'message-' + id + '-' + _.random(3,9999);
        this.messageText = messageText;
        this.messageStyle = messageStyle;
        this.displayTime = displayTime;
        this.motion = motion;

    }


    /**
     * Returns message templates stored on the client side
     * @returns {[{messageText: string, displayTime: number, messageStyle: string, name: string, id: number, showToChair: boolean}, {messageText: string, displayTime: number, messageStyle: string, name: string, id: number}, {messageText: string, displayTime: number, messageStyle: string, name: string, id: number}, {messageText: string, displayTime: number, messageStyle: string, name: string, id: number}, {messageText: string, displayTime: number, messageStyle: string, chairOnly: boolean, name: string, id: number}]}
     */
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


            {
                id: 4,
                name: 'voteRecordingError',
                //This may be replaced with server generated message
                messageText: "Your vote could not be recorded: ",
                messageStyle: 'danger',
                displayTime: 7000
            },
            {
                //dev Probably won't use this since the spinner (VOT-85) works better and doesn't hang around after the motion has loaded
                id: 5,
                name: 'settingUpMotion',
                messageText: "Preparing the motion  ",
                messageStyle: 'primary',
                displayTime: 5000,
                chairOnly: true
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

    get chairOnly(){
        if(! isReadyToRock(this._chairOnly)) return false;

        return this._chairOnly;
    }

    set showToChair(v){
        this._chairOnly = v;
    }

    static makeFromTemplate(name, motion = null) {
        let m = _.filter(Message.templates, (t) => {
            return t.name === name;
        });
        m = m[0];
        m['motion'] = motion;
        return new Message(m);
    }

    static makeFromServerResponse(serverResponse){
        return new Message(serverResponse);
    }

}
