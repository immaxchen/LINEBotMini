<?php

require_once('./LINEBotMini.php');
require_once('./functions.php');

if ($_POST) {
    if (array_key_exists('userId', $_POST) && array_key_exists('message', $_POST)) {
        $body = build_push_body($_POST['userId'], $_POST['message']);
        save_to_log(json_encode($body));
        $client = new LINEBotMini();
        $client->pushMessage($body);
    }
}

$content = file_get_contents('users');
$users = json_decode($content, true);

?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <title>Push LINE Message</title>
  </head>
  <body>
    <div class="container">
      <br>
      <form method="post">
        <div class="form-group">
          <label for="userId">LINE User</label>
          <select class="form-control" id="userId" name="userId">
            <?php 
            foreach ($users as $user) {
                echo '<option value="'.$user['userId'].'">'.$user['displayName'].'</option>';
            }
            ?>
          </select>
        </div>

        <div class="form-group">
          <label for="message">LINE Message</label>
          <textarea class="form-control" rows="3" id="message" name="message"></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Send</button>
      </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
  </body>
</html>
