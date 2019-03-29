<?php
/**
 * Created by IntelliJ IDEA.
 * User: alexanderschropp
 * Date: 2019-03-20
 * Time: 11:41
 */

class Notification
{

    function sendNotification($title, $message, $id){
        define('API_ACCESS_KEY','AAAAOaKRBDQ:APA91bF1GnOiAqvkgyudU3uW5QyiI_IzW87686Hz9tnBpOTqoEL1rxqsP50lH5GEvvE9vVtRtK-v1AiieFl_Z0oi0wmT4PEdCbXGzthG93lCydgiI7wBdW-I8ra7ejSgSEJmvHlpKbwo');
        $fcmUrl = 'https://fcm.googleapis.com/fcm/send';
        $token='fOOLuAFYdEc:APA91bFxkL4I9r9UgfXzS-wrxqaWZi7TN8DOpHFwdC7t7EhGog1ejuS2UsjBTby1DchT1HAGDIaDNOVbH-Vvb_ZkvvY592OUKPyYVEdq0CPbRh30TlAnLTtr0GT50EDb127fKQus3xQ5';

        $notification = [
            'title' =>$title,
            'body' => $message,
            //'icon' =>'myIcon',
            //'sound' => 'mySound'
        ];
        $extraNotificationData = ["id" => $id];

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

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$fcmUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmNotification));
        curl_exec($ch);
        curl_close($ch);

    }

}