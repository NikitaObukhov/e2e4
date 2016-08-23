<?php

namespace app\controllers;

use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use JMS\Serializer\SerializationContext;
use yii\rest\ActiveController;
use yii\rest\Controller;
use yii\web\Request;
use yii\web\Response;

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
                'modelClass' => 'app\models\entity\SearchResult',
            ],
            'requests' => [
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

    public function actionView($id)
    {
        var_dump($id);die;
    }

    protected function getSerializer(Request $request)
    {
        $serializer = \Yii::$container->get('e2e4.serializer');
        $context = new SerializationContext();
        $groups = explode(',', $request->get('expand', ''));
        $context->setGroups(array_merge($groups, [GroupsExclusionStrategy::DEFAULT_GROUP]));
        $serializer->setSerializationContext($context);
        return $serializer;
    }

    protected function serializeData($data)
    {
        $serializer = $this->getSerializer(\Yii::$app->request);
        $json = $serializer->serialize($data, 'json');
        return $json;
    }

    protected function prepareResponse(Response $response)
    {
        $response->getHeaders()->set('Content-Type', 'application/json; charset=UTF-8');
        $response->format = Response::FORMAT_RAW;
    }

    public function afterAction($action, $result)
    {
        $this->prepareResponse(\Yii::$app->response);
        return parent::afterAction($action, $result);
    }
}