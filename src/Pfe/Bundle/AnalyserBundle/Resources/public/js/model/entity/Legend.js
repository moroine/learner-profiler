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
    this.getName = function () {
        return this._name;
    };

    /**
     *
     * @param {String} name
     * @returns {undefined}
     */
    this.setName = function (name) {
        this._name = name;
    };

    /**
     *
     * @returns {int}
     */
    this.getMin = function () {
        return this._min;
    };

    /**
     *
     * @param {int} min
     * @returns {undefined}
     */
    this.setMin = function (min) {
        this._min = min;
    };

    /**
     *
     * @returns {int}
     */
    this.getMax = function () {
        return this._max;
    };

    /**
     *
     * @param {int} max
     * @returns {undefined}
     */
    this.setMax = function (max) {
        this._max = max;
    };

    /**
     *
     * @returns {String}
     */
    this.getColor = function () {
        return this._color;
    };

    /**
     *
     * @param {String} color
     * @returns {undefined}
     */
    this.setColor = function (color) {
        this._color = color;
    };
};