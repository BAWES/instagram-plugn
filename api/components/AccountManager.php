<?php

namespace api\components;

use Yii;
use yii\base\Object;
use yii\base\InvalidParamException;
use yii\web\NotFoundHttpException;
use api\models\InstagramUser;
use common\models\Comment;

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
    // Accounts this Agent manages
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
        // This component must only be usable if agent is logged in
        if(Yii::$app->user->isGuest){
            die("ILLEGAL USAGE OF ACCOUNT MANAGER, THROW IN JAIL");
        }

        // // Getting a list of accounts this agent manages
        // $cacheDependency = Yii::createObject([
        //     'class' => 'yii\caching\DbDependency',
        //     'reusable' => true,
        //     'sql' => 'SELECT '.Yii::$app->user->identity->agent_id.', COUNT(*) FROM agent_assignment WHERE agent_id='.Yii::$app->user->identity->agent_id,
        //     // we SELECT agent_id as well to make sure every cached sql statement is unique to this agent
        //     // don't want agents viewing the cached content of another agent
        // ]);
        //
        // $cacheDuration = 60*1; //1 minute then delete from cache
        //
        // $this->_managedAccounts = InstagramUser::getDb()->cache(function($db) {
        //     return Yii::$app->user->identity->getAccountsManaged()
        //         ->withoutInactive()->all();
        // }, $cacheDuration, $cacheDependency);
        //

        // No cache
        $this->_managedAccounts = Yii::$app->user->identity->getAccountsManaged()->withoutInactive()->all();

        // Populate accounts with unhandled comment count
        $this->populateAccountsWithUnhandledCountandLastActivity();

        parent::__construct($config);
    }

    /**
     * Populates Agent Instagram Accounts with number of Unhandled Comments within each account
     */
    private function populateAccountsWithUnhandledCountandLastActivity(){
        $accounts = array();
        $accountIds = array();

        // Get list of account ids we wish to check for number of unhandled
        foreach($this->managedAccounts as $account){
            $accountIds[] = $account->user_id;
            $accounts[$account->user_id] = $account;
        }

        // Query for total number of unhandled grouped by account user id
        $unhandledTotalQuery = Comment::find()
                        ->select(['user_id', 'totalUnhandled' => 'COUNT(*)'])
                        ->where(['in', 'user_id', $accountIds])
                        ->andWhere(['comment_handled' => Comment::HANDLED_FALSE])
                        ->groupBy('user_id')
                        ->createCommand()
                        ->queryAll();

        // Assign the output to each cached Instagram_User model
        foreach($unhandledTotalQuery as $unhandledSummary){
            $accounts[$unhandledSummary['user_id']]->unhandledCount = (int) $unhandledSummary['totalUnhandled'];
        }

        // Query for last activity for user id
        $lastAccountActivities = \common\models\Activity::find()
                        ->select(['user_id', 'lastActivity' => 'MAX(activity_datetime)'])
                        ->where(['in', 'user_id', $accountIds])
                        ->groupBy('user_id')
                        ->createCommand()
                        ->queryAll();

        // Assign the output to each cached Instagram_User model
        foreach($lastAccountActivities as $activityDetail){
            $accounts[$activityDetail['user_id']]->lastAgentActivity = Yii::$app->formatter->asRelativeTime($activityDetail['lastActivity']);
        }
    }

    /**
     * Returns the accounts managed by this agent
     *
     * @return \api\models\InstagramUser    Records of accounts managed by this agent
     */
    public function getManagedAccounts(){
        return $this->_managedAccounts;
    }

    /**
     * Gets the account that the agent wants to manage
     *
     * @param integer $accountId id number of the account
     * @return \api\models\InstagramUser  The user account
     * @throws \yii\web\NotFoundHttpException if the account isnt one this agent manages
     */
    public function getManagedAccount($accountId){
        foreach($this->managedAccounts as $account){
            if($account->user_id == $accountId) return $account;
        }

        throw new \yii\web\BadRequestHttpException('You do not manage this account.');
    }

}
