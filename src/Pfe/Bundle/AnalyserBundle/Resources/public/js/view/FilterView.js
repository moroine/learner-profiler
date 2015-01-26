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
View.FilterView = function (ui) {
    this._ui = ui;

    this._current = null;
    this._increment = 0;

    this._$self = $("#filters-modal");
    this._$form = this._$self.find('form');
    this._$name = this._$self.find('[name="filter-name"]');
    this._$rule = this._$self.find('[name="filter-rule"]');
    this._$type = this._$self.find('[name="filter-type"]');
    this._$field = this._$self.find('[name="filter-field"]');
    this._$value = this._$self.find('[name="filter-value"]');
    this._$null_value = this._$self.find('[name="filter-value-null"]');

    var scope = this;
    this._$self.on("show.bs.modal", function (e) {
        scope.onModalShow();
    });
    this._$type.on("change", function (e) {
        scope.onInputTypeChange();
    });
    this._$self.find('#filter-confirm').on("click", function (e) {
        scope.onConfirm();
    });
};

View.FilterView.prototype.onModalShow = function () {
    this._$field.find('option').addClass('hidden');
    this._$field.find('.field-all').removeClass('hidden');

    this._current = null;
    this._$form[0].reset();
};

View.FilterView.prototype.onInputTypeChange = function () {
    this._$field.find('option').addClass('hidden');
    this._$field.find('.field-all').removeClass('hidden');
    this._$field.find('.field-' + this.getInputTypeValue()).removeClass('hidden');
};

View.FilterView.prototype.getInputTypeValue = function () {
    return this._$type.val();
};

View.FilterView.prototype.getCurrentFilter = function () {
    return this._current;
};

View.FilterView.prototype.onConfirm = function () {
    var filter = new Entity.Filter();
    var name = this._$name.val() || null;
    var rule = this._$rule.val() || null;
    var type = this._$type.val() || null;
    var field = this._$field.val() || null;
    var checked = this._$null_value.is(":checked");
    var value = this._$value.val() || null;

    if (name === null || rule === null || type === null || field === null || (!checked && value === null)) {
        alert("Invalid Filter: Please fill all inputs");

        return;
    }

    if (checked && value !== null) {
        alert("Invalid Value: Let value empty or unselect null value");

        return;
    }

    filter.setId(this._increment);
    filter.setName(name);
    filter.setRule(rule);
    filter.setType(type);
    filter.setField(field);
    filter.setValue(value); // Already null if checked

    this._increment++;

    this._current = filter;

    this._$self.modal('hide');
};