<?php


/*
* Get selected or fallback
*/
function get_selected_or_fallback($my_args) {

    $defaults = array(
        'post' => null,
        'num_items' => 3,
        'field' => 'acf_field_name',
        'array_label' => false,
        'type' => 'post_type',
        'orderby' => 'menu',
        'order' => 'ASC',
        'args' => array()
    );
    $my_args = wp_parse_args($my_args, $defaults);

    // set featured to 0
    $count = 0;
    $selected = [];
    $fallbacks = [];
    $selected_items = $my_args['post']->get_field($my_args['field']);

    // check if array and get selected if so
    if (is_array($selected_items)) {
        $selected = array_filter(array_map(function ($i) use ($my_args) {
            if ($my_args['array_label'] == false) {
                $p = new TimberPost($i->ID);
                if ($p->post_status == 'publish') {
                    return $p;
                }
            }

            elseif ($i[$my_args['array_label']]) {
                $p = new TimberPost($i[$my_args['array_label']]->ID);
                if ($p->post_status == 'publish') {
                    return $p;
                }
            }
            return null;
        }, $selected_items));
        $count = count($selected);
    } else {
        $selected = array();
        $count = count($selected);
    }

    // if less than num items, then calc variable to make up from query
    if ($count < $my_args['num_items']) {

        // make up to selected items
        $remainder = $my_args['num_items'] - $count;

        // add calling post to exluded
        $excluded = array_merge($selected, array($my_args['post']));

        // build standard args
        $args = array(
            'post_type' => $my_args['type'],
            'posts_per_page' => $remainder,
            'order' => $my_args['order'],
            'post__not_in'     => array_map(function ($i) {
                if ($i) {
                    return $i->ID;
                }
                return null;
            }, $excluded)
        );

        // add event start date meta query to args if $orderby == 'start_date'
        if ($my_args['orderby'] == 'end' or $my_args['orderby'] == 'start') {
            $today = time();
            $args['meta_query'] = array(
                array(
                    'key'       => $my_args['orderby'],
                    'compare'   => '>=',
                    'value'     => $today,
                    'type'      => 'numeric'
                )
            );
            $args['meta_key'] = $my_args['orderby'];
            $args['orderby'] = 'meta_value_num';
        }
        // otherwise use orderby as passed
        else {
            $args['orderby'] = $my_args['orderby'];
        }

        // Apply custom args passed in
        $args = wp_parse_args($my_args['args'], $args);

        // get fallbacks using query args
        $fallbacks = Timber::get_posts($args);
    }

    return array_merge($selected, $fallbacks);
}
