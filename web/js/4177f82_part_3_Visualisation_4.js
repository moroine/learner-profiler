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
};

/**
 *
 * @returns {int}
 */
Entity.Visualisation.prototype.getId = function () {
    return this._id;
};

/**
 *
 * @param {int} id
 * @returns {undefined}
 */
Entity.Visualisation.prototype.setId = function (id) {
    this._id = id;
};

/**
 *
 * @returns {String}
 */
Entity.Visualisation.prototype.getType = function () {
    return this._type;
};

/**
 *
 * @param {String} type
 * @returns {undefined}
 */
Entity.Visualisation.prototype.setType = function (type) {
    this._type = type;
};

/**
 *
 * @returns {String}
 */
Entity.Visualisation.prototype.getDatatype = function () {
    return this._datatype;
};

/**
 *
 * @param {String} datatype
 * @returns {undefined}
 */
Entity.Visualisation.prototype.setDatatype = function (datatype) {
    this._datatype = datatype;
};

/**
 *
 * @returns {Trace[]} - Array of traces
 */
Entity.Visualisation.prototype.getTraces = function () {
    return this._traces;
};

/**
 *
 * @param {Trace[]} traces - Array of traces
 * @returns {undefined}
 */
Entity.Visualisation.prototype.setTraces = function (traces) {
    this._traces = traces;
};

/**
 *
 * @returns {Legend[]} - Array of legends
 */
Entity.Visualisation.prototype.getLegends = function () {
    return this._legends;
};

/**
 *
 * @param {Legend[]} legends - Array of legends
 * @returns {undefined}
 */
Entity.Visualisation.prototype.setLegends = function (legends) {
    this._legends = legends;
};

/**
 *
 * @returns {Trace[]} traces - Array of active traces
 */
Entity.Visualisation.prototype.getActiveTraces = function () {
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
Entity.Visualisation.prototype.getActiveChoropleth = function () {
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
Entity.Visualisation.prototype.getActiveBubble = function () {
    var activeTraces = this.getActiveTraces();
    var i;

    for (i = 0; i < activeTraces.length; i++) {
        if (activeTraces[i].getType() === "bubble") {
            return activeTraces[i];
        }
    }

    return null;
};