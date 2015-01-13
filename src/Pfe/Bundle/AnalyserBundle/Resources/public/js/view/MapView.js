/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if(typeof View === "undefined") { var View = {}; }


/**
 * 
 * @constructor
 */
View.MapView = function(){
    /**
     * 
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setVisualisation = function(visualisation){ // visualisation from "VisualisationRepository"
        if(visualisation.getType() === "map"){
            this.setMap(visualisation);
        } else {
            this.setGraph(visualisation);
        }
    };
    
    /**
     * 
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setMap = function(visualisation){
        
    };
    
    /**
     * 
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setGraph = function(visualisation){
        
    };
};