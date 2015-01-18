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
 *
 * @constructor
 * @returns {undefined}
 */
Repository.LegendRepository = function () {
    this._legends = {};
    this._id = 0;

    /**
     *
     * @param {int} id
     * @returns {Legend}
     */
    this.get = function (id) {
        return this._legends.id;
    };

    /**
     *
     * @param {Legend} obj
     * @returns {Legend}
     */
    this.persist = function (obj) {
        obj.setId(this._id++);
        this._legends[obj.getId()] = obj;

        return obj;
    };

    /**
     *
     * @param {Legend} obj
     * @returns {undefined}
     */
    this.remove = function (obj) {
        delete this._legends[obj.getId()];
    };
};