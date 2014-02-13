<?php
/**
 * Plugin Name: Carbon Creative's Simple Twitter reader
 * Plugin URI: https://github.com/carboncreative/carbon-twitter/
 * Description: Simple Twitter reader that supplies the user with an array of tweets for a given user.
 * Author: Carbon Creative
 * Version: 0.0.0
 * Author URI: http://www.carboncreative.net/
 */

require_once(__DIR__.'/vendor/autoload.php');

add_action('admin_menu', 'Carbon\Wp\Twitter::addSettings');
