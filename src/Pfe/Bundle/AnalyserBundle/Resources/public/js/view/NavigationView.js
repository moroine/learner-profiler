if (typeof View === "undefined") {
    /**
     * @namespace
     */
    var View = {};
}

/**
 * @class
 * @param {View.UiView} ui
 */
View.NavigationView = function (ui) {
    /**
     * @type {View.UiView}
     */
    this._ui = ui;
    this._$self = $("#navigation");
    this._type = this._$self.find("#navigation-type .active a").data('navigation-type');
    this._datatype = this._$self.find("#navigation-datatype .active a").data('navigation-datatype');
};

View.NavigationView.prototype.init = function () {
    var scope = this;

    this._$self.on('click', 'li a', function (e) {
        scope.onNavigationTypeEvent(e.target);
        e.preventDefault();
    });

    this._ui._traceModal._$self.on('hide.bs.modal', function (e) {
        scope.onTraceModalHide();
    });
};

/**
 *
 * @param {HTMLElement} target
 */
View.NavigationView.prototype.onNavigationTypeEvent = function (target) {
    var $target = $(target);
    var nav = $target.data('navigation');
    var value = $target.data('navigation-' + nav);
    $target.parent().parent().find('.active').removeClass('active');
    $target.parent().addClass('active');
    switch (nav) {
        case 'type':
            this.setType(value);
            break;
        case 'datatype':
            this.setDataType(value);
            break;
    }
};

/**
 * @param {string} type
 */
View.NavigationView.prototype.setType = function (type) {
    this._type = type;
    switch (type) {
        case "graph":
            this._ui.getMapView().setVisualisation(visuGraph);
            break;
        case "map":
            this._ui.getMapView().setVisualisation(visuMap);
            break;
    }
};

/**
 * @param {string} datatype
 */
View.NavigationView.prototype.setDataType = function (datatype) {
    this._datatype = datatype;
};

View.NavigationView.prototype.onTraceModalHide = function () {
    var trace = this._ui._traceModal._current;

    if (!trace) {
        return;
    }

    console.log(trace);

    Provider.getData(this._datatype, trace, [], function () {
    });


};