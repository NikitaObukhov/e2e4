<?php

namespace app\controllers;

use yii\web\Controller;

class FooController extends Controller
{

    public function actionIndex()
    {
        return 'Huindex';
    }

    public function actionView($id)
    {
        var_dump($id);
        die;
    }
}