<?php

use yii\bootstrap\Modal;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\User;
/** @var array $work */
//echo '<pre>';
//print_r($work);
//echo '</pre>';
$work['start'] = new DateTime($work['start']);
$work['end'] = new DateTime($work['end']);
$disabled = true;
$audioPath = '';
if($work['audio']){
    $disabled = false;
    $audioPath = Url::to(['media/audio','audio' => $work['audio']]);
}
$canConfirmWork = false;
$permission = User::canConfirmWork(Yii::$app->user->identity->getId());
$workStatus = [2,3];
if(in_array($work['status'],$workStatus) && $permission){
    $canConfirmWork = true;
}
$imagePath = Url::to(['/media/view' ,'image' => 'h2_small.jpg']);
?>
<div class="row" id="user-work-info">
    <div class="col-md-4" >
        <p><?= Yii::t('app','User code')?> : <?= $work['userCode'] ?></p>
        <p><?= Yii::t('app','User name')?> : <?= $work['userName'] ?></p>
        <p><?= Yii::t('app','Phone number')?> : <?= $work['phone'] ?></p>
    </div>
    <div class="col-md-4">
        <p class="info-label"><?= Yii::t('app','Start time')?></p>
        <p><?= date_format($work['start'],'d-m-Y H:i:s') ?></p>
        <p class="info-label"><?= Yii::t('app','Work content')?></p>
        <p><?= $work['workContent'] ?></p>
    </div>
    <div class="col-md-4">
        <p class="info-label"><?= Yii::t('app','End time')?></p>
        <p><?= date_format($work['end'],'d-m-Y H:i:s') ?></p>
    </div>
</div>

<div id="work-image">
    <p><?= Yii::t('app','Work Detail Image')?></p>
    <div class="row" id="image">
   
        <?php 
        if(isset($work['images'])){
            foreach ($work['images'] as $image){

            ?>
                <?= $this->render('row-image',['image' => $image])?>
            <?php }?>
        <?php }?>
    </div>


    <div class="row">
        <div id="user-comment">
            <p>User Comment</p>
        </div>
        <div id="confirm-audio-button">
            <?= Html::button(Yii::t('app','Confirm Audio'),['id' => 'confirm-audio','class' => 'btn btn-primary','disabled' => $disabled])?>
        </div>
    </div>
    <div class="row">
            <label><?= Yii::t('app','Confirm comment')?></label>
            <br >
            <input type="text" class="form-control" id="confirm-comment" maxlength="300">
            <div id="button-detail">
                <?= Html::button(Yii::t('app','Decline'),['id' => 'btn-decline','class' => 'btn btn-danger'])?>
                <?= Html::button(Yii::t('app','Accept'),['id' => 'btn-accept','class' => 'btn btn-primary'])?>
            </div>

    </div>
    <div style="display: none;">
        <audio id ="audio-file" controls>
            <?php if($audioPath) {?>
            <source src=<?= $audioPath ?> type="audio/mp4">
            Your browser does not support the audio element.
            <?php } ?>
        </audio>
    </div>

</div>
<div id="fullsize-image">
    <img src="#" alt="Fullsize-Image" class="responsive center" >
</div>
<!--Modal confirm M31-->
<?php
Modal::begin([
    'header' =>  Yii::t('app','Confirm Work'),
    'id' => 'modal-confirm',
    'closeButton' => false,
]);
?>
<div id="confirm-message">
    <p><?= Yii::t('app','This will be confirmed,won\'t it?')?></p>
</div>
<div id="btn-group">
    <button class="btn btn-primary" id='btn-confirm'> <?= Yii::t('app','Yes')?></button>
    <button class="btn" id='btn-not-confirm'><?= Yii::t('app','No')?></button>
</div>
<?php Modal::end(); ?>

<!--Modal decline M30-->
<?php
Modal::begin([
    'header' =>  Yii::t('app','Decline Work'),
    'id' => 'modal-decline',
    'closeButton' => false,
]);
?>
<div id="confirm-message">
    <p><?= Yii::t('app','This will be declined,won\'t it?')?></p>
</div>
<div id="btn-group">
    <button class="btn btn-primary" id='btn-decline'> <?= Yii::t('app','Yes')?></button>
    <button class="btn" id='btn-not-decline'><?= Yii::t('app','No')?></button>
</div>
<?php Modal::end(); ?>
<!--<button class="btn btn-info" id="test">Image</button>-->
<?php
//Modal::begin([
//    'header' =>  Yii::t('app','Confirm send email'),
//    'id' => 'modal-image',
//    'closeButton' => false,
//    'size' => 'modal-lg'
//]);
//?>
<!--<img src="--><?//= $imagePath ?><!--" alt="" style="width: 100%;height: 100%">-->

<script>
    var urlUpdateStatusWork = "<?= \yii\helpers\Url::to(['work/update-status','id' => $_GET['id']])?>";
    var canConfirmWork = "<?php echo $canConfirmWork ?>" ;
    var csrf = "<?=Yii::$app->request->getCsrfToken()?>";
</script>

<?php $this->registerCssFile('@web/css/work-detail.css') ?>
<?php $this->registerJsFile('@web/js/work-detail.js',[
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]]) ?>

