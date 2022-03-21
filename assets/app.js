/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './scss/app.scss';
// start the Stimulus application
import './bootstrap';
require('leaflet/dist/leaflet');
require('html2pdf.js');
require('./js/map/map');
require('./js/materialize-init.js');
require('./js/nav.js');
require('./js/listMainStudies.js');
require('./js/keywords.js');
require('./js/citySearch');
require('./js/flashes');
require('./js/jstopdf');
require('./js/home');
require('./js/deletePicture');
require('./js/showpictures');
require('./js/fileSizeVerificator');
require('./js/saveToExcel');
require('./js/subCategoiesSelector');
