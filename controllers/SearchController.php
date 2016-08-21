<?php

namespace app\controllers;

use yii\rest\ActiveController;
use yii\rest\Controller;

class SearchController extends Controller
{

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'history' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => 'app\models\entity\SearchRequest',
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'do' => ['POST', 'GET'],
            'history' => ['GET', 'HEAD'],
        ];
    }

    public function actionDo()
    {
        $form = \Yii::$container->get('e2e4.form.parse_url');

        $result = $form->load(\Yii::$app->request->get(), '');
        if ($form->validate()) {
            $form->submit();
        }
        else {
            var_dump($form->getErrors());
        }
    }
}