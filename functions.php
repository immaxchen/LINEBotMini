<?php

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

function build_push_body($to, $text)
{
    $body = array(
        'to' => $to,
        'messages' => array(
            array(
                'type' => 'text',
                'text' => $text
            )
        )
    );
    return $body;
}

function save_to_users($userId)
{
    $filename = 'users';
    $users = array();
    if (file_exists($filename)) {
        $content = file_get_contents($filename);
        $users = json_decode($content, true);
        foreach ($users as $user) {
            if ($user['userId'] == $userId) 
                return;
        }
    }
    $client = new LINEBotMini();
    $profile = $client->getProfile($userId);
    $users[] = array(
        'userId' => $profile['userId'],
        'displayName' => $profile['displayName'],
        'pictureUrl' => $profile['pictureUrl']
    );
    save_to_file($filename, 'w', json_encode($users));
}

function save_to_log($message)
{
    save_to_file('mylog', 'a+', $message."\n\n");
}

function save_to_file($filename, $mode, $content)
{
    $file = fopen($filename, $mode);
    fwrite($file, $content);
    fclose($file);
}

?>
