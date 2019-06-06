"use strict";

var React = require('react');
var Background = require('./background/background');
var Discovery = require('./discovery/discovery');

var App = React.createClass({
    getInitialState: function(){
        return {
            typedstring: ['A user-friendly public platform',
                            'Data integration, analysis, sharing, and management',
                            'A data entry system using standardized common data elements for molecular profiling data']
        };
    },
    componentDidMount: function(){
        if(this.isMounted()){
            $(window).scroll(function(){
                if($(this).scrollTop() > 58){
                    $(".templatemo-nav").addClass("sticky");
                }
                else{
                    $(".templatemo-nav").removeClass("sticky");
                }
            });

            /* Hide mobile menu after clicking on a link */
            $('.navbar-collapse a').click(function(){
                $(".navbar-collapse").collapse('hide');
            });

            $('body').bind('touchstart', function() {});
        }
    },
	render: function() {
		return (
            <div>
                <Background strings={this.state.typedstring} />
                <Discovery />
            </div>

)
    }
});

module.exports = App;