const {nextTick} = require("vue");

/**
 * Manages focus on change of motion
 * Assumes have a property 'focusId'
 */
module.exports = {

    watch: {
        motion: {
            handler(motion) {
                let me = this;
                nextTick(() => {
                    let el = document.getElementById(me.focusId);
                    el.scrollIntoView()
                });

            },
            immediate: true,
        }
    },

};
