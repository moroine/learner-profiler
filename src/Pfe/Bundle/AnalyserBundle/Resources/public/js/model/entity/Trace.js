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
 * @constructor
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

    /**
     *
     * @returns {int}
     */
    this.getId = function () {
        return this._id;
    };

    /**
     *
     * @param {int} id
     * @returns {undefined}
     */
    this.setId = function (id) {
        this._id = id;
    };

    /**
     *
     * @returns {String}
     */
    this.getOperation = function () {
        return this._operation;
    };

    /**
     *
     * @param {String} operation
     * @returns {undefined}
     */
    this.setOperation = function (operation) {
        this._operation = operation;
    };

    /**
     *
     * @returns {String}
     */
    this.getType = function () {
        return this._type;
    };

    /**
     *
     * @param {String} type
     * @returns {undefined}
     */
    this.setType = function (type) {
        this._type = type;
    };

    /**
     *
     * @returns {Filter[]} - Array of filters
     */
    this.getFilters = function () {
        return this._filters;
    };

    /**
     *
     * @param {Filter[]} filters - Array of filters
     * @returns {undefined}
     */
    this.setFilters = function (filters) {
        this._filters = filters;
    };

    /**
     *
     * @returns {String}
     */
    this.getDenominateur = function () {
        return this._denominateur;
    };

    /**
     *
     * @param {String} denominateur
     * @returns {undefined}
     */
    this.setDenominateur = function (denominateur) {
        this._denominateur = denominateur;
    };

    /**
     *
     * @returns {Boolean}
     */

    this.isActive = function () {
        return this._active;
    };
};