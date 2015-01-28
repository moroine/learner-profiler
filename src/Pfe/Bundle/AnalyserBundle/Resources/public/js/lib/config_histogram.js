/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

var margin = {top: 40, right: 20, bottom: 40, left: 70},
width = 990 - margin.left - margin.right,
        height = 510 - margin.top - margin.bottom;

var formatPercent = d3.format(".0%");

var x = d3.scale.ordinal()
        .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
        .range([height, 0]);

var xAxis = d3.svg.axis()
        .scale(x)
        .orient("bottom");

var yAxis = d3.svg.axis()
        .scale(y)
        .orient("left");