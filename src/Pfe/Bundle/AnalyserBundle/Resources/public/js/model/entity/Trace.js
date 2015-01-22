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
    this._denominateur = null;

    /**
     * @type boolean
     */
    this._active = true;

    this._data = {};
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
Entity.Trace.prototype.getDenominateur = function () {
    return this._denominateur;
};

/**
 *
 * @param {String} denominateur
 * @returns {undefined}
 */
Entity.Trace.prototype.setDenominateur = function (denominateur) {
    this._denominateur = denominateur;
};

/**
 *
 * @returns {Boolean}
 */

Entity.Trace.prototype.isActive = function () {
    return this._active;
};

Entity.Trace.prototype.getData = function (datatype, legends, callback) {
    $.getJSON(Provider.endpoint + "/participant/count.json", function (data) {
        this._data = data;
        callback(this, this._data);
    });
};
