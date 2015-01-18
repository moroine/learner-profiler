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

    this._$self = $("#main-container");
};

View.UiView.prototype.init = function () {
    this._navigation.init();
};

/**
 * @param {string} type
 * @param {string} title
 * @param {string} message
 */
View.UiView.prototype.alert = function (type, title, message) {
    var html = '<div class="alert alert-' + type + ' alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button><strong>' + title + '</strong> ' + message + '</div>';
    this._$self.find('#alert-message').append(html);
};

/**
 *
 * @param {string} title
 * @param {string} message
 */
View.UiView.prototype.alertSuccess = function (title, message) {
    this.alert('success', title, message);
};

/**
 *
 * @param {string} title
 * @param {string} message
 */
View.UiView.prototype.alertWarning = function (title, message) {
    this.alert('warning', title, message);
};

/**
 *
 * @param {string} title
 * @param {string} message
 */
View.UiView.prototype.alertInfo = function (title, message) {
    this.alert('info', title, message);
};

/**
 *
 * @param {string} title
 * @param {string} message
 */
View.UiView.prototype.alertDanger = function (title, message) {
    this.alert('danger', title, message);
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

/**
 * @returns {View.LegendView}
 */
View.UiView.prototype.getLegendView = function () {
    return this._legend;
};

/**
 * @returns {View.DetailsView}
 */
View.UiView.prototype.getDetailsView = function () {
    return this._details;
};
