<?php

namespace common\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', 'This is the message');
 * \Yii::$app->getSession()->setFlash('success', 'This is the message');
 * \Yii::$app->getSession()->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * \Yii::$app->getSession()->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class Alert extends \yii\base\Widget
{
    /**
     * @var array the alert types for coloring the buttons
     * This array is setup as $key => $value, where:
     * - $key is the name of the session flash variable
     * - $value is the Sweet alert type (i.e. danger, success, info, warning)
     */
    public $alertTypes = [
        'primary'   => 'btn-primary',
        'error'  => 'btn-danger',
        'success' => 'btn-success',
        'info'    => 'btn-info',
        'warning' => 'btn-warning'
    ];

    public $okOptions = [
        'Ok',
        'Great',
        'Awesome',
        'Cool',
    ];

    public function init()
    {
        parent::init();

    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        $output = "";

        $session = \Yii::$app->getSession();
        $flashes = $session->getAllFlashes();

        foreach ($flashes as $type => $data) {
            if (isset($this->alertTypes[$type])) {
                $data = (array) $data;

                foreach ($data as $i => $message) {
                    $title = "";
                    $buttonType = $this->alertTypes[$type];
                    $confirmText = $type=="success"?$this->okOptions[rand(0,3)]:"Ok";

                    //Title of the flash message goes between [brackets]
                    //isolate it from message along with content
                    preg_match_all("/\[[^\]]*\]/", $message, $matches);
                    if(isset($matches[0][0])){
                        $title = str_replace(['[',']'],"",$matches[0][0]);
                        $message = str_replace($title, "", $message);
                        $message = str_replace("[]", "", $message);
                    }

                    $js = "
                    swal({
                        html: true,
                        title: '$title',
                        text: '$message',
                        type: '$type',
                        confirmButtonClass: '$buttonType',
                        confirmButtonText: '$confirmText'
                    });
                    ";

                    $this->view->registerJs($js);
                }

                $session->removeFlash($type);
            }
        }

        return $output;
    }


}
