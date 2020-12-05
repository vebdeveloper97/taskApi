<?php


namespace app\modules\api\controllers;


use app\models\News;
use app\modules\api\resources\NewsResources;
use yii\base\Controller;
use yii\helpers\Json;

class NewsController extends BaseApiController
{
    public $modelClass = NewsResources::class;

    /**
     * @var IndexAction
     * */
    public function actionIndex()
    {
        /* Yangiliklarni barchasini ko'rish */
        $model = NewsResources::find()
            ->all();

        if(!empty($model)){
            foreach ($model as $key => $item) {
                $author = $item->getUsers()->select('username')->one();
                if(!empty($author)){
                    $item['author_id'] = $author->username;
                }else{
                    $item['author_id'] = 'Mavjud emas';
                }
            }
            return $model;
        }else{
            return [
                'success' => false,
                'message' => 'Malumot topilmadi!'
            ];
        }
    }

    /**
     * @var CreateAction
     * */
    public function actionCreate()
    {
        $model = new NewsResources();
        $app = \Yii::$app;

        /* Controller va Action olish uchun */
        $controller = self::getController();
        $action = self::getAction();

        if($model->load($app->request->post(),'')){
            $transaction = \Yii::$app->db->beginTransaction();
            $saved = false;
            try{
                $saved = $model->save();
                if($saved){
                    $transaction->commit();
                    $success = 'Id raqami '.$model->id.' bolgan malumot saqlandi';
                    \Yii::info($success,'save');
                    return $model;
                }else{
                    $transaction->rollBack();
                    $error = $controller.' - '.$action.' da malumotlar saqlanmadi';
                    \Yii::info($error,'save');
                    return [
                        'success' => false,
                        'message' => $error,
                        'errors' => $model->errors
                    ];
                }
            }catch (\Exception $e){
                \Yii::info('xatolik mavjud '.$e->getMessage(), 'save');
                $app->response->statusCode = 400;
                return [
                    'success' => false,
                    'message' => 'Xatolik mavjud',
                    'errors' => $e->getMessage()
                ];
            }
        }else{
            $error = $controller.' - '.$action.' malumotlar yuklanmadi!';
            \Yii::info($error, 'save');
            $app->response->statusCode = 400;
            return [
                'success' => false,
                'message' => 'Malumotlar yuklanmadi!'
            ];
        }
    }
    
    /**
     * @var UpdateAction
     * */
    public function actionUpdate()
    {
        $id = \Yii::$app->request->get('id',null);
        $controller = self::getController();
        $action = self::getAction();

        if($id!=null){
            $model = NewsResources::findOne($id);
            if(empty($model)){
                \Yii::error($controller.'/'.$action.' da malumotlar topilmadi!', 'save');
                \Yii::$app->response->statusCode = 404;
                return [
                    'success' => false,
                    'message' => 'Malumot topilmadi'
                ];
            }
            $postdata = \Yii::$app->request->post();
            if(empty($postdata)){
                \Yii::error($controller.'/'.$action.' da malumotlar kelmadi', 'save');
                \Yii::$app->response->statusCode = 404;
                return [
                    'success' => false,
                    'message' => 'Parametrlar mavjud emas!'
                ];
            }
            $model->setAttributes($postdata);
            if($model->save()){
                \Yii::info($controller.'/'.$action.' da malumotlar yangilandi!', 'save');
                return $model;
            }else{
                \Yii::error($controller.'/'.$action.' da malumotlarni yangilab bolmadi!', 'save');
                \Yii::$app->response->statusCode = 401;
                return [
                    'success' => false,
                    'message' => $model->errors
                ];
            }
        }else{
            \Yii::error($controller.'/'.$action.' parametr mavjud emas!', 'save');
            \Yii::$app->response->statusCode = 404;
            return [
                'success' => false,
                'message' => 'Id paramteri mavjud emas'
            ];
        }
    }

    /**
     * @var DeleteAction
     * */
    public function actionDelete($id)
    {
        $controller = self::getController();
        $action = self::getAction();

        if(!empty($id)){
            $model = NewsResources::findOne($id);
            if(!empty($model)){
                if($model->delete()){
                    $success = 'Id nomeri = '.$id.' \n bolgan raqamdagi malumot ochirildi!';
                    \Yii::info($controller.'/'.$action.' da '.$success,'save');
                    return [
                        'success' => true,
                        'message' => $success
                    ];
                }else{
                    $success = 'Id nomeri = '.$id.' bolgan raqamdagi malumot ochirilmadi!';
                    \Yii::error($controller.'/'.$action.' da.'.$success,'save');
                    return [
                        'success' => false,
                        'message' => $success
                    ];
                }
            }else{
                $success = 'Id nomeri = '.$id.' bolgan raqamdagi malumot topilmadi';
                \Yii::error($controller.'/'.$action.' da.'.$success,'save');
                return [
                    'success' => false,
                    'message' => $success
                ];
            }
        }else{
            \Yii::$app->response->statusCode = 404;
            $success = 'Id Parametr mavjud emas!';
            \Yii::error($controller.'/'.$action.' da.'.$success,'save');
            return [
                'success' => false,
                'message' => $success
            ];
        }
    }

}