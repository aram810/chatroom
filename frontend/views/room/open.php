<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JqueryAsset;

$this->title = 'Room #' . $roomId;
$this->params['breadcrumbs'][] = ['label' => 'Rooms', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile(
	Yii::getAlias('@web') . '/js/chat.js',
    ['depends' => [JqueryAsset::className()]]
);

?>
<h2><span id="active_users_count"><?= $activeUsersCount; ?></span> active user</h2>
<div id="chat">
	<div id="messages">
        <ul>
	        <?= $this->render('_chat', ['messages' => $messages, 'userId' => $userId]); ?>
        </ul>
	</div>

	<div id="new_message_section">
		<div id="new_message">
			<?= Html::textarea('new_message', ''); ?>
		</div>
		<div id="send_section">
			<?= Html::a('Send', ['room/update/'. $roomId], ['id' => 'send_message', 'class' => 'btn-primary btn']) ?>
		</div>
	</div>
</div>
<input id="update_url" type="hidden" name="" value="<?= Url::to(['room/update/' . $roomId]) ?>">
<input id="room_id" type="hidden" name="" value="<?= $roomId; ?>">