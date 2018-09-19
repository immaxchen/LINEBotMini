# LINEBotMini
PHP demo for interacting with the LINE Messaging API (using line-bot-sdk-tiny)

It has the abilities to log followers, reply messages and push messages.

# Usage

Replace these two lines with your own token in LINEBotMini.php.

```php
$channelAccessToken = 'Your channelAccessToken Here';
$channelSecret = 'Your channelSecret Here';
```

Implement your own reply logic with "build_reply_body" function in functions.php.

```php
function build_reply_body($replyToken, $text)
{
    $body = array(
        'replyToken' => $replyToken,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => "You said: \n".$text
            )
        )
    );
    return $body;
}
```
