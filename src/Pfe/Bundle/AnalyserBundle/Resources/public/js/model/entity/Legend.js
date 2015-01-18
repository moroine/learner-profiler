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
 * @param {String} name
 * @param {int} min
 * @param {int} max
 * @param {String} color
 * @returns {undefined}
 */
Entity.Legend = function (name, min, max, color) {
    /**
     * @type int
     */
    this._id = null;

    /**
     * @type String
     */
    this._name = name;

    /**
     * @type int
     */
    this._min = min;

    /**
     * @type int
     */
    this._max = max;

    /**
     * @type String
     */
    this._color = color;
};

/**
 *
 * @returns {int}
 */
Entity.Legend.prototype.getId = function () {
    return this._id;
};

/**
 *
 * @param {int} id
 * @returns {undefined}
 */
Entity.Legend.prototype.setId = function (id) {
    this._id = id;
};

/**
 *
 * @returns {String}
 */
Entity.Legend.prototype.getName = function () {
    return this._name;
};

/**
 *
 * @param {String} name
 * @returns {undefined}
 */
Entity.Legend.prototype.setName = function (name) {
    this._name = name;
};

/**
 *
 * @returns {int}
 */
Entity.Legend.prototype.getMin = function () {
    return this._min;
};

/**
 *
 * @param {int} min
 * @returns {undefined}
 */
Entity.Legend.prototype.setMin = function (min) {
    this._min = min;
};

/**
 *
 * @returns {int}
 */
Entity.Legend.prototype.getMax = function () {
    return this._max;
};

/**
 *
 * @param {int} max
 * @returns {undefined}
 */
Entity.Legend.prototype.setMax = function (max) {
    this._max = max;
};

/**
 *
 * @returns {String}
 */
Entity.Legend.prototype.getColor = function () {
    return this._color;
};

/**
 *
 * @param {String} color
 * @returns {undefined}
 */
Entity.Legend.prototype.setColor = function (color) {
    this._color = color;
};