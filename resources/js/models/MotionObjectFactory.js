import Motion from "./Motion"
import Resolution from "./Resolution";

/**
 * For a regular meeting, determines whether the motion
 * is something simple or a resolution and returns appropriate object
 */
export default class MotionObjectFactory{

    static make(data){
        if(data.is_resolution === true){
            return new Resolution(data);
        }else{
            return new Motion(data);
        }

    }
}
