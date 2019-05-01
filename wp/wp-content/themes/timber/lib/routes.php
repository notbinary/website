<?php

// Fixes pagination 404 if WP posts_per_page and search.php posts_per_page are different
Routes::map('/page/:pg', function ($params) {
    return Routes::load('search.php', $params);
});

/**
 * Helper function for loading listings
 * @param  string $post_type the post type slug
 * @return function          a view function that loads the right template
 */
function load_archive($post_type)
{
    return function ($params) use ($post_type) {
        if (array_key_exists('cat_or_year', $params) && !array_key_exists('month', $params)) {
            $args = array(
                'name' => $params['cat_or_year'],
                'post_type' => $post_type,
                'post_status' => 'publish',
                'numberposts' => 1,
                );
            $post = get_posts($args);
            if (!empty($post)) {
                return Routes::load('single-' . $post_type . '.php', $params, $args);
            }
        }
        return Routes::load('page-templates/' . $post_type . '.php', $params);
    };
}

/**
 * Helper function for loading singles
 * @param  string $post_type the post type slug
 * @return function          a view function that loads the right template
 */
function load_single($post_type)
{
    return function ($params) use ($post_type) {
        $query = array(
            'name' => $params['slug'],
            'post_type' => $post_type,
            'post_status' => 'publish',
            'numberposts' => 1,
        );
        if (get_first(Timber::get_posts($query))) {
            return Routes::load('single-' . $post_type . '.php', $params, $query, 200);
        } else {
            return Routes::load('404.php');
        }
    };
}

/**
 * Rewriting and routing for $postType posts
 * so they are always under the $pageTemplate route
 */
function routePostsToPage ($pageTemplate, $postType, $postTemplate) {
    $args = array(
        'post_type' => 'page',
        'fields' => 'ids',
        'nopaging' => true,
        'meta_key' => '_wp_page_template',
        'meta_value' => $pageTemplate,
    );
    $page = get_first(get_posts($args));

    if ($page) {
        $page_path = get_page_uri($page);

        Routes::map("/$page_path/:name", function ($params) use ($postType, $postTemplate) {

            $slug = $params['name'];
            $query = array(
                'name'        => $slug,
                'post_type'   => $postType,
                'post_status' => 'publish',
            );
            if (get_first(Timber::get_posts($query))) {
                Routes::load($postTemplate, $params, $query, 200);
            }
        });

        add_filter('post_type_link', function ($post_link, $post) use ($page_path, $postType) {
            if ($post->post_type != $postType) {
                return $post_link;
            }
            return str_replace($postType.'-slug', $page_path, $post_link);
        }, 1, 2);
    }

}

/**
 * Jobs urls
 */
$jobs_archive = load_archive('jobs');
Routes::map('/jobs/page/:pg', $jobs_archive);
Routes::map('/jobs/:cat_or_year', $jobs_archive);
Routes::map('/jobs/:cat_or_year/page/:pg', $jobs_archive);
Routes::map('/jobs/:cat_or_year/:month', $jobs_archive);
Routes::map('/jobs/:cat_or_year/:month/page/:pg', $jobs_archive);

/**
 * Blog urls
 */
$blog_archive = load_archive('blog');
Routes::map('/blog/page/:pg', $blog_archive);
Routes::map('/blog/:cat_or_year', $blog_archive);
Routes::map('/blog/:cat_or_year/page/:pg', $blog_archive);
Routes::map('/blog/:cat_or_year/:month', $blog_archive);
Routes::map('/blog/:cat_or_year/:month/page/:pg', $blog_archive);

