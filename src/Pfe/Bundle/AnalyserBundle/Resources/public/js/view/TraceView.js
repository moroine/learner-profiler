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
View.TraceView = function (ui) {
    this._ui = ui;

    this._current = null;
    this._filters = [];
    this._increment = 0;

    this._$self = $("#trace-modal");
    this._$filterTable = this._$self.find('#filter-table');
};

View.TraceView.prototype.init = function ()
{
    var scope = this;
    this._$self.on("show.bs.modal", function (e) {
        scope.onModalShow();
    });
    this._$self.on("click", '.filter-remove', function (e) {
        scope.removeFilter($(e.target).closest('tr'));
    });
    this._ui._filterModal._$self.on("hide.bs.modal", function (e) {
        scope.onFilterModalHide(e);
    });
};

View.TraceView.prototype.onModalShow = function () {
    var datatype = this._ui._navigation._datatype;
    var type = this._ui._navigation._type;

    switch (type) {
        case "graph":
            this._$self.find(".map-type-option").addClass('hidden');
            break;
        case "map":
            this._$self.find(".map-type-option").removeClass('hidden');
            break;
    }

    this._current = null;
    this._filters = [];
};

View.TraceView.prototype.onFilterModalHide = function (e) {
    var filter = this._ui._filterModal._current;

    this._filters.push(filter);

    this._$filterTable.append(this.generateFilterHtml(filter));
};

View.TraceView.prototype.generateFilterHtml = function (filter) {
    var html = [];

    html.push('<tr data-filter-id="' + filter.getId() + '">');
    html.push('<td><a href="#" class="filter-remove"><span class="glyphicon glyphicon-remove text-danger"></span></a></td>');
    html.push('<td>' + filter.getName() + '</td>');
    html.push('<td>' + filter.getRule() + '</td>');
    html.push('<td>' + filter.getType() + '</td>');
    html.push('<td>' + filter.getField() + '</td>');
    var value = filter.getValue() || 'NULL';
    html.push('<td>' + value + '</td>');
    html.push('</tr>');

    return html.join('');
};

View.TraceView.prototype.removeFilter = function ($elmt) {
    var id = parseInt($elmt.data('filter-id'));

    for (var i = 0; i < this._filters.length; i++) {
        if (this._filters[i].getId() === id) {
            this._filters = this._filters.splice(i, 1);
            $elmt.remove();
        }
    }
};