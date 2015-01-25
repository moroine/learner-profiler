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
    this._datamaps = null;
    this._currentBubbleData = null;
    this._regions = {
        afrique: {
            latitude: 23,
            longitude: -3,
            scale: 400
        },
        ameriqueDuNord: {
            latitude: -105,
            longitude: 54,
            scale: 320
        },
        ameriqueDuSud: {
            latitude: -55,
            longitude: -15,
            scale: 400
        },
        europe: {
            latitude: 11,
            longitude: 54,
            scale: 600
        },
        asie: {
            latitude: 100,
            longitude: 44,
            scale: 350
        },
        oceanie: {
            latitude: 133,
            longitude: -30,
            scale: 400
        },
        monde: {
            latitude: 0,
            longitude: 12,
            scale: 159.5
        }
    };
    /**
     *
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setVisualisation = function (visualisation) { // visualisation from "VisualisationRepository"
        if (this._datamaps !== null) {
            document.getElementById("visualisation_container").innerHTML = "";
            this._datamaps = null;
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
        /*
         Place pour insérer les boutons de zooms (Monde, Afrique, Europe, ...)

         Paramètres pour méthode "zoom" :
         - Afrique : 23, -3, 400
         - Amérique du Nord : -105, 54, 320
         - Amérique du Sud : -55, -15, 400
         - Europe : 11, 54, 600
         - Asie : 100, 44, 350
         - Océanie : 133, -30, 400
         - Monde : 0, 12, 159.5

         */
        if (visualisation.getDatatype() === "apprenants") {
            // For active trace in visualisation, get d3js data for datatype (trace.getData(datatype, legend))
            var choroplethTrace = visualisation.getActiveChoropleth();
            var bubbleTrace = visualisation.getActiveBubble();
            if (choroplethTrace !== null) {
                var scope = this;
                var callback = function (v, t, d) {
                    scope.displayTrace(v, t, d);
                    var bubbleDataCallback = scope.displayTrace.bind(scope);
                    bubbleTrace.getData(visualisation, visualisation.getDatatype(), visualisation.getLegends(), bubbleDataCallback);
                };
                var choroplethData = choroplethTrace.getData(visualisation, visualisation.getDatatype(), visualisation.getLegends(), callback);
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
        for (var item in histogramData) {
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
                });
            }

            svg.append("g")
                    .attr("class", "x axis")
                    .attr("transform", "translate(0," + height + ")")
                    .call(xAxis);
            var ticks = document.getElementsByClassName("x axis")[0].children;
            for (var i = 0; i < ticks.length - 1; i++) {
                ticks[i].setAttribute("transform", "translate(" + (20 * i + 6) + ", 17) rotate(-90)");
                ticks[i].firstChild.style.visibility = "hidden";
            }



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
    var scope = this;
};
var scope = this;
/**
 *
 * @param {Visualisation} visualisation
 * @param {Trace} trace
 * @param {type} data
 * @returns {undefined}
 */
View.MapView.prototype.displayTrace = function (visualisation, trace, data) {
    if (trace.getType() === "choropleth") {
        var legends = visualisation.getLegends();
        var i;
        var fillKeys = {
            defaultFill: '#CCCCCC'
        };
        for (i = 0; i < legends.length; i++) {
            fillKeys[legends[i].getName()] = legends[i].getColor();
            for (country in data) {

                if (legends[i].getMin() === null) {
                    if (data[country].number < legends[i].getMax()) {
                        data[country].fillKey = legends[i].getName();
                    }
                } else if (legends[i].getMax() === null) {
                    if (data[country].number >= legends[i].getMin()) {
                        data[country].fillKey = legends[i].getName();
                    }
                }
                else if (data[country].number >= legends[i].getMin() && data[country].number < legends[i].getMax()) {
                    data[country].fillKey = legends[i].getName();
                }
            }
        }

        var container = document.getElementById("visualisation_container");
        document.getElementById("visualisation_container").innerHTML = "";
        this._datamaps = new Datamap({
            element: document.getElementById('visualisation_container'),
            fills: fillKeys,
            data: data,
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
        this._datamaps.legend();
    } else {
        var bubbleData = [];
        bubbleData = this.createBubble(data);
        this._currentBubbleData = bubbleData;
        if (this._datamaps instanceof Datamap) {
            this._datamaps.bubbles(bubbleData, {
                popupTemplate: function (geo, data) {
                    return '<div class="hoverinfo">Size : ' + data.nombre + '</div>';
                }
            });
        }
    }
};
/**
 *
 * @returns {View.UiView}
 */
View.MapView.prototype.getUiview = function () {
    return this._ui;
};

View.MapView.prototype.createBubble = function (data) {
    var bubbleData = [];
    var found;
    for (var j = 0; j < data.length; j++) {
        found = false;
        for (var i = 0; i < bubbleData.length; i++) {
            if (Converter.isNear(bubbleData[i], data[j])) {
                bubbleData[i].nombre = Number(bubbleData[i].nombre) + 1;
                found = true;
                break;
            }
        }
        if (!found) {
            bubbleData.push({
                "latitude": Number(data[j].latitude),
                "longitude": Number(data[j].longitude),
                "nombre": 1
            });
        }
    }

    var range = {
        min: 5,
        max: 50
    };
    var maxArray = 1;
    var minArray = 1;
    for (i = 0; i < bubbleData.length; i++) {
        if (bubbleData[i].nombre) {
            if (bubbleData[i].nombre < minArray) {
                minArray = bubbleData[i].nombre;
            }
            if (bubbleData[i].nombre > maxArray) {
                maxArray = bubbleData[i].nombre;
            }
        }
    }

    for (i = 0; i < bubbleData.length; i++) {
        if (bubbleData[i].nombre) {
            bubbleData[i].radius = ((range.max - range.min) / (maxArray - 1)) * bubbleData[i].nombre + range.min - (range.max / (maxArray - 1));
        }
    }

    return bubbleData;
};

View.MapView.prototype.setProjection = function (region) {
    region = region.toLowerCase() || "monde";
    if (this._regions[region]) {
        this.zoom(parseFloat(this._regions[region].latitude), parseFloat(this._regions[region].longitude), parseFloat(this._regions[region].scale));
    }
};

View.MapView.prototype.zoom = function (latitude, longitude, scaleValue) {
    var fillKeys = this._datamaps.options.fills;
    var currentData = this._datamaps.options.data;
    document.getElementById("visualisation_container").innerHTML = "";
    this._datamaps = new Datamap({
        element: document.getElementById('visualisation_container'),
        setProjection: function (element) {
            var projection = d3.geo.equirectangular()
                    .center([latitude, longitude])
                    .scale(scaleValue)
                    .translate([element.offsetWidth / 2, element.offsetHeight / 2]);
            var path = d3.geo.path()
                    .projection(projection);
            return {path: path, projection: projection};
        },
        fills: fillKeys,
        data: currentData,
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
    var newBubbles = [];
    for (var i = 0; i < this._currentBubbleData.length; i++) {
        newBubbles[i] = {};
        $.extend(newBubbles[i], this._currentBubbleData[i]);
    }

    this._datamaps.bubbles(newBubbles, {
        popupTemplate: function (geo, data) {
            return '<div class="hoverinfo">Size : ' + data.nombre + '</div>';
        }
    });
};