<?php

namespace frontend\controllers;

use common\models\ActiveUser;
use common\models\Message;
use Yii;
use common\models\Room;
use yii\base\Exception;
use yii\base\Request;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * RoomController implements the CRUD actions for Room model.
 */
class RoomController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Room models.
     * @return mixed
     */
    public function actionIndex()
    {
    	$query = Yii::$app->user->isGuest
		    ? Room::find()->where(['access_level' => 0])->orderBy(['id' => SORT_DESC])
	        : Room::find()->where(['or', ['created_by' => null], ['created_by' => Yii::$app->user->id]])->orderBy(['id' => SORT_DESC]);


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Room model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Room();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['room/open/'. $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

	public function beforeAction($action)
	{
		if (Yii::$app->request->isAjax) {
			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction($action);
	}

    /**
     * Updates an existing Room model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $messageText = Yii::$app->request->post('message');

        if ('' !== trim($messageText)) {
        	$message = new Message();

        	$message->text = $messageText;
        	$message->user_id = Yii::$app->user->isGuest ? null : Yii::$app->user->id;
        	$message->user_session_id = null === $message->user_id ? Yii::$app->session->id : null;
        	$message->room_id = $id;

        	$message->save();
        }

        $messages = $model->getMessages()->where(['>', 'id', Yii::$app->request->post('lastMessageId') ?: 0] )->all();

	    if (Yii::$app->request->isAjax) {
		    Yii::$app->response->format = Response::FORMAT_JSON;

		    return [
		    	'html' => $this->renderPartial('_chat', ['messages' => $messages]),
			    'activeUsersCount' => $model->getActiveUsersCount()
		    ];
	    }

        return $this->renderPartial('_chat', ['messages' => $messages]);
    }

	public function actionOpen($id)
	{
		if (!$this->currentUserHasAccess($id)) {
			throw new Exception('You have not access to private rooms.');
		}

		$model = self::findModel($id);

		$messages = Message::find()->where(['room_id' => $id])->all();
		$userId = Yii::$app->user->id ?: 0;
		$activeUsersCount = $model->getActiveUsersCount();

		return $this->render('open', [
			'messages' => $messages,
			'userId' => $userId,
			'roomId' => $id,
			'activeUsersCount' => $activeUsersCount,
		]);
	}

    /**
     * Finds the Room model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Room the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Room::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

	private function currentUserHasAccess($room_id) {
		$room = $this->findModel($room_id);
		$isPrivate = 1 === $room->access_level;

		if ($isPrivate) {
			$isGuestWithoutToken = Yii::$app->user->isGuest && Yii::$app->request->get('token') === null;
			$isCreator = Yii::$app->user->id === $room->created_by;
			$tokenRequired = strlen($room->token) > 10;
			$wrongToken = Yii::$app->request->get('token') !== null && Yii::$app->request->get('token') !== $room->token;
			$withoutToken = Yii::$app->request->get('token') === null;

			if ($isGuestWithoutToken) {
				return false;
			}

			if ($wrongToken) {
				return false;
			}

			if ($withoutToken && $tokenRequired && !$isCreator) {
				return false;
			}
		}

		return true;
    }
}
