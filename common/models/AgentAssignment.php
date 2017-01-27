<?php

namespace common\models;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "agent_assignment".
 *
 * @property integer $assignment_id
 * @property integer $user_id
 * @property integer $agent_id
 * @property string $assignment_agent_email
 * @property string $assignment_created_at
 * @property string $assignment_updated_at
 *
 * @property Agent $agent
 * @property InstagramUser $user
 */
class AgentAssignment extends \yii\db\ActiveRecord
{
    public $instagramAccountModel;
    public $sendEmail = true;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'agent_assignment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['assignment_agent_email'], 'required'],
            [['assignment_agent_email'], 'string', 'max' => 255],
            [['assignment_agent_email'], 'email'],

            //Only allow one record of each email per account
            ['assignment_agent_email', 'unique', 'filter' => function($query){
                $query->andWhere(['user_id' => $this->user_id]);
            }, 'message' => 'This person has already been added as an agent.'],
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'assignment_created_at',
                'updatedAtAttribute' => 'assignment_updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'assignment_id' => 'Assignment ID',
            'user_id' => 'User ID',
            'agent_id' => 'Agent ID',
            'assignment_agent_email' => 'Email',
            'assignment_created_at' => 'Date Assigned',
            'assignment_updated_at' => 'Assignment Updated At',
        ];
    }

    public function beforeSave($insert) {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                //If agent with this email exists, set agent_id to his id
                $agent = Agent::findOne(['agent_email'=>$this->assignment_agent_email]);
                if($agent){
                    $this->agent_id = $agent->agent_id;
                }

                if($this->sendEmail){
                    //Send Email to Agent notifying him that he got assigned
                    Yii::$app->mailer->compose([
                        'html' => 'agency/agentInvite',
                            ], [
                        'accountFullName' => $this->instagramAccountModel->user_fullname,
                        'accountName' => $this->instagramAccountModel->user_name,
                        'accountPhoto' => $this->instagramAccountModel->user_profile_pic,
                    ])
                    ->setFrom([\Yii::$app->params['supportEmail'] => \Yii::$app->name ])
                    ->setTo($this->assignment_agent_email)
                    ->setSubject("You've been invited to manage @".$this->instagramAccountModel->user_name)
                    ->send();
                }

                //Send Slack notification of agent assignment
                Yii::info("[Agent Invite from @".$this->instagramAccountModel->user_name."] Sent to ".$this->assignment_agent_email, __METHOD__);
            }

            return true;
        }
    }

    /**
     * After the assignment has been saved
     * @param type $insert
     * @param type $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);


    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAgent()
    {
        return $this->hasOne(Agent::className(), ['agent_id' => 'agent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(InstagramUser::className(), ['user_id' => 'user_id']);
    }
}
