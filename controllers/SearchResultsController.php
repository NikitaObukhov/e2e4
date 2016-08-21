<?php

namespace app\controllers;

use yii\rest\ActiveController;

class UsersController extends ActiveController
{
    public $modelClass = 'app\models\SearchResult';

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        return 'JHUI';
    }
}