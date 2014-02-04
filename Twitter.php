<?php
namespace Carbon;

/**
 * Carbon\Twitter - manages twitter feeds on Wordpress installs.
 *
 * Usage:
 * $twitter_username = 'bob';
 * $tweet_quantity = 5;
 * $twitter = new Carbon\Twitter();
 * $tweets = $twitter->get($twitter_username, $tweet_quantity);
 *
 * @package Carbon\Twitter
 * @author Tom Moitié
 * @author Dave Aspinal
 **/
class Twitter {
    /**
     * sets the cache length for finding new tweets in seconds
     **/
    const CACHE_LENGTH = 300;

    /**
     * get - Gets a twitter feed
     *
     * @param $username string twitter username
     * @param $qty int number of tweets to retrieve
     * @return array Tweets
     * @author Dave Aspinal
     * @author Tom Moitié
     **/
    public function get($username, $qty = 1) {
        $rss = $this->getRssFeed($username, $qty);

        if(!is_wp_error($rss))
            $items = $rss->get_items(0, $qty);

        if(!empty($items)) {
            return $this->tweetArray($items);
        }
    }

    /**
     * getRssFeed - returns a object of a twitter feed via RSS
     *
     * @param $username string twitter username
     * @param $qty int number of tweets to retrieve
     * @return SimpleXML RSS feed of tweets
     * @author Tom Moitié
     **/
    private function getRssFeed($username, $qty)
    {
        include_once(ABSPATH . WPINC . '/feed.php');

        $return_cache_amount = function ($seconds) {
            return self::CACHE_LENGTH;
        };
        add_filter('wp_feed_cache_transient_lifetime', $return_cache_amount);
        $rss = fetch_feed(plugins_url().'/carbon-twitter/tweetledee/userrss.php?user='.$username.'&limit='.$qty);
        remove_filter('wp_feed_cache_transient_lifetime', $return_cache_amount);

        return $rss;
    }

    /**
     * tweetArray - turns a SimpleRSS object of tweets into an array of tweets
     *
     * @param array $items SimpleXML RSS Objects of tweets.
     * @return array Tweets
     * @author Tom Moitié
     **/
    private function tweetArray($items)
    {
        $tweets = array();
        foreach($items as $key => $item) {
            // tweet
            $tweet = $item->get_title();

            //find retweets and add RT style to tweet
            preg_match('/^\[((?i)[a-z0-9_]+)\]/', $tweet, $matches);
            $user_tweet = $matches[1];
            $tweet = preg_replace('/^\[' . $user_tweet .'\]/', '', $tweet);
            if ($user_tweet != $username) {
                $tweet = sprintf('RT <a href="https://twitter.com/%s" target="_blank">@%s</a>: %s', $user_tweet, $user_tweet, $tweet);
            }

            $tweet = str_replace('…', '', $tweet);
            $search = array('|(http://[^ ]+)|', '/(^|[^a-z0-9_])@([a-z0-9_]+)/i');
            $replace = array('<a href="$1" target="_blank">$1</a>', '$1<a href="http://twitter.com/$2" target="_blank">@$2</a>');
            $tweets[$key]['tweet'] = preg_replace($search, $replace, $tweet);
            // human time
            $time = strtotime($item->get_date());
            $severtime = current_time('U');
            $tweets[$key]['time'] = human_time_diff($time, $severtime) . ' ago';

            $tweets[$key]['link'] = $item->get_link();
        }
        return $tweets;
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
                include(__DIR__ . '/view/settings.php');
            }
        );
    }
}
