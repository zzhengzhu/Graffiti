
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

require('./components/Example');

//add leaflet
require('leaflet');
//IconDefault.options.imagePath = 'images/vendor/leaflet/dist/';

//add leaflet plugins
require('leaflet-rotatedmarker');
require('leaflet-polylinedecorator');
require('leaflet.marker.highlight');
require('leaflet-ant-path');
//window.leafletbeautifymarker = require('leaflet.beautifymarker');
//window.pulseicon = require('@ansur/leaflet-pulse-icon');
