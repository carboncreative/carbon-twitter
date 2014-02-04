<?php
/**
 * Plugin Name: Carbon Creative's Simple Twitter reader
 * Plugin URI: http://www.carboncreative.net
 * Description: Simple Twitter reader that supplies the user with an array of tweets for a given user.
 * Author: Carbon Creative
 * Version: 1.0
 * Author URI: http://www.carboncreative.net/
 */

require_once(__DIR__.'/Twitter.php');

add_action('admin_menu', 'Carbon\Twitter::addSettings');
