/* eslint-disable no-undef */
window._ = require('lodash');

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.headers.common['Content-Type'] = 'application/json';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

window.Echo = new Echo({
    broadcaster      : 'reverb',
    key              : 'eoytyybulduyb02y32g6',
    wsHost           : process.env.MIX_REVERB_HOST,
    wsPort           : process.env.MIX_REVERB_PORT,
    wssPort          : process.env.MIX_REVERB_PORT,
    forceTLS         : false,
    enabledTransports: ['ws', 'wss'],
});
