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
            var choroplethTrace = visualisation.getActiveChoropleth();
            //var bubbleTrace = visualisation.getActiveBubble();

            if (choroplethTrace !== null) {
                var choroplethData = choroplethTrace.getData(visualisation, visualisation.getDatatype(), visualisation.getLegends(), this.displayTrace);
                //var bubbleData = bubbleTrace.getData(visualisation.getDatatype(), visualisation.getLegends(), this.displayTrace);
            }

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
     * @param {Trace} trace
     * @param {type} choroplethData
     * @returns {undefined}
     */
    this.displayTrace = function (visualisation, trace, choroplethData) {
        var legends = visualisation.getLegends();
        var i;

        var fillKeys = {
            defaultFill: '#CCCCCC'
        };

        for (i = 0; i < legends.length; i++) {
            fillKeys[legends[i].getName()] = legends[i].getColor();
            for (country in choroplethData) {

                if (legends[i].getMin() === null) {
                    if (choroplethData[country].number < legends[i].getMax()) {
                        choroplethData[country].fillKey = legends[i].getName();
                    }
                } else if (legends[i].getMax() === null) {
                    if (choroplethData[country].number >= legends[i].getMin()) {
                        choroplethData[country].fillKey = legends[i].getName();
                    }
                }
                else if (choroplethData[country].number >= legends[i].getMin() && choroplethData[country].number < legends[i].getMax()) {
                    choroplethData[country].fillKey = legends[i].getName();
                }
            }
        }

        var container = document.getElementById("visualisation_container");

        document.getElementById("container").innerHTML = "";
        container = new Datamaps({
            element: document.getElementById('container'),
            fills: fillKeys,
            data: choroplethData,
            geographyConfig: {
                popupTemplate: function (geo, data) {
                    if (data === null) {
                        return '<div class="hoverinfo">' + geo.properties.name + '<br />None</div>';
                    } else {
                        var number = data.number;
                        return '<div class="hoverinfo">' + geo.properties.name + ' : ' + number + '<br />Size : ' + data.fillKey + '</div>';
                    }
                }
            }
        });
        container.legend();


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