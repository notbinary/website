<?php

function do_seo_to_post($context, $post) {
    $_SERVER['REQUEST_URI_PATH'] = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $segments = explode('/', $_SERVER['REQUEST_URI_PATH']);
    if (isset(get_page_by_path($segments[1])->ID)) {
        $post = new TimberPost(get_page_by_path($segments[1])->ID);
        $yoast_title = get_post_meta($post->ID, '_yoast_wpseo_title', true);
        $title = $yoast_title ? $yoast_title : $post->title . ' - ' . $context['site_name'];
        global $params;
        $term_name = '';
        if (isset($params['cat_or_year'])) {
            $term = new TimberTerm($params['cat_or_year']);
            $term_name = isset($term->name) ? $term->name . ' - ' : '';
            $meta = get_option('wpseo_taxonomy_meta');
            $term_meta = isset($meta[$term->taxonomy][$term->term_id]) ? $meta[$term->taxonomy][$term->term_id] : null;
            if ($term_meta) {
                $seo_desc = isset($term_meta['wpseo_desc']) ? $term_meta['wpseo_desc'] : '';
                if ($seo_desc) {
                    add_filter('wpseo_metadesc', function() use ($seo_desc) {
                        return $seo_desc;
                    }, 10, 1 );
                }
                $seo_title = isset($term_meta['wpseo_title']) ? $term_meta['wpseo_title'] : '';
                $title = $seo_title ? $seo_title : $title;
                $term_name = $seo_title ? '' : $term_name;
                add_filter('wpseo_canonical', function() {
                    return get_home_url() . $_SERVER['REQUEST_URI_PATH'];
                }, 10, 1 );
            }
        }
        $page = '';
        if (isset($params['pg'])) {
            add_filter('wpseo_canonical', function() {
                return get_home_url() . $_SERVER['REQUEST_URI_PATH'];
            }, 10, 1 );
            $page = ' - Page ' . $params['pg'];
        }
        $context['wp_title'] = $term_name . $title . $page;
    }

    return array(
        'context'   => $context,
        'post'      => $post
    );
}

/*
* Get optimum image for Yoast
*/
function add_yoast_image_filter($context, $post) {
    $image = null;
    try {

        if ($post->featured_image) {
            $image = $post->featured_image;
        }
        elseif ($post->hero_image) {
            $image = $post->hero_image;
        }

        if ($image) {
            $post->featured_image = $image;
            $image = TimberImageHelper::resize(new TimberImage($image), 1200, 630);

            add_filter('wpseo_opengraph_image', function($img) use ($image) {
                return $image;
            }, 10, 1);

            add_filter('wpseo_twitter_image', function($img) use ($image) {
                return $image;
            }, 10, 1);
        }

    } catch (Exception $e) {
        error_log('Caught exception: ',  $e->getMessage(), '\n');
    }
}
