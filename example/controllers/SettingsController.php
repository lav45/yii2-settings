<?php

namespace example\controllers;

use Yii;
use yii\web\Controller;
use example\models\SettingsForm;

class SettingsController extends Controller
{
    public function actionIndex()
    {
        $model = new SettingsForm();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Settings successfully updated!');
            return $this->refresh();
        }

        return $this->render('settings', [
            'model' => $model,
        ]);
    }
}