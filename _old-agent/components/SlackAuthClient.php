<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace agent\components;

use yii\authclient\OAuth2;

/**
 * SlackAuthClient allows authentication via Slack OAuth.
 *
 * Example application configuration:
 *
 * ~~~
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => 'yii\authclient\Collection',
 *         'clients' => [
 *             'slack' => [
 *                 'class' => 'agent\components\SlackAuthClient',
 *                 'clientId' => 'client_id',
 *                 'clientSecret' => 'client_secret',
 *             ],
 *         ],
 *     ]
 *     ...
 * ]
 * ~~~
 *
 * @see https://api.slack.com/docs/sign-in-with-slack
 *
 * @author Khalid Al-Mutawa <khalid@bawes.net>
 */
class SlackAuthClient extends OAuth2
{
    /**
     * @inheritdoc
     */
    public $authUrl = 'https://slack.com/oauth/authorize';
    /**
     * @inheritdoc
     */
    public $tokenUrl = 'https://slack.com/api/oauth.access';
    /**
     * @inheritdoc
     */
    public $apiBaseUrl = 'https://slack.com/api';


    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->scope === null) {
            $this->scope = implode(',', [
                'identity.basic',
                'identity.email'
            ]);
        }
    }

    /**
     * @inheritdoc
     */
    protected function initUserAttributes()
    {
        return $this->accessToken->params['user'];
    }

    /**
     * @inheritdoc
     */
    protected function defaultName()
    {
        return 'slack';
    }

    /**
     * @inheritdoc
     */
    protected function defaultTitle()
    {
        return 'Slack';
    }

    /**
     * @override Oath2::fetchAccessToken()
     * Did the override to fix bug in return url having additional params that aren't needed
     */
    public function fetchAccessToken($authCode, array $params = [])
    {
        $defaultParams = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'code' => $authCode,
            'grant_type' => 'authorization_code',
            //Removing ?state= from the return url via "strok" function as it makes Slack bug out
            'redirect_uri' => strtok($this->getReturnUrl(), '?'),
        ];

        $response = $this->sendRequest('POST', $this->tokenUrl, array_merge($defaultParams, $params));

        $token = $this->createToken(['params' => $response]);
        $this->setAccessToken($token);

        return $token;
    }

}
