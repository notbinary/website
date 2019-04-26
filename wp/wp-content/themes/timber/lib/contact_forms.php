<?php

/*
* Remove Contact Form 7 JS and CSS, load on pages explicitly
*/
add_filter('wpcf7_load_js', '__return_false');
add_filter('wpcf7_load_css', '__return_false');


/*
* Allow editors to see Flamingo
*/
function fl_map_meta_cap($meta_caps){
    $meta_caps = array(
        'flamingo_edit_contacts' => 'delete_pages',
        'flamingo_edit_contact' => 'delete_pages',
        'flamingo_delete_contact' => 'delete_pages',
        'flamingo_edit_inbound_messages' => 'delete_pages',
        'flamingo_delete_inbound_message' => 'delete_pages',
        'flamingo_delete_inbound_messages' => 'delete_pages',
        'flamingo_spam_inbound_message' => 'delete_pages',
        'flamingo_unspam_inbound_message' => 'delete_pages');

    return $meta_caps;
}
add_filter('flamingo_map_meta_cap', 'fl_map_meta_cap');
