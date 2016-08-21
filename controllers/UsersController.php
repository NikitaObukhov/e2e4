<?php

namespace app\controllers;

use app\models\forms\ParseUrlForm;
use app\models\ImagePageContent;
use app\models\parser\ImageParser;
use app\models\parser\LinkParser;
use app\models\parser\WebsitePageParser;
use Goutte\Client;
use yii\rest\ActiveController;

class UsersController extends ActiveController
{
    public $modelClass = 'app\models\User';

    public function actions()
    {
        return [];
    }

    public function actionIndex()
    {
        $form = \Yii::$container->get('e2e4.form.parse_url');

        $result = $form->load(\Yii::$app->request->get(), '');
        $validate = $form->validate();
        var_dump($result);
        var_dump($validate);
        var_dump($form->getErrors());
        $result = $form->submit();

        die;
        $client = new Client();
        $response = $client->request('GET', 'https://novosibirsk.e2e4online.ru/');
        $client->getRequest();
        $parser = new WebsitePageParser([
            new LinkParser(),
            new ImageParser(),
        ]);
        foreach($parser->doParse($response) as $link) {
            var_dump($link);
        }
        die;
        $links = $response->filterXpath('//a')->links();
        foreach($links as $link) {
            var_dump($link->getUri());
            var_dump($link);die;
        }
        $images = $response->filterXpath('//img')->images();
        $xpath = sprintf('//*[contains(text(),\'%s\')]', 'оборудование');
        $text = $response->filterXPath($xpath);
        foreach($text as $node) {
            var_dump($node->textContent);
        }
        die;
        foreach($images as $image) {
            var_dump($image->getUri());
            var_dump($image);die;
        }
        $parser = new ImageParser();
        $html = file_get_contents();
        $document = new \DOMDocument();
        @$document->loadHTML($html);
        $parser = new ImageParser();
        $parser->doParse($document);

    }
}