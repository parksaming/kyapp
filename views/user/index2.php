<div class="header">
    <?= Yii::t('app','Company')?>
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
      border-right: 1px solid blue;
      border-bottom: 1px solid blue;
   }
   .item {
      border-top:1px solid blue;
      border-left:1px solid blue;
   }

   .item .child{
      padding-left: 30px;
   }
   .header{
      border-top: 1px solid blue;
      border-left: 1px solid blue;
      border-right: 1px solid blue;
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
      border-left:1px solid blue;
      text-align: center;
   }
</style>