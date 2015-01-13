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
 *
 * @constructor
 * @param {String} rule
 * @param {String} type
 * @param {String} field
 * @param {int} value
 * @returns {undefined}
 */
Entity.Filter = function (rule, type, field, value) {
    this._id = null;
    this._rule = rule;
    this._type = type;
    this._field = field;
    this._value = value;
    this._actif = true;

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
    this.getRule = function () {
        return this._rule;
    };

    /**
     *
     * @param {String} rule
     * @returns {undefined}
     */
    this.setRule = function (rule) {
        this._rule = rule;
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
     * @returns {String}
     */
    this.getField = function () {
        return this._field;
    };

    /**
     *
     * @param {String} field
     * @returns {undefined}
     */
    this.setField = function (field) {
        this._field = field;
    };

    /**
     *
     * @returns {int}
     */
    this.getValue = function () {
        return this._value;
    };

    /**
     *
     * @param {int} value
     * @returns {undefined}
     */
    this.setValue = function (value) {
        this._value = value;
    };
};