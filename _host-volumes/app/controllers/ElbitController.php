<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;

class ElbitController extends Controller 
{
    public function __construct($id, $module, $config = []) {
        $request = Yii::$app->request;
        $flashCache = $request->get('flush-cache');

        if (
            $flashCache === 'true' 
            || (array_key_exists('flushCache', \Yii::$app->params) 
            && \Yii::$app->params['flushCache'])
        ) Yii::$app->cache->flush();
        parent::__construct($id, $module, $config = []);
    }
}
