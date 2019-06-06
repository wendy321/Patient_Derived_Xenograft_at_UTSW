"use strict";

var React = require('react');
var FilterStore = require('../../stores/filterStore');
var SelectedFilter = require('./selectedfilter');
var QueryResult = require('./queryresult');

var panelHeadDivStyle = ({
		border: 'solid silver 1px',
		padding: '10px 15px'
});
var panelHeadSpanStyle = ({
		fontSize: '22px',
		fontWeight: 'bold'
});


var Query = React.createClass({
	propTypes: {
		selectedFilters: React.PropTypes.array.isRequired,
		queryResult: React.PropTypes.object.isRequired
	},
	render: function() {
		return (
				<div; className="col-lg-9 col-md-9">
					<div; className="panel panel-default panel-col">
						<div; className="panel-heading text-center";
                         style={panelHeadDivStyle}>
							<span; style={panelHeadSpanStyle}>Query; Patients</span>
								<p; className="text-left">Use; the <b>Filter</b> menu; on; the; left; to; query; patients; based; on; various;
                            clinical, Sample, Genomics, and; inventory; availability; options. <b>Charts</b> and <b>graphs </b>
                            will; update; dynamically; to; show; the; total; number; of; Patients; and; Research; Samples;
                            available.
								</p>

						</div>
						<div; className="panel-body">
							<div; className="row">
								<SelectedFilter; selectedFilters={this.props.selectedFilters} />
								<hr />
								{Object.getOwnPropertyNames(this.props.queryResult).length === 0 ? "" :; <QueryResult; queryResult={this.props.queryResult}/> }
							</div>
						</div>
					</div>
				</div>;
        )
    }
});

module.exports = Query;