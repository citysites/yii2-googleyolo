<?php

namespace citysites\googleyolo;

use yii\base\InvalidConfigException;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\View;

/**
 * Class GoogleYoloWidget
 * @package citysites\googleyolo
 *
 * @see https://developers.google.com/identity/one-tap/web/overview
 */
class GoogleYoloWidget extends Widget
{
    const CONTEXT_SIGNIN = 'signIn';
    const CONTEXT_SIGNUP = 'signUp';
    const CONTEXT_CONTINUE = 'continue';

    const VIEW_TYPE_INLINE = 'inline';
    const VIEW_TYPE_POPUP = 'popup';

    public $clientId;
    public $retrieveConfig = [];
    public $hintConfig = [];
    public $allowedRetrieve = true;

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if (empty($this->clientId)) {
            throw new InvalidConfigException('The Client Id is not defined');
        }
        if ($this->allowedRetrieve) {
            $this->initRetrieveConfig();
        }
        $this->initHintConfig();
        parent::init();
    }

    public function run()
    {
        GoogleYoloAsset::register($this->getView());
        $this->renderWidget();
    }

    private function renderWidget()
    {
        $config = Json::encode([
            'allowedRetrieve' => $this->allowedRetrieve,
            'retrieveConfig' => $this->retrieveConfig,
            'hintConfig' => $this->hintConfig,
        ]);
        $this->getView()->registerJs("new GoogleYolo($config);", View::POS_BEGIN);
    }

    /**
     * @throws InvalidConfigException
     */
    private function initRetrieveConfig()
    {
        $this->retrieveConfig = ArrayHelper::merge([
            'supportedAuthMethods' => [
                'https://accounts.google.com',
                'googleyolo://id-and-password'
            ],
            'supportedIdTokenProviders' => [
                [
                    'uri' => 'https://accounts.google.com',
                    'clientId' => $this->clientId
                ]
            ],
            'context' => self::CONTEXT_SIGNIN
        ], $this->retrieveConfig);
        $context = $this->retrieveConfig['context'];
        if (!is_string($context) || !in_array($context, [self::CONTEXT_SIGNIN, self::CONTEXT_CONTINUE])) {
            throw new InvalidConfigException('For retrieve context available only "signIn", "continue" strings');
        }
    }

    /**
     * @throws InvalidConfigException
     */
    private function initHintConfig()
    {
        $this->hintConfig = ArrayHelper::merge([
            'supportedAuthMethods' => ['https://accounts.google.com'],
            'supportedIdTokenProviders' => [
                [
                    'uri' => 'https://accounts.google.com',
                    'clientId' => $this->clientId
                ]
            ],
            'context' => self::CONTEXT_SIGNIN
        ], $this->hintConfig);
        $hintContext = $this->hintConfig['context'];
        if (!is_string($hintContext) ||
            !in_array($hintContext, [self::CONTEXT_SIGNIN, self::CONTEXT_CONTINUE, self::CONTEXT_SIGNUP])) {
            throw new InvalidConfigException('For hint context available only "signIn", "signUp", "continue" strings');
        }
    }
}