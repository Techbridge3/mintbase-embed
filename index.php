<?php
/**
 * Plugin Name: TB Mintbase plugin
 * Description: plugin for buying coupons for nLEARNs
 * Version: 0.0.1
 * Author: Techbridge
 * Author URI: https://techbridge.ca/
 */

use TBMintbase\Model\Constructor\Constructor;

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
} else {
    _e('Install composer for current work');
    exit;
}

$constructor = Constructor::getInstance();
