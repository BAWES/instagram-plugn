<?php

namespace agent\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidParamException;
use common\models\InstagramUser;

/**
 * AccountManager is a component that holds a list of Accounts this user manages
 * The purpose of this component is to reduce the stress incurred on the database via Agents
 * Example Usage:
 * - Get list of accounts this agent manages
 * - Check if agent is authorised to make actions on behalf of an account maybe?
 */
class AccountManager extends Object
{
    //Accounts this Agent manages
    /**
     * @var \common\models\InstagramUser
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
        //Getting a list of accounts this agent manages
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM agent_assignment WHERE agent_id='.Yii::$app->user->identity->agent_id,
        ]);

        $cacheDuration = 60*15; //15 minutes then delete from cache

        $this->_managedAccounts = InstagramUser::getDb()->cache(function($db) {
            return Yii::$app->user->identity->accountsManaged;
        }, $cacheDuration, $cacheDependency);

        parent::__construct($config);
    }

    public function init()
    {
        parent::init();

        // ... initialization after configuration is applied

    }

    /**
     * Returns the accounts managed by this agent
     *
     * @return \common\models\InstagramUser    Records of accounts managed by this agent
     */
    public function getManagedAccounts(){
        return $this->_managedAccounts;
    }
}
