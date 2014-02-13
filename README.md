Usage:
======

1. Install composer dependancies

2. Set up Twitter auth codes in Settings -> Carbon Twitter Settings

3. Put this in your code:

```php
<?php
$twitter = Carbon\Wp\Twitter::factory();
$tweets = $twitter->getUserTweets(
    array(
        'screen_name' => 'carboncreative',
        'count' => 5
    )
);
```
