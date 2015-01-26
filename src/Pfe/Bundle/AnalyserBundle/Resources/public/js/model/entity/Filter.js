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
 * @class
 * @param {String} rule
 * @param {String} type
 * @param {String} field
 * @param {int} value
 * @returns {undefined}
 */
Entity.Filter = function (name, rule, type, field, value) {
    /**
     * @type int
     */
    this._id = null;

    /**
     * @type String
     */
    this._name = name || null;

    /**
     * @type String
     */
    this._rule = rule || null;

    /**
     * @type String
     */
    this._type = type || null;

    /**
     * @type String
     */
    this._field = field || null;

    /**
     * @type String
     */
    this._value = value || null;

    /**
     * @type boolean
     */
    this._actif = true;
};

/**
 *
 * @returns {int}
 */
Entity.Filter.prototype.getId = function () {
    return this._id;
};

/**
 *
 * @param {int} id
 * @returns {undefined}
 */
Entity.Filter.prototype.setId = function (id) {
    this._id = id;
};

/**
 *
 * @returns {String}
 */
Entity.Filter.prototype.getName = function () {
    return this._name;
};

/**
 *
 * @param {String} name
 * @returns {undefined}
 */
Entity.Filter.prototype.setName = function (name) {
    this._name = name;
};

/**
 *
 * @returns {String}
 */
Entity.Filter.prototype.getRule = function () {
    return this._rule;
};

/**
 *
 * @param {String} rule
 * @returns {undefined}
 */
Entity.Filter.prototype.setRule = function (rule) {
    this._rule = rule;
};

/**
 *
 * @returns {String}
 */
Entity.Filter.prototype.getType = function () {
    return this._type;
};

/**
 *
 * @param {String} type
 * @returns {undefined}
 */
Entity.Filter.prototype.setType = function (type) {
    this._type = type;
};

/**
 *
 * @returns {String}
 */
Entity.Filter.prototype.getField = function () {
    return this._field;
};

/**
 *
 * @param {String} field
 * @returns {undefined}
 */
Entity.Filter.prototype.setField = function (field) {
    this._field = field;
};

/**
 *
 * @returns {int}
 */
Entity.Filter.prototype.getValue = function () {
    return this._value;
};

/**
 *
 * @param {int} value
 * @returns {undefined}
 */
Entity.Filter.prototype.setValue = function (value) {
    this._value = value;
};