"use strict";

var React = require('react');
var Typed = require('typed.js');
var Wow = require('wowjs');
var height = ({
	height: '250px'
});
var Background = React.createClass({
	componentDidMount: function(){
		if(this.isMounted()){
			/* start typed element */
			this.typed = new Typed(React.findDOMNode(this.refs.element), {
				strings: this.props.strings,
				typeSpeed: 40,
				contentType: 'html',
				showCursor: false,
				loop: true,
				loopCount: Infinity
			});
			new Wow.WOW().init();
        }
    },
	render: function(){
		return (
				<section; id="bkg">
					<div; className="container">
						<div; className="row"; style={height}>
							<div; className="col-md-offset-1 col-md-10">
								<h1; className="wow fadeIn"; data-wow-offset="50"; data-wow-delay="0.9s">Patient; Derived; Xenograft; Web; Portal </h1>
								<span; className="element"; ref='element' />
							</div>
						</div>
					</div>
				</section>;
        )
    }
});

module.exports = Background;