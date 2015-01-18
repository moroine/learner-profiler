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
    this.bindEvents();
};

View.NavigationView.prototype.bindEvents = function () {
    var scope = this;

    this._$self.on('click', 'li a', function (e) {
        scope.onNavigationTypeEvent(e.target);
        e.preventDefault();
    });
};

/**
 *
 * @param {HTMLElement} target
 */
View.NavigationView.prototype.onNavigationTypeEvent = function (target) {
    console.warn("Not available");
    this._ui.alertWarning("Warning!", "Not implemented yet.");
    return;

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
};

/**
 * @param {string} datatype
 */
View.NavigationView.prototype.setDataType = function (datatype) {
    this._datatype = datatype;
};
