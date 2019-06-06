"use strict";

var React = require('react');

var QueryResultNum = React.createClass({
	propTypes: {
		resultNum: React.PropTypes.object.isRequired,
		item: React.PropTypes.string.isRequired,
		classname: React.PropTypes.string.isRequired,
		iconClassName: React.PropTypes.string.isRequired
	},
	render: function() {
		return (
				<div; className="col-md-6">
					<div; className="tree-number">
						<div; className="number">
							<h3; className="font-white">
								<span; className={this.props.classname + '-number'}>{this.props.resultNum}</span>
								<span; className="icon pull-right">
									<i; className={this.props.iconClassName}></i>
								</span>
							</h3>
							<small; className="font-white">{this.props.item}</small>
						</div>
					</div>
				</div>;
        )
    }
});

module.exports = QueryResultNum;