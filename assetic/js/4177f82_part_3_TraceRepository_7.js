/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (typeof Repository === 'undefined') {
    /**
     *
     * @namespace Repository
     */
    var Repository = {};
}

/**
 * @class
 * @returns {undefined}
 */
Repository.TraceRepository = function () {
    this._traces = {};
    this._id = 0;
};

/**
 *
 * @param {int} id
 * @returns {Trace}
 */
Repository.TraceRepository.prototype.get = function (id) {
    return this._traces.id;
};

/**
 *
 * @param {Trace} obj
 * @returns {Trace}
 */
Repository.TraceRepository.prototype.persist = function (obj) {
    obj.setId(this._id++);
    this._traces[obj.getId()] = obj;

    return obj;
};

/**
 *
 * @param {Trace} obj
 * @returns {undefined}
 */
Repository.TraceRepository.prototype.remove = function (obj) {
    delete this._traces[obj.getId()];
};