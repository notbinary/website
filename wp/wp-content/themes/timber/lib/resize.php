<?php

use Timber\ImageHelper;
use Timber\URLHelper;

/**
 * A wrapper for Timber resize which optionally passes the image through
 * an image manipulation library to add nice effects.
 *
 * Requires both PYTHON_PATH and JAM_FILTERS_PATH to be set in wp-config.php
 * which should be file paths to the python executable and the sub-folder
 * in the jam-image-filter repository which contains the scripts
 * e.g. 'path/to/python' and 'path/to/jam-image-filter/jam_image_filter'
 *
 * @param  string  $src    The image url passed through by the template
 * @param  integer $w      The target width
 * @param  integer $h      The target height (optional)
 * @param  string  $filter The filter to apply (optional)
 * @param  string  $crop   The crop to set (optional)
 * @return string          The url of the new image
 */
function filter_resize ($src, $w, $h=0, $filter='none', $crop='default') {

    // The filters/filenames we're allowing
    $valid_filters = array(
        'blur',
        'halftone',
        'pxl',
        'stitch'
    );
    // Vars for exec output and status code
    $output = array();
    $return = 0;

    // Standard TimberImage resize call
    $url = Timber\ImageHelper::resize($src, $w, $h, $crop);

    if ($filter === 'none' || !in_array($filter, $valid_filters)) {
        // Exit early because we're using the default image
        return $url;
    }

    // Convert URL to file path
    $path = Timber\URLHelper::url_to_file_system($url);
    // remove the extra /wp/ in path caused by our subfoldering/rewriting
    $path = preg_replace('/wp\/wp/', 'wp', $path);
    // Add filter name to image filename
    $new_path = preg_replace('/(.*)\.(\w+)/i', '${1}-'.$filter.'.${2}', $path);

    // Test if we've already made this image before
    if (!file_exists($new_path)) {
        // Call out to python lib
        $command = escapeshellcmd(join(' ', array(
            PYTHON_PATH,
            JAM_FILTERS_PATH.$filter.'.py',
            $path,
            $new_path,
        )));
        exec($command, $output, $return);
    }

    if ($return !== 0) {
        // the filter failed so fallback to regular image
        return $url;
    }

    // add on the /wp/ to path because of our subfoldering/rewriting
    $new_path = preg_replace('/\/wp\//', '/wp/wp/', $new_path);
    // file path back to URL
    $new_url = Timber\URLHelper::file_system_to_url($new_path);

    return $new_url;
}
