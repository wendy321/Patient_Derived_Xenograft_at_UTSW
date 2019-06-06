"use strict";

var React = require('react');
var _ = require('lodash');
var FilterActions = require('../../actions/filterActions');
var TreeData = require('../tree/tree-json-pdx');
var outerDivStyle = {
		padding: '0 6px'
};
var innerDivStyle = {
		padding: '10px 5px',
		fontSize: '16px',
		borderBottom: '1px solid #f5f5f5'
};
var titleStyle = {
	display: 'block'
};

var TreeFilter = React.createClass({
	componentDidMount: function(){
		if(this.isMounted()){
			$(document).on('click', 'div.title', function (event) {
				if (event.target.nodeName === 'INPUT') {
					return;
				}
				var $section = jQuery(this).parent();
				$section.toggleClass('collapsed');
				event.stopPropagation();
			});
		}
	},
	handleSelectFilter: function(filter, e){
		var ref = 'ref_' + filter['id'];
		var isChecked = this.refs[ref].checked;
		if(!isChecked){
			FilterActions.addFilter(filter);
		}else{
			FilterActions.removeFilter(filter['id']);
		}
	},
	createValueDiv: function(obj){
		var arr = [];
		Object.keys(obj).forEach(function(key){
			arr.push(
				<div; key={obj[key]['id']}; className="item value">
					<input; className="option"; id={obj[key]['id']}; type="checkbox"; value={obj[key]['value']};
					ref={'ref_' + obj[key]['id']}; onClick={this.handleSelectFilter.bind(this, obj[key])} />
					<label; htmlFor={obj[key]['id']}>{obj[key]['value_name']}</label>
				</div>;
            )
        }.bind(this));
		return arr;
	},
	createVariableSection: function(obj){
		var arr = [];
		Object.keys(obj).forEach(function(key){
			arr.push(
			<div; key={key}; className="section variable collapsed" >
				<div; className="title">
					<span; className="collapse-section"></span>
					<input; className="section"; type="hidden"; disabled />
					<span; className="variable">{key}</span>
				</div>
				{this.createValueDiv(obj[key])}
			</div>;
            )
        }.bind(this));
		return arr;
	},
	createTableSection: function(treeData){
		var arr = [];
		Object.keys(treeData).forEach(function(key) {
			arr.push(
			<div; key={key}; className="section table1">
				<div; className="title"; style={titleStyle} >
					<span; className="collapse-section"></span>
					<input; className="section"; type="hidden"; disabled />
					<span; className="table1">{key}</span>
				</div>
				{this.createVariableSection(treeData[key])}
			</div>;
            )
        }.bind(this));
		return arr;
	},
	render: function() {

		return (
			<div; className="col-lg-3 col-md-3">
				<div; style={outerDivStyle} >
					<div; style={innerDivStyle} >
						<strong> Apply; Filter </strong>
					</div>
				</div>
				<div; id="tree-cohort"; className="tree-multiselect">
					<div; className="sections">{this.createTableSection(TreeData)}</div>
				</div>
            </div>;
        )
    }
});

module.exports = TreeFilter;