if (typeof View === "undefined") {
    /**
     * @namespace
     */
    var View = {};
}


/**
 * @class
 */
View.UiView = function () {
    /**
     * @type {View.NavigationView}
     */
    this._navigation = new View.NavigationView(this);

    /**
     * @type {View.MapView}
     */
    this._map = new View.MapView(this);

    /**
     * @type {View.LegendView}
     */
    this._legend = new View.LegendView(this);

    /**
     * @type {View.DetailsView}
     */
    this._details = new View.DetailsView(this);
};

/**
 * @returns {View.NavigationView}
 */
View.UiView.prototype.getNavigation = function () {
    return this._navigation;
};

/**
 * @returns {View.MapView}
 */
View.UiView.prototype.getMapView = function () {
    return this._map;
};
