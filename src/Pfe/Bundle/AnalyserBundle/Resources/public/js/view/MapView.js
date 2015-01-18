if (typeof View === "undefined") {
    var View = {};
}


/**
 * @class
 * @param {View.UiView} ui
 */
View.MapView = function (ui) {
    /**
     * @type {View.UiView}
     */
    this._ui = ui;

    /**
     *
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setVisualisation = function (visualisation) { // visualisation from "VisualisationRepository"
        if (visualisation.getType() === "map") {
            this.setMap(visualisation);
        } else {
            this.setGraph(visualisation, "apprenants");
        }
    };

    /**
     *
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setMap = function (visualisation) {
        if (visualisation.getDatatype() === "apprenants") {
            // For active trace in visualisation, get d3js data for datatype (trace.getData(datatype, legend))
            var choroplethTrace = visualisation.getFirstChoropleth();
            var bubbleTrace = visualisation.getFirstBubble();

            var choroplethData = choroplethTrace.getData(visualisation.getDatatype(), visualisation.getLegends());
            var bubbleData = bubbleTrace.getData(visualisation.getDatatype(), visualisation.getLegends());

            this.displayMap();

        } else { // Datatype === "actions"

        }
    };

    /**
     *
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setGraph = function (visualisation) {

    };

    /**
     *
     * @param {type} choroplethData
     * @param {type} bubbleData
     * @param {Legend[]} legends
     * @returns {undefined}
     */
    this.displayMap = function (choroplethData, bubbleData, legends) {

    };

    this.displayGraph = function () {

    };
};

/**
 *
 * @returns {View.UiView}
 */
View.MapView.prototype.getUiview = function () {
    return this._ui;
};