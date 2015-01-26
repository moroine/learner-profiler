"use strict";
var visuMap, visuGraph;
var _ui;
window.onload = function () {
    /*
     visuMap = new Entity.Visualisation("map", "apprenants");
     visuGraph = new Entity.Visualisation("graph", "apprenants");
     visuMap._traces[0] = new Entity.Trace("count", "choropleth");
     visuGraph._traces[0] = new Entity.Trace("count", "histogram");
     visuMap._traces[1] = new Entity.Trace("count", "bubble");

     visuMap.setLegends(legends);
     */
    var ui = new View.UiView();
    ui.init();
//    ui.getMapView().setVisualisation(visuMap);

    _ui = ui;
};
