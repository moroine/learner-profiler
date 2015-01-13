/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(typeof Repository === 'undefined'){ 
    /**
     * 
     * @namespace Repository
     */
    var Repository = {}; 
}

/**
 * @constructor
 * @returns {undefined}
 */
Repository.VisualisationRepository = function(){
    this._visualisations = {};
    this._id = 0;
    
    this.get = function(id){
        return this._visualisations.id;
    };
    
    this.persist = function(obj){
        obj.setId(this._id++);
        this._visualisations[obj.getId()] = obj;
        
        return obj;
    };
    
    this.remove = function(obj){
        delete this._visualisations[obj.getId()];
    };
};