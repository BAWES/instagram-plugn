<?php

namespace common\models;

use Yii;

/**
 * Model for creating and sending Push Notifications
 */
class PushNotification extends \yii\base\Model
{

    /**
     * Send the push notification
     * @return [type] [description]
     */
    public function send(){
        $groupId = "@mai_almutairi";

        // Title and Content of Notification
        $headings = ["en" => $groupId];
        $content = ["en" => "Hello brother"];

		$fields = [
			'app_id' => "6ca2c182-dda4-4749-aed6-0b4310188986",
			'filters' => [
                ["field" => "tag", "key" => "agentId", "relation" => "=", "value" => "1"],
                ["operator" => "OR"],
                // ["field" => "tag", "key" => "agentId", "relation" => "=", "value" => "1"],
            ],
			'data' => [
                "foo" => "bar"
            ],
			'contents' => $content,
            'headings' => $headings,
            "large_icon" => "https://igcdn-photos-b-a.akamaihd.net/hphotos-ak-xta1/t51.2885-19/11351974_903363216397161_1294946634_a.jpg",
            "android_group" => $groupId,
            "android_group_message" => ["en" => "$[notif_count] new comments"]
		];

		$fields = json_encode($fields);
    	print("\nJSON sent:\n");
    	print($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
												   'Authorization: Basic ZGIzNWMwNDgtNDZlNy00MTUzLWFkNWYtNmMwOWM4Y2M4ZGVj'));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		$response = curl_exec($ch);
		curl_close($ch);

		//return $response

        // $return["allresponses"] = $response;
    	// $return = json_encode( $return);

    	print("\n\nJSON received:\n");
    	print_r($response);
    	print("\n");
    }
}