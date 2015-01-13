/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(typeof Entity === 'undefined'){ 
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
Entity.Visualisation = function(type, datatype){
    this._id = null;
    this._type = type;
    this._datatype = datatype;
    this._traces = [];
    
    /**
     * 
     * @returns {int}
     */
    this.getId = function(){
        return this._id;
    };
    
    /**
     * 
     * @param {int} id
     * @returns {undefined}
     */
    this.setId = function(id){
        this._id = id;
    };
    
    /**
     * 
     * @returns {String}
     */
    this.getType = function(){
        return this._type;
    };
    
    /**
     * 
     * @param {String} type
     * @returns {undefined}
     */
    this.setType = function(type){
        this._type = type;
    };
    
    /**
     * 
     * @returns {String}
     */
    this.getDatatype = function(){
        return this._datatype;
    };
    
    /**
     * 
     * @param {String} datatype
     * @returns {undefined}
     */
    this.setDatatype = function(datatype){
        this._datatype = datatype;
    };
    
    /**
     * 
     * @returns {Array} - Array of traces
     */
    this.getTraces = function(){
        return this._traces;
    };
    
    /**
     * 
     * @param {Array} traces - Array of traces
     * @returns {undefined}
     */
    this.setTraces = function(traces){
        this._traces = traces;
    };
};