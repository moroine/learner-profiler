if (typeof Entity === 'undefined') {
    /**
     *
     * @namespace Controller
     */
    var Controller = {};
}

Controller.ApiController = function () {

};

Controller.ApiController.prototype.getTraceData = function (datatype, legends, trace, callback) {
    var route = ProviderApi[datatype];
    var data = null;

    $.ajax({
        type: "POST",
        url: route,
        data: data,
        success: callback,
        dataType: "json"
    });

    $.getJSON(Learner, function (data) {
        this._data = data;
        callback(visualisation, this, this._data);
    });
};