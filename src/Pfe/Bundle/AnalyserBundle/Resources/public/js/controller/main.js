"use strict";
var visuMap, visuGraph;
var _ui;
window.onload = function () {
    visuMap = new Entity.Visualisation("map", "apprenants");
    visuGraph = new Entity.Visualisation("graph", "apprenants");
    visuMap._traces[0] = new Entity.Trace("count", "choropleth");
    visuGraph._traces[0] = new Entity.Trace("count", "histogram");
    visuMap._traces[1] = new Entity.Trace("count", "bubble");

    var legends = [];
    legends.push(new Entity.Legend("VeryFew", null, 5, "#f1c40f"));
    legends.push(new Entity.Legend("Few", 5, 10, "#f39c12"));
    legends.push(new Entity.Legend("Correct", 10, 25, "#e67e22"));
    legends.push(new Entity.Legend("big", 25, 50, "#d35400"));
    legends.push(new Entity.Legend("veryBig", 50, 100, "#e74c3c"));
    legends.push(new Entity.Legend("soMuch", 100, null, "#c0392b"));

    visuMap.setLegends(legends);

    var ui = new View.UiView();
    ui.init();
    ui.getMapView().setVisualisation(visuMap);

    _ui = ui;
};
