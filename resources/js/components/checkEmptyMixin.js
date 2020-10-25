module.exports = {

    methods: {

        emptyStringIfEmpty: function (val) {
            if (_.isUndefined(val) || _.isNull(val)) {
                return '';
            }

        }
    }
}
