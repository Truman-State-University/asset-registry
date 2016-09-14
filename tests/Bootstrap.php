<?php
/**
 * @author Curtis Kelsey <kelseyc@truman.edu>
 * @copyright Copyright (c) Truman State University
 */
$_tests_dir = getenv('WP_TESTS_DIR');
if ( !$_tests_dir ) $_tests_dir = '/var/lib/wordpress/trunk';

require_once $_tests_dir . '/tests/phpunit/includes/functions.php';

function _manually_load_plugin() {
    require dirname( __FILE__ ) . '/../asset-registry.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

require $_tests_dir . '/tests/phpunit/includes/bootstrap.php';