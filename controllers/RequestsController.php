<?php

namespace app\controllers;

use app\models\Request;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class RequestsController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $payload = Yii::$app->request->getBodyParams();
        $model = new Request();
        $model->load($payload, '');

        if ($model->validate() && $model->save(false)) {
            Yii::$app->response->statusCode = 201;
            return ['result' => true, 'id' => (int)$model->id];
        }

        Yii::$app->response->statusCode = 400;
        return ['result' => false];
    }
}
