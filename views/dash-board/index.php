<?php
/** @var yii\web\View\ $this */
use yii\web\View;
$this->title = Yii::t('app','Confirm Work');
?>
<h2 style="margin-top: 0"><?= Yii::t('app','Confirm Work')?></h2>
<div class="header">
    <div id="first-item"><?= Yii::t('app','Company')?></div>
    <div class="item-value"> <?= Yii::t('app','Wait for confirm')?></div>
    <div class="item-value"> <?= Yii::t('app','Working')?></div>
    <div class="item-value"> <?= Yii::t('app','Register')?></div>
</div>

<div class="items">
    <?php
        echo $this->render('table',['data' => $data]);
    ?>
</div>

<style>
    .items > div{
        border-right: 1px solid #1F497D;
        border-bottom: 1px solid #1F497D;
    }
    .item {
        border-top:1px solid #1F497D;
        border-left:1px solid #1F497D;
    }

    .item .child{
        padding-left: 30px;
    }
    .header{
        border-top: 1px solid #1F497D;
        border-left: 1px solid #1F497D;
        border-right: 1px solid #1F497D;
    }
    .item .name, .header{
        padding-left: 5px;
        height: 40px;
        line-height: 40px;
    }
    .item-value{
        float: right;
        width: 200px;
        height: 40px;
        border-left:1px solid #1F497D;
        text-align: center;
    }
    #first-item{
        width: calc(100% - 600px);
        float: left;
        text-align: center;
    }
</style>
