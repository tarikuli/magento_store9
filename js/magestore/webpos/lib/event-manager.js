var EventManager = {
    dispatch: function (eventName, data, timeout) {
        $D("body").eventName = '';
        if (timeout) {
            setTimeout(function () {
                $D("body").trigger(eventName, data);
            }, 100);
        } else $D("body").trigger(eventName, data);
        return true;
    },
    observer: function (eventName, function_callback) {
        $D("body").on(eventName, function_callback);
        return true;
    }
};