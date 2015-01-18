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
Repository.FilterRepository = function () {
    this._filters = {};
    this._id = 0;
};

/**
 *
 * @param {int} id
 * @returns {Filter}
 */
Repository.FilterRepository.prototype.get = function (id) {
    return this._filters.id;
};

/**
 *
 * @param {Filter} obj
 * @returns {Filter}
 */
Repository.FilterRepository.prototype.persist = function (obj) {
    obj.setId(this._id++);
    this._filters[obj.getId()] = obj;

    return obj;
};

/**
 *
 * @param {Filter} obj
 * @returns {undefined}
 */
Repository.FilterRepository.prototype.remove = function (obj) {
    delete this._filters[obj.getId()];
};