<?php
/**
 * Plugin Name: Asset Registry
 * Plugin URI: https://itscode.truman.edu/its-web-wordpress/asset-registry-plugin
 * Description: Provides a registry of CSS and Javascript assets.
 * Version: 0.0.1
 * Author: Curtis Kelsey
 * Author URI: http://its.truman.edu
 * License: BSD 3
 */

// Initialize the autoloader
require_once "vendor/autoload.php";

// Instantiate any objects that you need
$example = new \AssetRegistryPlugin\AssetRegistry();