import fastClick from 'fastclick';
import $ from 'jquery';

import skipLinks from './utils/skipLinks';
import iframer from './utils/iframer';
import mNav from './utils/mNav';
import setupNotices from './utils/setupNotices';

function globals () {

	// FastClick
    fastClick(document.body);

    // iframe video in body content
    iframer();

    // Small Screen Navigation
    mNav(
        '#navigation-primary-toggle',
        'navigation-primary-toggle--active',
        '#navigation-primary',
        'navigation-primary--active'
    );

    // Notices
    setupNotices();
}

$(function run () {
    console.log('ᕕ( ᐛ )ᕗ Running...');
    globals();
});
