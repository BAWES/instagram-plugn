<?php

namespace agent\components\AccountManager;

use yii\base\Object;

class AccountManager extends Object
{
    /**
     * Disable the use of AccountManager if the user is not logged in
     */
    public $enabled = false;

    /**
     * @var InstagramUser
     */
    public $accountsManaged;

    public function init()
    {
        parent::init();

        // ... initialization after configuration is applied

    }

    public function getManagedAccounts(){
        //Getting a list of accounts this agent manages
        $cacheDependency = Yii::createObject([
            'class' => 'yii\caching\DbDependency',
            'reusable' => true,
            'sql' => 'SELECT COUNT(*) FROM agent_assignment WHERE agent_id='.Yii::$app->user->identity->agent_id,
        ]);

        $cacheDuration = 60*15; //15 minutes then delete from cache

        $accountsManaged = InstagramUser::getDb()->cache(function($db) {
            return Yii::$app->user->identity->accountsManaged;
        }, $cacheDuration, $cacheDependency);

        return $accountsManaged;
    }
}
