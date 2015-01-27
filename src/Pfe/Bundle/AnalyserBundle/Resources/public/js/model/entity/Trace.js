/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (typeof Entity === 'undefined') {
    /**
     *
     * @namespace Entity
     */
    var Entity = {};
}

/**
 * @class
 * @param {String} operation
 * @param {String} type
 * @returns {undefined}
 */
Entity.Trace = function (operation, type) {
    /**
     * @type int
     */
    this._id = null;


    /**
     * @type String
     */
    this._name = name;


    /**
     * @type String
     */
    this._operation = operation;

    /**
     * @type String
     */
    this._type = type;

    /**
     * @type {Filter[]}
     */
    this._filters = [];

    /**
     * @type String
     */
    this._group = null;

    /**
     * @type boolean
     */
    this._active = true;

    this._data = {};
};

Entity.Trace.prototype.toJSON = function () {
    return {
        operation: this._operation,
        type: this._type,
        filters: this._filters,
        group: this._group
    };
};

/**
 *
 * @returns {int}
 */
Entity.Trace.prototype.getId = function () {
    return this._id;
};

/**
 *
 * @param {int} id
 * @returns {undefined}
 */
Entity.Trace.prototype.setId = function (id) {
    this._id = id;
};

/**
 *
 * @returns {String}
 */
Entity.Trace.prototype.getName = function () {
    return this._name;
};

/**
 *
 * @param {String} name
 * @returns {undefined}
 */
Entity.Trace.prototype.setName = function (name) {
    this._name = name;
};

/**
 *
 * @returns {String}
 */
Entity.Trace.prototype.getOperation = function () {
    return this._operation;
};

/**
 *
 * @param {String} operation
 * @returns {undefined}
 */
Entity.Trace.prototype.setOperation = function (operation) {
    this._operation = operation;
};

/**
 *
 * @returns {String}
 */
Entity.Trace.prototype.getType = function () {
    return this._type;
};

/**
 *
 * @param {String} type
 * @returns {undefined}
 */
Entity.Trace.prototype.setType = function (type) {
    this._type = type;
};

/**
 *
 * @returns {Filter[]} - Array of filters
 */
Entity.Trace.prototype.getFilters = function () {
    return this._filters;
};

/**
 *
 * @param {Filter[]} filters - Array of filters
 * @returns {undefined}
 */
Entity.Trace.prototype.setFilters = function (filters) {
    this._filters = filters;
};

/**
 *
 * @returns {String}
 */
Entity.Trace.prototype.getGroup = function () {
    return this._group;
};

/**
 *
 * @param {String} group
 * @returns {undefined}
 */
Entity.Trace.prototype.setGroup = function (group) {
    this._group = group;
};

/**
 *
 * @returns {Boolean}
 */
Entity.Trace.prototype.isActive = function () {
    return this._active;
};

Entity.Trace.prototype.setActive = function (active) {
    this._active = !!active;
};

Entity.Trace.prototype.getData = function (datatype, callback) {
    /*var trace = this;
     if (this.getType() === "choropleth") {
     $.getJSON(Learner, function (data) {
     trace._data = data;
     callback(visualisation, trace, trace._data);
     });
     } else if (this.getType() === "histogram") {
     $.getJSON(Learner, function (data) {
     trace._data = data;
     callback(visualisation, trace, trace._data);
     });
     } else if (this.getType() === "bubble") {
     $.getJSON("/bundles/pfeanalyser/js/test/bubbles.json", function (data) {
     trace._data = data;
     callback(visualisation, trace, trace._data);
     });
     }*/
};
