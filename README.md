This is a WIP, and could do with a lot of work, namely the URL pointing to Tweetledee, possible composer tweetledee integration, and PSR4 composer integration.

Caveats:
========

Must have directory name of `carbon-twitter` in plugins dir.

Usage:
======

Set up Twitter auth codes in Settings -> Carbon Twitter Settings, then put this in your code:

```php
<?php
$twitter = new Carbon\Twitter();
$tweets = $twitter->get('carboncreative', 5);
```

Shout out to http://github.com/chrissimpkins/tweetledee for the twitter RSS feeds.
