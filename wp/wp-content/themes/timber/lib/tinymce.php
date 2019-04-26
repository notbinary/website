<?php

/**
 * Customise the editor toolbar
 * @param  object $in the object that describes Tiny MCE
 * @return object     the object that describes Tiny MCE
 */
function fffunction_mce($in) {
    $in['block_formats'] = "Paragraph=p; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6;";
    return $in;
}
add_filter('tiny_mce_before_init', 'fffunction_mce');


/**
 * Customise ACF Toolbar list
 * @param  object $toolbars
 * @return object
 */
function fff_toolbars($toolbars) {

    // Add a new toolbar called "Simple"
    // - this toolbar has only 1 row of buttons
    $toolbars['Simple'] = array();
    $toolbars['Simple'][1] = array('formatselect', 'bold', 'link', 'unlink', 'bullist', 'numlist', 'blockquote', 'pastetext');

    // Add a new toolbar called "Lockdown"
    // - this toolbar has only 1 row of buttons
    $toolbars['Lockdown'] = array();
    $toolbars['Lockdown'][1] = array('bold', 'link', 'unlink', 'bullist', 'numlist', 'blockquote', 'pastetext');

    // Add a new toolbar called "Link"
    // - this toolbar has only 1 row of buttons
    $toolbars['Link'] = array();
    $toolbars['Link'][1] = array('link', 'unlink', 'pastetext');

    // return $toolbars - IMPORTANT!
    return $toolbars;
}
add_filter('acf/fields/wysiwyg/toolbars', 'fff_toolbars');
