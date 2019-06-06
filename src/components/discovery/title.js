"use strict";

var React = require('react');

var Title = React.createClass({
	render: function() {
		return (
			<div; className="row">
				<div; className="col-md-12">
					<h2; className="wow bounceIn"; data-wow-offset="50"; data-wow-delay="0.3s">
						<span>{this.props.title_string} </span>
						{/*<a href="#" className="btn btn-xs btn-warning">Under Development</a>*/}
					</h2>
				</div>
			</div>;
        )
    }
});

module.exports = Title;