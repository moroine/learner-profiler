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
        if (visualisation !== null) {
            document.getElementById("visualisation_container").innerHTML = "";
        }
        if (visualisation.getType() === "map") {
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
        if (visualisation.getDatatype() === "apprenants") {
            // For active trace in visualisation, get d3js data for datatype (trace.getData(datatype, legend))
            var histogramTrace = visualisation.getActiveHistogram();
            //var bubbleTrace = visualisation.getActiveBubble();

            if (histogramTrace !== null) {
                var histogramData = histogramTrace.getData(visualisation, visualisation.getDatatype(), visualisation.getLegends(), this.displayGraph);
                //var bubbleData = bubbleTrace.getData(visualisation.getDatatype(), visualisation.getLegends(), this.displayTrace);
            }

        } else { // Datatype === "actions"

        }
    };

    /**
     *
     * @param {Visualisation} visualisation
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

        document.getElementById("visualisation_container").innerHTML = "";
        container = new Datamaps({
            element: document.getElementById('visualisation_container'),
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

    this.displayGraph = function (visualisation, trace, histogramData) {
        var tip = d3.tip()
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .html(function (d) {
                    return "<strong>Count :</strong> <span style='color:red'>" + d.nombre + "</span>";
                });

        var svg = d3.select("#visualisation_container").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");



        var abscisse = [];
        var ordonnee = [];

        for (item in histogramData) {
            abscisse.push(item);
            ordonnee.push(histogramData[item].number);
        }

        d3.json(Learner, function (error, data) {
            x.domain(abscisse);
            y.domain(ordonnee);



            var dataArray = [];
            for (item in data) {
                var nom = item;
                dataArray.push({
                    "name": nom,
                    "nombre": data[item].number
                })
            }

            svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);

            svg.select("x axis")
                    .selectAll(".tick")
                    .attr("transform", "translate(")
                    .style("text-anchor", "end")

            svg.append("g")
                    .attr("class", "y axis")
                    .call(yAxis)
                    .append("text")
                    .attr("transform", "rotate(-90)")
                    .attr("y", 0)
                    .attr("dy", ".71em")
                    .style("text-anchor", "end")
                    .text("Number of participants");

            svg.selectAll(".bar")
                    .data(dataArray)
                    .enter().append("rect")
                    .attr("class", "bar")
                    .attr("x", function (d) {
                        return x(d.name);
                    })
                    .attr("width", x.rangeBand())
                    .attr("y", function (d) {
                        return y(d.nombre);
                    })
                    .attr("height", function (d) {
                        return height - y(d.nombre);
                    })
                    .on('mouseover', tip.show)
                    .on('mouseout', tip.hide);

            svg.call(tip);
        });
    };
};



/**
 *
 * @returns {View.UiView}
 */
View.MapView.prototype.getUiview = function () {
    return this._ui;
};