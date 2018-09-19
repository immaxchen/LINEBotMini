# LINEBotMini
PHP demo for interacting with the LINE Messaging API (using line-bot-sdk-tiny)

It has the abilities to log followers, reply messages and push messages.

# Usage

Replace these two lines in LINEBotMini.php with your own token.

```php
$channelAccessToken = 'Your channelAccessToken Here';
$channelSecret = 'Your channelSecret Here';
```

Write your own reply logic with "build_reply_body" method in functions.php.
