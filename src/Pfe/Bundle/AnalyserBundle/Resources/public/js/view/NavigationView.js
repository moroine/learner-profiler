if (typeof View === "undefined") {
    /**
     * @namespace
     */
    var View = {};
}

/**
 * @class
 * @param {View.UiView} ui
 */
View.NavigationView = function (ui) {
    /**
     * @type {View.UiView}
     */
    this._ui = ui;
    this._$self = $("#navigation");
    this._type = this._$self.find("#navigation-type .active a").data('navigation-type');
    this._datatype = this._$self.find("#navigation-datatype .active a").data('navigation-datatype');
    this._visualisations = {
        "map": {"participant": new Entity.Visualisation("map", "participant"), "action": new Entity.Visualisation("map", "action")},
        "graph": {"participant": new Entity.Visualisation("graph", "participant"), "action": new Entity.Visualisation("graph", "action")},
    };
};
View.NavigationView.prototype.init = function () {
    var scope = this;
    this._$self.on('click', 'li a', function (e) {
        scope.onNavigationTypeEvent(e.target);
        e.preventDefault();
    });
    this._ui._traceModal._$self.on('hide.bs.modal', function (e) {
        scope.onTraceModalHide();
    });
    $("#recap").on('change', '.trace-enable', function (e) {
        scope.enableTrace($(e.target));
        e.preventDefault();
    });
    $("#recap").on('click', '.trace-remove', function (e) {
        scope.removeTrace($(e.target));
        e.preventDefault();
    });

    this.update();
};
View.NavigationView.prototype.update = function () {
    var visualisation = this._visualisations[this._type][this._datatype];
    this._ui.getMapView().setVisualisation(visualisation);
    $("#recap").html(this.getHtmlRecap(visualisation.getTraces()));
};

View.NavigationView.prototype.enableTrace = function ($target) {
    var id = parseInt($target.data('trace-id'));
    var active = $target.is(':checked');

    var visualisation = this._visualisations[this._type][this._datatype];
    var traces = visualisation.getTraces();

    for (var i = 0; i < traces.length; i++)
    {
        if (traces[i].getId() === id) {
            var type = traces[i].getType();
            traces[i].setActive(active);
        }
    }
    if (active) { // Then we have to ckech conflicts
        for (var i = 0; i < traces.length; i++)
        {
            if (traces[i].getId() !== id && type === traces[i].getType()) {
                traces[i].setActive(false);
            }
        }
    }
    this.update();
};

View.NavigationView.prototype.removeTrace = function ($target) {
    var id = parseInt($target.closest('.trace-remove').data('trace-id'));

    var visualisation = this._visualisations[this._type][this._datatype];
    var traces = visualisation.getTraces();
    var t = [];
    for (var i = 0; i < traces.length; i++)
    {
        if (traces[i].getId() !== id) {
            t.push(traces[i]);
        }
    }

    this._visualisations[this._type][this._datatype].setTraces(t);

    this.update();
};

View.NavigationView.prototype.getHtmlRecap = function (traces)
{
    var html = [];
    html.push('<tr><th colspan="2">#</th><th>Name</th><th>Type</th><th>GroupType</th><th>GroupField</th></tr>')

    for (var i = 0; i < traces.length; i++) {
        var checked = (traces[i].isActive()) ? 'checked' : '';
        html.push("<tr>");
        html.push('<td><div><label><input type="checkbox" class="trace-enable" data-trace-id="' + traces[i].getId() + '" ' + checked + '></label></div></td>');
        html.push('<td><a href="#" class="trace-remove" data-trace-id="' + traces[i].getId() + '"><span class="glyphicon glyphicon-remove text-danger"></span></a></td>');
        html.push('<td>' + traces[i].getName() + '</td>');
        html.push('<td>' + traces[i].getType() + '</td>');
        html.push('<td>' + traces[i].getGroup().type + '</td>');
        html.push('<td>' + traces[i].getGroup().field + '</td>');
        html.push("</tr>");
    }

    return html.join('');
};
/**
 *
 * @param {HTMLElement} target
 */
View.NavigationView.prototype.onNavigationTypeEvent = function (target) {
    var $target = $(target);
    var nav = $target.data('navigation');
    var value = $target.data('navigation-' + nav);
    $target.parent().parent().find('.active').removeClass('active');
    $target.parent().addClass('active');
    switch (nav) {
        case 'type':
            this.setType(value);
            break;
        case 'datatype':
            this.setDataType(value);
            break;
    }
};
/**
 * @param {string} type
 */
View.NavigationView.prototype.setType = function (type) {
    this._type = type;
    this.update();
};
/**
 * @param {string} datatype
 */
View.NavigationView.prototype.setDataType = function (datatype) {
    this._datatype = datatype;
    this.update();
};
View.NavigationView.prototype.onTraceModalHide = function () {
    var trace = this._ui._traceModal._current;
    if (!trace) {
        return;
    }

    this._visualisations[this._type][this._datatype].addTrace(trace);
    this.update();
};