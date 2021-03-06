<?php
namespace app\controllers;

use Yii;
use app\models\Country;
use app\models\CountrySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\jldtx\Category;

/**
 * CountryController implements the CRUD actions for Country model.
 */
class CountryController extends Controller
{

    /**
     *
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => [
                        'POST'
                    ]
                ]
            ]
        ];
    }

    /**
     * Lists all Country models.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CountrySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * Displays a single Country model.
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Country model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     *
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Country();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        return $this->render('create', [
            'model' => $model
        ]);
    }

    /**
     * Updates an existing Country model.
     * If update is successful, the browser will be redirected to the 'view' page.
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect([
                'view',
                'id' => $model->id
            ]);
        }
        
        return $this->render('update', [
            'model' => $model
        ]);
    }

    /**
     * Deletes an existing Country model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     *
     * @param string $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        
        return $this->redirect([
            'index'
        ]);
    }

    /**
     * Finds the Country model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     *
     * @param string $id
     * @return Country the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Country::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /*
     * jl
     */
    public function actionTree()
    {
        $list = (new \yii\db\Query())->select([
            'id',
            'pid',
            'name'
        ])
            ->from('country')
            ->all();
        
        $cat = new Category($list);
        $_city_tree = $cat::tree();
        return $this->render('tree', [
            'list' => $_city_tree
        ]);
    }

    //
    public function actionTreedata()
    {
        $list = (new \yii\db\Query())->select([
            'id',
            'pid',
            'name'
        ])
            ->from('country')
            ->all();
        
        $cat = new Category($list);
        $_city_tree = $cat::tree();
        echo json_encode($_city_tree);
    }

    //
    public function actionTreeupdate()
    {
        $data = Yii::$app->request->post();
        $countryModel = new Country();
        $countryModel::updateAll([
            'name' => $data['name']
        ], [
            'id' => $data['id']
        ]);
        echo '1';
    }

    //
    public function actionTreedelete()
    {
        $data = Yii::$app->request->post();
        $countryModel = new Country();
        $countryModel::deleteAll([
            'id' => $data['id']
        ]);
        echo '1';
    }

    //
    public function actionTreecreate()
    {
        $data = Yii::$app->request->post();
        $countryModel = new Country();
        $countryModel->pid = $data['pid'];
        $countryModel->save();
        $id = $countryModel->attributes['id'];
        $r = [];
        $r['status'] = 1;
        $r['id'] = $id;
        header('Content-Type:application/json; charset=utf-8');
        echo json_encode($r);
    }
}
