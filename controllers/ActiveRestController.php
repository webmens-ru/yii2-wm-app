<?php
namespace app\controllers;

use Yii;
use yii\filters\auth\HttpBearerAuth;

class ActiveRestController extends \yii\rest\ActiveController
{

    public function behaviors()
    {

        $behaviors = parent::behaviors();



        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::class,
                'cors' => [
                    // restrict access to
                    'Origin' => ['http://localhost:3000', 'http://localhost:3001', 'http://localhost:3002', 'https://app-new.radamsk.ru'],
                    // Allow only POST and PUT methods
                    'Access-Control-Request-Method' => ['POST', 'PUT', 'PATCH', 'DELETE', 'GET', 'OPTIONS'],
                    // Allow only headers 'X-Wsse'
                    'Access-Control-Request-Headers' => ['X-Wsse'],
                    // Allow credentials (cookies, authorization headers, etc.) to be exposed to the browser
                    'Access-Control-Allow-Credentials' => true,
                    // Allow OPTIONS caching
                    'Access-Control-Max-Age' => 3600,
                    // Allow the X-Pagination-Current-Page header to be exposed to the browser.
                    'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
                    'Access-Control-Allow-Origin' => ['*'],
                    'Access-Control-Allow-Headers' => ['*'],
                ],
            ],
            'contentNegotiator' => [
                'class' => \yii\filters\ContentNegotiator::class,
                'formatParam' => '_format',
                'formats' => [
                    'application/json' => \yii\web\Response::FORMAT_JSON,
                    'xml' => \yii\web\Response::FORMAT_XML,
                ],
            ],
            'authenticator' => [
                'class' => HttpBearerAuth::class,
            ],
        ];
    }

    public function actions()
    {
        $actions = parent::actions();

        // отключить действия "delete" и "create"
        // unset($actions['delete'], $actions['create']);
        // настроить подготовку провайдера данных с помощью метода 
        // "prepareDataProvider()"
        $actions['index']['prepareDataProvider'] = [$this, 'prepareDataProvider'];
        return $actions;
    }

    public function prepareDataProvider()
    {
        $searchModel = new $this->modelClass();
        return $searchModel->search(Yii::$app->request->queryParams, 1);
    }

    public function actionSchema()
    {
        $model = new $this->modelClass();
        return $model->schema;
    }

    public function actionValidation()
    {
        $model = new $this->modelClass();
        return $model->restRules;
    }
}
