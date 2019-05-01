<?php

/**
 * Takes an args array (same as get_posts) and returns a
 * function that is a context mixin. The mixin does:
 *  - Add array of posts of supplied post_type as a key of $post_type
 *    to context
 *  - Add pagination to the context
 *  - Add post type categories to context
 *  - Filter and order posts based on query you pass in
 *
 * @param  array  $my_args Same as supplied to get_posts
 * @return function        function($context : array[]) -> array[]
 */
function listing_mixin($my_args=array()) {
    $defaults = array(
        'post_type'      => 'news',
        'posts_per_page' => 9,
        'orderby'        => 'date',
        'order'          => 'DESC',
        'category'       => 'category',
        'archive'        => true,
        'post_status'    => 'publish',
        'args'           => array(),
        'tax_query'      => array(),
    );
    $my_args = wp_parse_args($my_args, $defaults);

    // echo '<pre>'; print_r($my_args); echo '</pre>';

    return function($context) use ($my_args) {
        global $paged;
        global $params;
        global $wpdb;
        $params = (isset($params)) ? $params : array();
        if (!isset($paged) || !$paged) {
            $paged = 1;
        }
        if (isset($params['pg'])) {
            $paged = $params['pg'];
        }
        $args = array(
            'post_type'      => $my_args['post_type'],
            'posts_per_page' => $my_args['posts_per_page'],
            'orderby'        => $my_args['orderby'],
            'order'          => $my_args['order'],
            'post_status'    => $my_args['post_status'],
            'tax_query'      => $my_args['tax_query'],
            'paged'          => $paged
        );
        $args = array_merge($args, $my_args['args']);
        if (array_key_exists('cat_or_year', $params) || array_key_exists('month', $params)) {
            if (array_key_exists('cat_or_year', $params) && !preg_match('/\d\d\d\d/', $params['cat_or_year'])) {
                $args['tax_query'] = array(
                    array(
                        'taxonomy' => $my_args['category'],
                        'field' => 'slug',
                        'terms' => $params['cat_or_year']
                    )
                );
            } else {
                $args['date_query'] = array(
                    'year'  => (array_key_exists('cat_or_year', $params)) ? $params['cat_or_year'] : null,
                    'month' => (array_key_exists('month', $params)) ? $params['month'] : null,
                );
            }
        }

        query_posts($args);
        if ($my_args['archive']) {
            $context['archive'] = $wpdb->get_results("
                SELECT DISTINCT
                    MONTH( $wpdb->posts.post_date ) AS month,
                    YEAR( $wpdb->posts.post_date ) AS year,
                    DATE( $wpdb->posts.post_date ) AS date,
                    MONTHNAME( $wpdb->posts.post_date ) AS monthname
                FROM $wpdb->posts
                WHERE $wpdb->posts.post_type = '$my_args[post_type]'
                AND $wpdb->posts.post_status = 'publish'
                AND $wpdb->posts.post_date <= now()
                GROUP BY month, year ORDER BY post_date DESC
            ");
        }
        $context['categories'] =  get_terms(array(
            'post_type' => array($my_args['post_type']),
            'taxonomy' => $my_args['category'],
            'hide_empty' => true,
        ));

        $context[str_replace('-', '_', $my_args['post_type'])] = Timber::get_posts($args);
        $context['pagination'] = Timber::get_pagination();
        $context['pagination']['page'] = $paged;
        $context['pagination']['query'] = json_encode($args);

        wp_reset_query();
        return $context;
    };
}
