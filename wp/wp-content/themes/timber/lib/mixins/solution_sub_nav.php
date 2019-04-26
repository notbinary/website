<?php

function solution_sub_nav_mixin($context) {

    $tabbed_solution = array(get_page_by_template('page-templates/solution-tabbed.php'));
    $solutions = get_pages_by_template('page-templates/solution.php', 'menu_order', 'ASC');
    $all_solutions = array_merge($tabbed_solution, $solutions);

    $i = 0;
    $total = count($all_solutions);
    $context['sub_nav'] = array(null, null);

    foreach ($all_solutions as $item) {

        if ($item->ID == $context['post']->ID) {
            if ($i > 0) {
                $context['sub_nav'][0] = $all_solutions[$i - 1];
            }
            if ($i < $total - 1) {
                $context['sub_nav'][1] = $all_solutions[$i + 1];
            }
            break;
        }

        $i++;
    }

    return $context;
}
