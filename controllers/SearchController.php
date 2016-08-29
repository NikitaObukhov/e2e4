<?php

namespace app\controllers;

use app\models\bridge\RequestAwareSerializationContext;
use app\models\bridge\RequestedFieldsAwareSerializationContext;
use app\models\entity\SearchRequest;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use JMS\Serializer\Exclusion\GroupsExclusionStrategy;
use yii\data\ActiveDataProvider;
use yii\rest\Controller;
use yii\rest\IndexAction;
use yii\web\NotFoundHttpException;
use yii\web\Request;
use yii\web\Response;

class SearchController extends Controller
{

    public function behaviors()
    {
        $behaviors = parent::behaviors() + [
            'cors' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Allow-Credentials' => null,
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Expose-Headers' => ['X-Pagination-Total-Count'],
                ],
            ]
        ];
        return $behaviors;
    }

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
            'view' => [
                'class' => 'yii\rest\ViewAction',
                'modelClass' => 'app\models\entity\SearchRequest',
            ],
            'results' => [
                'class' => 'yii\rest\IndexAction',
                'modelClass' => 'app\models\entity\SearchResult',
                'prepareDataProvider' => function(IndexAction $action) {
                    $request = \Yii::$app->request;
                    if (null === $searchRequest = SearchRequest::findOne($request->get('id'))) {
                        throw new NotFoundHttpException;
                    }
                    return new ActiveDataProvider([
                        'query' => $searchRequest->getSearchResults(),
                    ]);
                },
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        return [
            'do' => ['POST', 'OPTIONS'],
            'history' => ['GET', 'HEAD'],

        ];
    }

    public function actionDo()
    {
        if (\Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            \Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST');
            return;
        }

        $form = \Yii::$container->get('e2e4.form.search_request');

        $result = $form->load(\Yii::$app->request->post(), '');
        if ($form->validate()) {
            $response = \Yii::$app->getResponse();
            try {
                $searchRequest = $form->submit();
                /* @var $searchRequest SearchRequest */
            }
            catch (RequestException $e) {
                $response->setStatusCode(400);
                $context = $e->getHandlerContext();
                return ['errors' => [$context['error']]];
            }

            return $searchRequest;
        }
        else {
            $response = \Yii::$app->getResponse();
            $response->setStatusCode(400);
            return $this->serializeData(['errors' => $form->getErrors()]);
        }
    }


    protected function getSerializer(Request $request)
    {
        $serializer = \Yii::$container->get('e2e4.serializer');
        $context = new RequestedFieldsAwareSerializationContext();
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