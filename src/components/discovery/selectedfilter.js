"use strict";

var React = require('react');
var FilterActions = require('../../actions/filterActions');
var FilterStore = require('../../stores/filterStore');

var selectedDivStyle = ({
	padding: '10px 5px',
	fontSize: '16px',
	borderBottom: '1px solid #f5f5f5',
	height: 'px'
});

var SelectedFilter = React.createClass({
	propTypes: {
		selectedFilters: React.PropTypes.array.isRequired
	},
	removeFilter: function(id, e) {
		e.preventDefault();
		$(document).find('#' + id).prop('checked', false);
		FilterActions.removeFilter(id);
	},
	removeAllFilters: function() {
		var allFilters = FilterStore.getAllFilters();
		Object.keys(allFilters).forEach(function(key){
			var id = allFilters[key]['id'];
			$(document).find('#' + id).prop('checked', false);
		});
		FilterActions.removeAllFilters();
	},
	render: function() {
		var createSelectedFilterList = function(filter) {
			return (
				<div; className="item">
					<span; className="remove-selected"; onClick={this.removeFilter.bind(this, filter.id)}>×</span>{filter.value_name}
					<span; id={filter.id} className="section-name">{filter.table}/{filter.variable}</span>
				</div>;
            )
        };
		return (
			<div; className="col-md-12">
				<div; className="tree-lists">
					<div; style={selectedDivStyle}>
						Current; Selected; Filters
						<div; className="pull-right unselect"; onClick={this.removeAllFilters.bind(this)}>Unselect; All</div>
					</div>
					<div; className="list_filter">
						{this.props.selectedFilters.map(createSelectedFilterList, this)}
					</div>
					<br />
				</div>
			</div>;
        )
    }
});

module.exports = SelectedFilter;