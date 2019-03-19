<?php
define('API_ACCESS_KEY','AAAAOaKRBDQ:APA91bF1GnOiAqvkgyudU3uW5QyiI_IzW87686Hz9tnBpOTqoEL1rxqsP50lH5GEvvE9vVtRtK-v1AiieFl_Z0oi0wmT4PEdCbXGzthG93lCydgiI7wBdW-I8ra7ejSgSEJmvHlpKbwo');
$fcmUrl = 'https://fcm.googleapis.com/fcm/send';
$token='dlXdF6eNolY:APA91bEhhziqbs2xTOgbpFRCScyo5bVUpGr7gdAwKNDruG6-WaUt6kLP0YpEsodjsNN7zRyHeZntvCxDnhgN3KiEVjlgLKGYfk13HZ7Fq3cEdLlUP904QLvJfN9sZA5OoRIw3QIfcX8s';

$notification = [
    'title' =>'title',
    'body' => 'body of message.',
    //'icon' =>'myIcon',
    //'sound' => 'mySound'
];
$extraNotificationData = ["id" => "0"];

$fcmNotification = [
    //'registration_ids' => $tokenList, //multple token array
    'to'        => $token, //single token
    'notification' => $notification,
    'data' => $extraNotificationData
];

$headers = [
    'Authorization: key=' . API_ACCESS_KEY,
    'Content-Type: application/json'
];

//echo json_encode($fcmNotification);
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$fcmUrl);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
$result = curl_exec($ch);
curl_close($ch);


//echo $result;
echo json_encode($fcmNotification);
