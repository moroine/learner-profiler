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
 * @param {String} type
 * @param {String} datatype
 * @returns {undefined}
 */
Entity.Visualisation = function (type, datatype) {
    /**
     * @type int
     */
    this._id = null;

    /**
     * @type String
     */
    this._type = type;

    /**
     * @type String
     */
    this._datatype = datatype;

    /**
     * @type {Trace[]}
     */
    this._traces = [];

    /**
     * @type {Legend[]}
     */
    this._legends = [];

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
    this.getDatatype = function () {
        return this._datatype;
    };

    /**
     *
     * @param {String} datatype
     * @returns {undefined}
     */
    this.setDatatype = function (datatype) {
        this._datatype = datatype;
    };

    /**
     *
     * @returns {Trace[]} - Array of traces
     */
    this.getTraces = function () {
        return this._traces;
    };

    /**
     *
     * @param {Trace[]} traces - Array of traces
     * @returns {undefined}
     */
    this.setTraces = function (traces) {
        this._traces = traces;
    };

    /**
     *
     * @returns {Legend[]} - Array of legends
     */
    this.getLegends = function () {
        return this._legends;
    };

    /**
     *
     * @param {Legend[]} legends - Array of legends
     * @returns {undefined}
     */
    this.setLegends = function (legends) {
        this._legends = legends;
    };

    /**
     *
     * @returns {Trace[]} traces - Array of active traces
     */
    this.getActiveTraces = function () {
        var traces = this.getTraces();
        var activeTraces = [];

        for (var i = 0; i < traces.length; i++) {
            if (traces[i].isActive()) {
                activeTraces.push(traces[i]);
            }
        }

        return activeTraces;
    };

    /**
     *
     * @returns {?Trace}
     */
    this.getFirstChoropleth = function () {
        var activeTraces = this.getActiveTraces();
        var i;

        for (i = 0; i < activeTraces.length; i++) {
            if (activeTraces[i].getType() === "choropleth") {
                return activeTraces[i];
            }
        }

        return null;
    };

    /**
     *
     * @returns {?Trace}
     */
    this.getFirstBubble = function () {
        var activeTraces = this.getActiveTraces();
        var i;

        for (i = 0; i < activeTraces.length; i++) {
            if (activeTraces[i].getType() === "bubble") {
                return activeTraces[i];
            }
        }

        return null;
    };
};