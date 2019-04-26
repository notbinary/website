<?php
/**
 * Adds a random testimonial to the context at ['testimonial']
 * @param  obj $context
 * @return obj $context
 */
function random_testimonial_mixin ($context, $types = null) {

    $types = $types ? $types : $context['post']->testimonial_types;

    $args = array(
        'post_type' => 'testimonials',
        'orderby' => 'rand',
        'posts_per_page' => 1,
        'post_status' => 'publish',
    );

    if (is_array($types) && count($types)) {
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'testimonial-type',
                'field' => 'id',
                'terms' => $types
            )
        );
    }

    $testimonials = Timber::get_posts($args);

    if (!count($testimonials)) {
        $context['testimonial'] = false;
    }
    else {
        $context['testimonial'] = $testimonials[0];
        $context['testimonial']->name = $context['testimonial']->get_field('name');
    }

    return $context;
}
