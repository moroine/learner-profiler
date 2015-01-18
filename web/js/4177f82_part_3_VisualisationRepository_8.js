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
Repository.VisualisationRepository = function () {
    this._visualisations = {};
    this._id = 0;
};

/**
 *
 * @param {int} id
 * @returns {Visualisation}
 */
Repository.VisualisationRepository.prototype.get = function (id) {
    return this._visualisations.id;
};

/**
 *
 * @param {Visualisation} obj
 * @returns {Visualisation}
 */
Repository.VisualisationRepository.prototype.persist = function (obj) {
    obj.setId(this._id++);
    this._visualisations[obj.getId()] = obj;

    return obj;
};

/**
 *
 * @param {Visualisation} obj
 * @returns {undefined}
 */
Repository.VisualisationRepository.prototype.remove = function (obj) {
    delete this._visualisations[obj.getId()];
};