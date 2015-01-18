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
View.DetailsView = function (ui) {
    this._ui = ui;
};