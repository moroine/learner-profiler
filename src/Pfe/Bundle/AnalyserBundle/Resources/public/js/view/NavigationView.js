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
    this._ui = ui;
    this._$self = $("#navigation");
    this._type = this._$self.find("#navigation-type .active a").data('navigation-type');
    this._datatype = this._$self.find("#navigation-datatype .active a").data('navigation-datatype');
};

View.NavigationView.prototype.init = function () {
    this.bindEvents();
};

View.NavigationView.prototype.bindEvents = function () {
    this._$self.on('click', '')
};
