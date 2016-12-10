<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * Model for creating and sending Push Notifications
 */
class PushNotification extends \yii\base\Model
{
    const ONE_SIGNAL_APP_ID = "6ca2c182-dda4-4749-aed6-0b4310188986";

    /**
     * Send Push Notifications for New Comments
     */
    public static function notifyNewComments(){
        // Get all comments that have notification not sent
        $newComments = Comment::find()
            ->where(['comment_pushnotif_sent' => 0])
            ->with('user.agents')
            ->orderBy('user_id, comment_datetime')
            ->asArray()
            ->all();

        // Update all comments set push notification as sent
        Comment::updateAll(['comment_pushnotif_sent' => 1], ['comment_pushnotif_sent' => 0]);

        $crawledAccount = null;
        $agentsNotificationFilter = [];
        // Loop through comments
        foreach($newComments as $comment){
            $instagramAccount = $comment['user_id'];
            if($instagramAccount != $crawledAccount){
                $crawledAccount = $instagramAccount;
                $agentsNotificationFilter = [];
                $index = 0;

                // Define the tag filters on notifications
                foreach($comment['user']['agents'] as $agent)
                {
                    // If there's more than one agent then append an OR operator
                    if($index > 0){
                        $agentsNotificationFilter[] = ["operator" => "OR"];
                    }

                    $agentsNotificationFilter[] = [
                        "field" => "tag",
                        "key" => "email",
                        "relation" => "=",
                        "value" => $agent['agent_email']
                    ];

                    $index++;
                }

                echo "\nCrawling Instagram User #$crawledAccount \n\n";
                echo "Agents: \n";
                print_r($agentsNotificationFilter);
            }

            // Send the comment to the assigned agents
            PushNotification::sendCommentNotificationToAgents($comment, $agentsNotificationFilter);

        }

    }

    /**
     * Send the push notification
     * @param  [type] $comment           [description]
     * @param  [type] $agentsTagFilter    [description]
     * @return [type]              [description]
     */
    public static function sendCommentNotificationToAgents($comment, $agentsTagFilter){
        $groupId = "@".$comment['comment_by_username'];

        // Title and Content of Notification
        $headings = ["en" => $groupId];
        $content = ["en" => $comment['comment_text']];

		$fields = [
			'app_id' => static::ONE_SIGNAL_APP_ID,
			'filters' => $agentsTagFilter,
			'data' => [
                "foo" => "bar"
            ],
			'contents' => $content,
            'headings' => $headings,
            "large_icon" => $comment['comment_by_photo'],
            "android_group" => $groupId,
            "collapse_id" => $groupId,
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
