<?php
namespace Carbon\Wp;

use Tmoitie\Twitter\TwitterClient;

/**
 * Carbon\Twitter - manages twitter feeds on Wordpress installs.
 *
 * @package Carbon\Twitter
 * @author Tom Moitié
 **/
class Twitter
{
    /**
     * get - Gets a twitter feed
     *
     * @param $username string twitter username
     * @param $qty int number of tweets to retrieve
     * @return array Tweets
     * @author Tom Moitié
     **/
    public function get($username, $qty = 1)
    {
        $client = self::factory();
        return $client->getUserTweets(
            array(
                'screen_name' => $username,
                'count' => $qty
            )
        );
    }

    public static function factory()
    {
        $keys = array(
            'consumer_key' => get_option('carbon_twitter_key'),
            'consumer_secret' => get_option('carbon_twitter_secret'),
            'token' => get_option('carbon_twitter_token'),
            'token_secret' => get_option('carbon_twitter_token_secret'),
        );

        $consumerSet = isset($keys['consumer_secret']) && isset($keys['consumer_secret']);
        $tokenSet = isset($keys['consumer_secret']) && isset($keys['consumer_secret']);

        if ($consumerSet && $tokenSet) {
            return TwitterClient::factory($keys);
        }
    }


    /**
     * addSettings - Adds a settings page for AWS set up.
     *
     * @return void
     **/
    public static function addSettings()
    {
        add_submenu_page(
            'options-general.php',
            'Carbon Twitter Settings',
            'Carbon Twitter Settings',
            'manage_options',
            'carbon-twitter-settings',
            function() {
                $out = array('success' => false);

                if (!current_user_can('manage_options')) {
                    throw new Exception('Access denied');
                }
                if (!empty($_REQUEST['submit'])) {
                    $out['nonce_incorrect'] = true;
                    if(check_admin_referer('carbon_twitter_settings')) {
                        update_option('carbon_twitter_key', $_REQUEST['carbon_twitter_key']);
                        update_option('carbon_twitter_secret', $_REQUEST['carbon_twitter_secret']);
                        update_option('carbon_twitter_token', $_REQUEST['carbon_twitter_token']);
                        update_option('carbon_twitter_token_secret', $_REQUEST['carbon_twitter_token_secret']);
                        $out['nonce_incorrect'] = false;
                        $out['success'] = true;
                    }
                }

                $out['nonce'] = wp_nonce_field('carbon_twitter_settings', '_wpnonce', true, false);
                $out['carbon_twitter_key'] = get_option('carbon_twitter_key');
                $out['carbon_twitter_secret'] = get_option('carbon_twitter_secret');
                $out['carbon_twitter_token'] = get_option('carbon_twitter_token');
                $out['carbon_twitter_token_secret'] = get_option('carbon_twitter_token_secret');
                include(__DIR__ . '/../view/settings.php');
            }
        );
    }
}
