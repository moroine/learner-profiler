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
    this._currentVisualisation = null;
    this._datamaps = null;
    this._currentBubbleData = null;
    this._regions = {
        afrique: {
            latitude: 23,
            longitude: -3,
            scale: 400
        },
        ameriquedunord: {
            latitude: -105,
            longitude: 54,
            scale: 320
        },
        ameriquedusud: {
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
        document.getElementById("visualisation_container").innerHTML = "";
        this._datamaps = null;
        this._currentBubbleData = null;

        this._currentVisualisation = visualisation;

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
        // For active trace in visualisation, get d3js data for datatype (trace.getData(datatype, legend))
        var choroplethTrace = visualisation.getActiveChoropleth();
        var bubbleTrace = visualisation.getActiveBubble();
        var scope = this;

        if (choroplethTrace && bubbleTrace) {
            var callback = function (d) {
                scope.displayChoroplethTrace(d);
                var bubbleDataCallback = scope.displayBubbleTrace.bind(scope);
                Provider.getData(visualisation.getDatatype(), bubbleTrace, visualisation.getLegends(), bubbleDataCallback);
            };

            Provider.getData(visualisation.getDatatype(), choroplethTrace, visualisation.getLegends(), callback);
        } else if (choroplethTrace) {
            Provider.getData(visualisation.getDatatype(), choroplethTrace, visualisation.getLegends(), scope.displayChoroplethTrace.bind(scope));
        } else if (bubbleTrace) {
            Provider.getData(visualisation.getDatatype(), bubbleTrace, visualisation.getLegends(), scope.displayChoroplethTrace.bind(scope));
        }

    };
    /**
     *
     * @param {Entity.Visualisation} visualisation
     * @returns {undefined}
     */
    this.setGraph = function (visualisation) {
        // For active trace in visualisation, get d3js data for datatype (trace.getData(datatype, legend))
        var scope = this;
        var histogramTrace = visualisation.getActiveHistogram();

        if (histogramTrace !== null) {
            Provider.getData(visualisation.getDatatype(), histogramTrace, visualisation.getLegends(), scope.displayGraph.bind(scope));
        }
    };

    this.displayGraph = function (data) {
        var abscisse = [];
        var ordonnee = [];
        var formattedDatas = [];
        var max = 0;

        for (var i = 0; i < data.length; i++)
        {
            // field group by
            var field = this._currentVisualisation.getActiveHistogram()._group.field;
            var label = data[i][field] || 'none';
            var value = parseFloat(data[i].entry);

            abscisse.push(label); // Ave Label Value
            // ordonnee.push(); // Axe Label Value
            max = (value > max) ? value : max;
            formattedDatas.push({// The Bar data
                label: label,
                entry: value,
            });
        }

        max *= 1.1;

        /** PopOver **/
        var tip = d3.tip() // The pop Over
                .attr('class', 'd3-tip')
                .offset([-10, 0])
                .html(function (d) {
                    return "<strong>" + d.label + " :</strong> <span style='color:red'>" + d.entry + "</span>"; // Template
                });

        // Adapt Canvas Properties
        var svg = d3.select("#visualisation_container").append("svg")
                .attr("width", width + margin.left + margin.right)
                .attr("height", height + margin.top + margin.bottom)
                .append("g")
                .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

        /** Apply popover **/
        svg.call(tip);


        /** Axis **/
        x.domain(abscisse);
        y.domain([0, max]);

        svg.append("g")
                .attr("class", "x axis")
                .attr("transform", "translate(0," + height + ")")
                .call(xAxis)
                .style("text-anchor", "start");

        var ticks = document.getElementsByClassName("x axis")[0].children;
        for (var i = 0; i < ticks.length - 1; i++) {
            ticks[i].setAttribute("transform", "translate(" + (x(abscisse[i]) + 10) + ", " + ((i % 2) * 15) + ")");
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
                .text("Count result");

        /** Bars **/
        svg.selectAll(".bar")
                .data(formattedDatas)
                .enter().append("rect")
                .attr("class", "bar")
                .attr("x", function (d) {  // Set x bar coordonnate in function of datas
                    return x(d.label);
                })
                .attr("width", function (d) {
                    return x.rangeBand();
                })
                .attr("y", function (d) {  // Set y bar coordonnate in function of datas
                    return y(d.entry);
                })
                .attr("height", function (d) {  // Set height bar coordonnate in function of datas
                    return height - y(d.entry);
                })
                .on('mouseover', tip.show)
                .on('mouseout', tip.hide);
    };
};

/**
 *
 * @param {Array} data
 * @returns {undefined}
 */
View.MapView.prototype.displayChoroplethTrace = function (data) {
    var legends = this._currentVisualisation.getLegends();
    var container = document.getElementById("visualisation_container");
    var fillKeys = {defaultFill: '#CCCCCC'};
    var formattedDatas = [];

    container.innerHTML = "";

    for (var i = 0; i < legends.length; i++)
    {
        fillKeys[legends[i].getName()] = legends[i].getColor();
    }

    for (var i = 0; i < data.length; i++)
    {
        var f;
        var number = data[i].entry;
        for (var j = 0; j < legends.length; j++)
        {
            if (legends[j].getMin() === null) {
                if (number < legends[j].getMax()) {
                    f = legends[j].getName();
                }
            } else if (legends[j].getMax() === null) {
                if (number >= legends[j].getMin()) {
                    f = legends[j].getName();
                }
            } else if (number >= legends[j].getMin() && number < legends[j].getMax()) {
                f = legends[j].getName();
            }
        }
        formattedDatas[data[i].isoAlpha3] = {
            number: number,
            fillKey: f
        };
    }

    this._datamaps = new Datamap({
        element: container,
        fills: fillKeys,
        data: formattedDatas,
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
};

View.MapView.prototype.displayBubbleTrace = function (data) {
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

/**
 *
 * @param {string} region
 * @returns {undefined}
 */
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
    if (this._currentBubbleData) {
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
    }
};