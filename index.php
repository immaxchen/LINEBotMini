<?php

require_once('./LINEBotMini.php');
require_once('./functions.php');

$inputBody = file_get_contents('php://input');
save_to_log($inputBody);

$client = new LINEBotMini();
foreach ($client->parseEvents() as $event) {
    switch ($event['type']) {
        case 'message':
            $message = $event['message'];
            switch ($message['type']) {
                case 'text':
                    $body = build_reply_body($event['replyToken'], $message['text']);
                    $client->replyMessage($body);
                    break;
                default:
                    save_to_log("Unsupporeted message type: " . $message['type']);
                    break;
            }
            break;
        case 'follow':
            save_to_users($event['source']['userId']);
            break;
        default:
            save_to_log("Unsupporeted event type: " . $event['type']);
            break;
    }
};

?>
