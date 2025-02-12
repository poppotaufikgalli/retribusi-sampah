import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import * as Popper from '@popperjs/core'
window.Popper = Popper
import 'bootstrap'

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });

import jszip from 'jszip';
//import pdfmake from 'pdfmake';
import pdfMake from "pdfmake/build/pdfmake";
//import * as pdfFonts from "pdfmake/build/vfs_fonts";

//pdfMake.vfs = pdfFonts.pdfMake.vfs;

pdfMake.fonts = {
  Roboto: {
    normal: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Regular.ttf',
    bold: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Medium.ttf',
    italics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-Italic.ttf',
    bolditalics: 'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.66/fonts/Roboto/Roboto-MediumItalic.ttf',
  },
};

import DataTable from 'datatables.net-dt';
//import 'datatables.net-buttons-dt';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import 'datatables.net-buttons/js/buttons.print.mjs';
import 'datatables.net-responsive-bs5';
//import 'datatables.net-select-dt';
import 'datatables.net-select-bs5';

window.pdfMake = pdfMake;
window.JSZip = jszip;
window.DataTable = DataTable

import GLightbox from 'glightbox/dist/js/glightbox.js';
window.GLightbox = GLightbox()

import Highcharts from 'highcharts';
import Highcharts3D from 'highcharts/highcharts-3d';
// Alternatively, this is how to load Highstock. Highmaps and Highcharts Gantt are similar.
// import Highcharts from 'highcharts/highstock';
// Load the exporting module.
import Exporting from 'highcharts/modules/exporting';
import Accessibility from 'highcharts/modules/accessibility';
// Initialize exporting module. (CommonJS only)
Highcharts3D(Highcharts);
Exporting(Highcharts);
Accessibility(Highcharts)

import Choices from 'choices.js'
window.Choices = Choices

window.Highcharts = Highcharts;