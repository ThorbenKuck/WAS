<!DOCTYPE html>
<?php
ini_set('session.gc_maxlifetime', 36000000);
ini_set('session.gc_divisor', 1);
ob_start();

if (session_status () !== PHP_SESSION_ACTIVE)
    session_start ();
//unset($_SESSION);

?>
<head>
    <script
        src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
</head>
<?php


require_once 'Main.class.php';

Main::loadSocket();

if(! function_exists('Main\main')) {
    die("Der Socket konnte nicht geladen werden! Stellen sie sicher, dass der Socket vorhanden ist!");
}

ob_end_flush();

Main\debug_input(["[Title]: TODO!", "--none--"], false);

Main\debug_input(["(StackStart)Ein Stacktrace!" , "Das ist test 1" , "Das ist test 2" , "(StackEnd)"], false);
Main\debug_input("Hi ... ", false);

Main\set_new_info("haha", "You stink");
Main\set_new_info("haha", "I hate you");

// Bla|30.09.2016|00:00|30.09.2016|08:57|2
?>


<!--
<button
	style="width: 100px; height: 100px; background-color: black; color: white;"
	onclick="notifyMe('Das ist ein Test', 'blabla')">Klick mich!</button>
 -->

<script>
    var first_path;
    var theme_path;


    function test() {
        return "erfolgreich";
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (Notification.permission !== "granted")
            Notification.requestPermission();
    });


    function notifyMe(body, title) {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try another browser.');
            return;
        }

        if (Notification.permission !== "granted")
            Notification.requestPermission();
        else {
            var notification = new Notification(title, {
                icon: theme_path + "resources/kgslogo300_klein.png",
                body: body
            });

            notification.onclick = function () {
                window.focus();
            };

        }

    }

</script>