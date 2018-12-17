/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)
require('../css/app.scss');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
var $ = require('jquery');

// import the function from greet.js (the .js extension is optional)
// ./ (or ../) means to look for a local file
var greet = require('./greet');

// JS is equivalent to the normal "bootstrap" package
// no need to set this to a variable, just require it
require('bootstrap');
// or you can include specific pieces
//require('bootstrap/js/dist/tooltip');
//require('bootstrap/js/dist/popover');

$(document).ready(function() {
    $('body').append('<small>'+greet('john')+'</small>');

    $('[data-toggle="popover"]').popover();
});
