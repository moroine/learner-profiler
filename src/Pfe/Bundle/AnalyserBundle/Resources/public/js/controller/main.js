$('document').ready(
        function () {
            var visu = new Entity.Visualisation("map", "apprenants");
            visu._traces[0] = new Entity.Trace("count", "choropleth");
            var legends = [];
            legends.push(new Entity.Legend("VeryFew", null, 5, "#f1c40f"));
            legends.push(new Entity.Legend("Few", 5, 10, "#f39c12"));
            legends.push(new Entity.Legend("Correct", 10, 25, "#e67e22"));
            legends.push(new Entity.Legend("big", 25, 50, "#d35400"));
            legends.push(new Entity.Legend("veryBig", 50, 100, "#e74c3c"));
            legends.push(new Entity.Legend("soMuch", 100, null, "#c0392b"));
            visu._traces[0].setLegends(legends);
            var ui = View.UiView();
            ui.getMapView().setVisualisation(visu);
        }
);
