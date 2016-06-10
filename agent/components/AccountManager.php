<?php

namespace agent\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use agent\models\InstagramUser;

/**
 * AccountManager is a component that holds a list of Accounts this agent manages
 * The purpose of this component is to reduce the stress incurred on the database via Agents
 * Example Usage:
 * - Get list of accounts this agent manages
 * - Check if agent is authorised to make actions on behalf of an account maybe?
 *
 */
class AccountManager extends Object
{
    //Accounts this Agent manages
    /**
     * @var \agent\models\InstagramUser
     */
    private $_managedAccounts = false;

    /**
     * Sets up the AccountManager component for use to manage accounts
     *
     * @param  array                           $config name-value pairs that will be used to initialize the object properties
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
    public function __construct($config = [])
    {
        //This component must only be usable if agent is logged in
        if(Yii::$app->user->isGuest){
            die("ILLEGAL USAGE OF ACCOUNT MANAGER, THROW IN JAIL");
        }

        //Getting a list of accounts this agent manages
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT '.Yii::$app->user->identity->agent_id.', COUNT(*) FROM agent_assignment WHERE agent_id='.Yii::$app->user->identity->agent_id,
            // we SELECT agent_id as well to make sure every cached sql statement is unique to this agent
            // don't want agents viewing the cached content of another agent
        ]);

        $cacheDuration = 60*15; //15 minutes then delete from cache

        $this->_managedAccounts = InstagramUser::getDb()->cache(function($db) {
            return Yii::$app->user->identity->accountsManaged;
        }, $cacheDuration, $cacheDependency);

        parent::__construct($config);
    }

    /**
     * Returns the accounts managed by this agent
     *
     * @return \agent\models\InstagramUser    Records of accounts managed by this agent
     */
    public function getManagedAccounts(){
        return $this->_managedAccounts;
    }

    /**
     * Gets the account that the agent wants to manage
     *
     * @param integer $accountId id number of the account
     * @return \agent\models\InstagramUser  The user account
     * @throws \yii\web\NotFoundHttpException if the account isnt one this agent manages
     */
    public function getManagedAccount($accountId){
        foreach($this->managedAccounts as $account){
            if($account->user_id == $accountId) return $account;
        }

        throw new NotFoundHttpException('You do not manage this account.');
    }

}
