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
    this._$form = this._$self.find('form');
    this._$name = this._$self.find('[name="trace-name"]');
    this._$operation = this._$self.find('[name="trace-operation"]');
    this._$type = this._$self.find('[name="trace-type"]');

    this._$group = $('#group_form');
    this._$group_type = this._$group.find('[name="group-type"]');
    this._$group_field = this._$group.find('[name="group-field"]');

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
    this._$self.find('#trace-confirm').on("click", function (e) {
        scope.onConfirm();
        e.preventDefault();
    });
};

View.TraceView.prototype.onModalShow = function () {
    var datatype = this._ui._navigation._datatype;
    var type = this._ui._navigation._type;

    this._$form[0].reset();

    switch (type) {
        case "graph":
            this._$self.find(".map-type-option").addClass('hidden');
            this._$self.find(".graph-type-option").removeClass('hidden');
            break;
        case "map":
            this._$self.find(".map-type-option").removeClass('hidden');
            this._$self.find(".graph-type-option").addClass('hidden');
            break;
    }

    this._$filterTable.html('<tr><th>#</th><th>Name</th><th>Rule</th><th>Type</th><th>Field</th><th>Value</th></tr>');

    var scope = this;

    if (type === 'map') {
        this._$group.closest('fieldset').prop('disabled', true);
        this._$type.on('change', function (e) {
            scope.updateGroup();
        });
    } else {
        this._$group.closest('fieldset').prop('disabled', false);
        this._$type.off('change');
    }

    this._current = null;
    this._filters = [];
};

View.TraceView.prototype.updateGroup = function ()
{
    if (this._$type.filter(':checked').val() === 'choropleth')
    {
        this._$group_type.val('localisation');
        this._$group_field.val('isoAlpha3');
    } else {
        this._$group_type.val('localisation');
        this._$group_field.val('city');
    }
};

View.TraceView.prototype.fill = function (trace)
{
    this._$name.val(trace.getName());
    this._$operation.val(trace.getOperation());
    this._$type.val(trace.getType());
    this._$group_type.val(trace.getGroup().type);
    this._$group_field.val(trace.getGroup().field);

    var filters = trace.getFilters();
    for (var i = 0; i < filters.length; i++)
    {
        this._filters.push(filters[i]);
        this._$filterTable.append(this.generateFilterHtml(filters[i]));
    }
};

View.TraceView.prototype.onFilterModalHide = function (e) {
    var filter = this._ui._filterModal._current;

    if (filter) {
        this._filters.push(filter);

        this._$filterTable.append(this.generateFilterHtml(filter));
    }
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

View.TraceView.prototype.onConfirm = function () {
    var trace = new Entity.Trace();

    var name = this._$name.val() || null;
    var operation = this._$operation.val() || null;
    var type = this._$type.filter(':checked').val() || null;
    var group_type = this._$group_type.val() || null;
    var group_field = this._$group_field.val() || null;

    if (name === null || operation === null || type === null || group_type === null || group_field === null) {
        alert("Invalid Filter: Please fill all inputs");

        return;
    }

    trace.setId(this._increment);
    trace.setName(name);
    trace.setOperation(operation);
    trace.setType(type);
    trace.setGroup({type: group_type, field: group_field});
    trace.setFilters(this._filters);

    this._increment++;

    this._current = trace;

    this._$self.modal('hide');
};