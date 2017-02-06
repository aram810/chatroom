<?php
foreach ($messages as $message) :

    if ((Yii::$app->user->isGuest && $message->user_session_id == Yii::$app->session->id) || (!Yii::$app->user->isGuest && Yii::$app->user->id == $message->user_id) ) : ?>
        <li data-id="<?= $message->id ?>" class="mine" ><?= $message->text; ?></li>
    <?php else : ?>
        <li data-id="<?= $message->id ?>"><?= $message->text; ?></li>
    <?php endif;

endforeach;
