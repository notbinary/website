<?php

require __DIR__ . '/../composer/autoload.php';
require_once __DIR__ . '/event_date_span.php';
require_once __DIR__ . '/event_time_span.php';

// Where Timber looks for twig templates
Timber::$dirname = array(
    'templates',
    'templates/includes',
    'templates/layouts',
);

add_filter('timber_context', 'add_to_context');
add_filter('get_twig', 'add_to_twig');

/**
 * A function that modifies the context returned by Timber::get_context()
 * You can add global state here
 */
function add_to_context($data) {
    global $wp;

    // Global options page
    $o = get_fields('options');
    $data = array_merge_recursive($data, (is_array($o)) ? $o : array());

    $data['home'] = home_url() . '/';
    $data['current_url'] = home_url(add_query_arg(array(),$wp->request)).'/';
    $data['assets'] = '/assets/';
    $data['theme_assets'] = get_template_directory_uri().'/assets/';
    $data['wp_title'] = new Timber\FunctionWrapper('wp_title', array('|', true, 'right'));
    $data['site_name'] = get_bloginfo('name');
    $data['responsive_sizes'] = $GLOBALS['responsive_sizes'];

    // Obfuscated wp version
    // I'm using this to cache bust WP supplied JS like comment-reply.js
    $data['wp_hash'] = substr(md5(get_bloginfo('version')), -6);
    $data['main_menu'] = new TimberMenu('Main navigation');
    $data['footer_menu'] = new TimberMenu('Footer navigation');
    $data['global'] = array(
            'footer' => array(
                'footer_contact_message' => get_field('footer_contact_message', 'option'),
                'small_print' => get_field('small_print', 'option'),
            ),
            'twitter_link' => get_field('twitter_link', 'option'),
            'facebook_link' => get_field('facebook_link', 'option'),
            'instagram_link' => get_field('instagram_link', 'option'),
            'youtube_link' => get_field('youtube_link', 'option'),
            'linkedin_link' => get_field('linkedin_link', 'option'),
            '404' => array(
                'title' => get_field('error_404_title', 'option'),
                'description' => get_field('error_404_description', 'option'),
                'page' => get_field('error_404_page_link', 'option'),
                'button_label' => get_field('error_404_button_label', 'option'),
            ),

    );
    $data['contact_page'] = get_page_by_template('page-templates/contact.php');

    return $data;
}

/**
 * A function that adds to Twig, e.g. custom filters
 */
function add_to_twig($twig) {
    // this is where you can add your own fuctions to twig
    $twig->addFilter('script', new Twig_Filter_Function('js_tag'));
    $twig->addFilter('css', new Twig_Filter_Function('css_tag'));
    $twig->addFilter('pre', new Twig_Filter_Function('pre_tag'));
    $twig->addFilter('content', new Twig_Filter_Function('content_filter'));
    $twig->addFilter('slugify', new Twig_Filter_Function('slugify_filter'));
    $twig->addFilter('asset_url', new Twig_Filter_Function('asset_url_filter'));
    $twig->addFilter('remove_empty', new Twig_Filter_Function('remove_empty_filter'));
    $twig->addFilter('map_key', new Twig_Filter_Function('map_key_filter'));
    $twig->addFilter('sentence', new Twig_Filter_Function('sentence_filter'));
    $twig->addFilter('guid', new Twig_Filter_Function('guid_filter'));
    $twig->addFilter('date_span', new Twig_Filter_Function('date_span_filter'));
    $twig->addFilter('time_span', new Twig_Filter_Function('time_span_filter'));
    $twig->addFilter('mime_type', new Twig_Filter_Function('mime_type_filter'));
    $twig->addFilter('human_filesize', new Twig_Filter_Function('human_filesize_filter'));
    $twig->addFilter('includes', new Twig_Filter_Function('includes_string'));
    $twig->addFilter('content_excerpt', new Twig_Filter_Function('content_excerpt'));
    $twig->addFilter('content_excerpt_imported', new Twig_Filter_Function('content_excerpt_imported'));
    $twig->addFilter('nice_name', new Twig_Filter_Function('nice_name'));
    $twig->addFilter('comments_length', new Twig_Filter_Function('comments_length'));
    $twig->addFilter('wrap_not_first', new Twig_Filter_Function('wrap_not_first'));
    $twig->addFilter('static', new Twig_Filter_Function(
        create_static_filter('patterns/converted-html/assets/', '/assets/')
    ));
    return $twig;
}

/**
 * Turns multiple slashes into a single slash
 * @param  str $string
 * @return str
 */
function dedupe_slashes($string) {
    return preg_replace('~/+~', '/', $string);
}

function js_tag($text) {
    return '<script async src="'.$text.'"></script>';
}

