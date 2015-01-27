var Provider = {
    endpoint: routing.provider,
    getData: function (datatype, trace, legends, callback) {
        var content = {
            datatype: datatype,
            trace: trace,
            legends: legends
        };
        $.ajax({
            type: "POST",
            url: this.endpoint,
            data: JSON.stringify(content),
            success: callback,
            contentType: 'application/json;charset=UTF-8',
            dataType: 'json'
        });
    }
};