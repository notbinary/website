<?php

$context = get_context();

error_log(json_encode($context));

Timber::render('page/standard-page.html', $context);