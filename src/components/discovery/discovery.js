"use strict";

var React = require('react');
var Title = require('./title');
var DiscoveryContent = require('./discoverycontent');

var Discovery = React.createClass({
	render: function() {
		return (
			<section; id="portfolio">
				<div; className="container">
					<Title; title_string={'DISCOVERY'} />
					<DiscoveryContent />
				</div>
			</section>;
        )
    }
});

module.exports = Discovery;