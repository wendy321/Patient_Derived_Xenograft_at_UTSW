$ = jQuery = require('jquery');
var React = require('react');
var App = require('./components/App');
var InitializeActions = require('./actions/initializeActions');
InitializeActions.initApp();

/* start preloader */
$(window).load(function(){
    "use strict";
    $('.preloader').fadeOut(1000); // set duration in brackets
});
/* end preloader */

React.render( < App / >, document.getElementById('app')
)