function css_tag($text) {
    return '<link rel="stylesheet" href="'.$text.'" type="text/css" media="all">';
}

function pre_tag($text) {
    return '<pre>'.$text.'</pre>';
}

function slugify_filter ($text) {
    return strtolower(preg_replace('/[^A-Za-z0-9-]+/', '-', $text));
}

function asset_url_filter ($text) {
    return '/static/'.$text;
}

function remove_empty_filter ($arr) {
    return array_filter($arr);
}

function map_key_filter ($arr, $key) {
    return array_map(function ($i) use ($key) {
        // Cast $i to an array if it isnt
        // e.g. an object like WP_Post
        $a = (array) $i;
        return $a[$key];
    }, $arr);
}

function guid_filter($text) {
    return uniqid();
}

function sentence_filter ($arr) {
    $count = count($arr);
    if ($count == 1) {
        return $arr[0];
    } else {
        $last = array_pop($arr);
        return join($arr, ', ') . ' and ' . $last;
    }
}

function date_span_filter ($event, $full_month = true) {
    if ($event) {
        return event_date_span($event->get_field('start_date'), $event->get_field('end_date'), $full_month);
    }
    return null;
}

function time_span_filter ($event) {
    if ($event) {
        return event_time_span($event->get_field('start_time'), $event->get_field('end_time'));
    }
    return null;
}

function srcset_image($image, $target_width, $target_height, $style='none', $crop = 'center') {

    $srcset = array();
    foreach ($GLOBALS['responsive_sizes'] as $size) {
        $width = $size;
        $height = ($target_height / $target_width) * $width;
        $image = new TimberImage($image);
        $srcset[] = filter_resize($image->src(), $width, $height, $style, $crop) . ' ' . $width . 'w';
        if ($size > $target_width * 2) {
            break;
        }
    }

    // Move the biggest to the front to sort out iPad quirk
    if (count($srcset) > 1) {
        array_unshift($srcset, array_pop($srcset));
    }

    return implode(',', $srcset);
}

function mime_type_filter($text) {
    $mimes = require('mimes.php');
    $type = strtolower($text);
    foreach ($mimes as $ext => $mime) {
        if (is_array($mime)) {
            if (in_array($type, $mime)) {
                return '(' . strtoupper($ext) . ')';
            }
        }
        else {
            if ($type == $mime) {
                return '(' . strtoupper($ext) . ')';
            }
        }
    }
    return '';
}

function human_filesize_filter($size) {
    $precision = 0;

    $units = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
    $step = 1024;
    $i = 0;
    while (($size / $step) > 0.9) {
        $size = $size / $step;
        $i++;
    }

    return round($size, $precision).' '.$units[$i];
}

function content_filter ($text) {
    return wpautop(apply_filters('the_content', $text));
}

/**
 * Checks if $text1 includes $text2
 * @param  str $text1
 * @param  str $text2
 * @return boolean
 */
function includes_string ($text1, $text2) {
    return strpos($text1, $text2);
}

function content_excerpt($item, $content = 'simple_content') {
    try {
        if ($item->post_excerpt != '') {
            return $item->post_excerpt;
        }
        else {
            return Timber\TextHelper::trim_words($item->$content, 20, false, '');
        }
    } catch (Exception $e) {
        return '';
    }
}

// Imported articles excerpt filter
function content_excerpt_imported($item, $content = 'imported_content') {
    try {
        if ($item->post_excerpt != '') {
            return $item->post_excerpt;
        }
        else {
            return Timber\TextHelper::trim_words($item->$content, 20, false, '') . '&hellip;';
        }
    } catch (Exception $e) {
        return '';
    }
}

// Make some nice names for search results
function nice_name($item) {
    $type = $item->post_type;
    if ($type == 'articles') {
        return 'Blog';
    }
    elseif ($type == 'page') {
        $page_template = get_page_template_slug($item->id);
        $page_template = str_replace('page-templates/', '', $page_template);
        $page_template = str_replace('.php', '', $page_template);
        $page_template = str_replace('-subpage', '', $page_template);
        $page_template = ucfirst($page_template);
        return $page_template;
    }
    else {
        return get_post_type_object($type)->labels->name;
    }
}

// Recursively get length of a comment thread including all nested children
function comments_length($comments) {
    $length = 0;
    foreach ($comments as $comment) {
        $length = $length + 1;
        $children = $comment->children;
        if (is_array($children) and count($children)) {
           $length += comments_length($children);
        }
    }
    return $length;
}

function wrap_not_first($text, $wrap_el) {
    $split = explode(' ', $text);
    if (count($split) < 2 || !$wrap_el) {
        return $text;
    }
    $first = array_shift($split);
    $rest = '<' . $wrap_el . '>' . implode(' ', $split) . '</' . $wrap_el . '>';

    return $first . $rest;

}
