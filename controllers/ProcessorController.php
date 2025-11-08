<?php

namespace app\controllers;

use app\services\ProcessorService;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class ProcessorController extends Controller
{
    public $enableCsrfValidation = false;

    public function actionIndex(int $delay = 5): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        @set_time_limit(0);
        (new ProcessorService())->run(max(0, $delay));
        return ['result' => true];
    }
}
