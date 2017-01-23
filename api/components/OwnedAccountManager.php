<?php

namespace api\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use api\models\InstagramUser;

/**
 * OwnedAccountManager is a component that holds a list of Accounts this agent owns
 * The purpose of this component is to reduce the stress incurred on the database
 * Example Usage:
 * - Get list of accounts this agent owns
 * - Check if agent is authorised to make actions on behalf of an account
 */
class OwnedAccountManager extends Object
{
    /**
     * Accounts this agent owns
     * @var \common\models\InstagramUser
     */
    private $_ownedAccounts = false;

    /**
     * Sets up the AccountManager component for use to manage accounts
     *
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        // This component must only be usable if agent is logged in
        if(Yii::$app->user->isGuest){
            die("ILLEGAL USAGE OF ACCOUNT OWNERSHIP MANAGER, THROW IN JAIL");
        }

        // Getting a list of accounts this agent manages
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT '.Yii::$app->user->identity->agent_id.', COUNT(*), SUM(user_status) FROM instagram_user WHERE agent_id='.Yii::$app->user->identity->agent_id,
            // we SELECT agent_id as well to make sure every cached sql statement is unique to this agent
            // don't want agents viewing the cached content of another agent
            // SUM of user_status is to bust the cache when status changes
        ]);

        $cacheDuration = 60*15; //15 minutes then delete from cache

        $this->_ownedAccounts = InstagramUser::getDb()->cache(function($db) {
            return Yii::$app->user->identity->instagramUsers;
        }, $cacheDuration, $cacheDependency);

        parent::__construct($config);
    }

    /**
     * Returns the accounts owned by this agent
     *
     * @return \agent\models\InstagramUser    Records of accounts owned by this agent
     */
    public function getOwnedAccounts(){
        return $this->_ownedAccounts;
    }

    /**
     * Gets a single account that the agent owns based on accountId
     *
     * @param integer $accountId id number of the account
     * @return \agent\models\InstagramUser  The user account
     * @throws \yii\web\NotFoundHttpException if the account isnt one this agent owns
     */
    public function getOwnedAccount($accountId){
        foreach($this->_ownedAccounts as $account){
            if($account->user_id == $accountId) return $account;
        }

        throw new \yii\web\BadRequestHttpException('You do not own this account.');
    }

}
