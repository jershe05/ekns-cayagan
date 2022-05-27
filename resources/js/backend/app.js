import 'alpinejs'
// import 'mdb-ui-kit'

window.$ = window.jQuery = require('jquery');
window.Swal = require('sweetalert2');

// CoreUI
require('@coreui/coreui');

// Boilerplate
window.Vapor = require('laravel-vapor');
require('../plugins');
require('./chart');
require('./alert');
require('./location');


document.addEventListener('DOMContentLoaded', () => {
    $('#dashboard-legend .close').on('click', function () {
        $('#dashboard-legend').addClass('d-none');
    });
});



// require('./google-maps');
