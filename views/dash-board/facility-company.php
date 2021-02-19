<?php
use yii\grid\GridView;
use yii\widgets\Pjax;
/** @var \yii\data\ActiveDataProvider $dataProvider */
/** @var  array $information */
/** @var int $id */
?>
<div class="info-filter">
    <div id="organization-info">
        <div class="info">
            <div class="info-name"><?= Yii::t('app','Register')?></div>
            <div class="value"><?= $information['numberOfUser']?></div>
        </div>
        <div class="info">
            <div class="info-name"><?= Yii::t('app','Working')?></div>
            <div class="value"><?= $information['peopleAreWorking']?></div>
        </div>
        <div class="info">
            <div class="info-name"><?= Yii::t('app','Wait for confirm')?></div>
            <div class="value"><?= $information['peopleAreWaiting']?></div>
        </div>
    </div>
    <div id="organization-filter">
        <label class="checkbox-inline"><input type="checkbox" id="cb-working" class="checkbox-group" name="cb-working"><?= Yii::t('app','Working')?></label>
        <label class="checkbox-inline"><input type="checkbox" id="cb-confirm" class="checkbox-group" name="cb-confirm"><?= Yii::t('app','Waiting confirm')?></label>
        <label class="checkbox-inline"><input type="checkbox" id="cb-decline" class="checkbox-group" name="cb-decline"><?= Yii::t('app','Decline')?></label>
        <label class="checkbox-inline"><input type="checkbox" id="cb-finished" class="checkbox-group" name="cb-finished"><?= Yii::t('app','Finished')?></label>
    </div>
</div>
<?php Pjax::begin(['id' => 'pjax-facility-company'])?>
    <?= GridView::widget([
            'id' => 'grid-company',
            'dataProvider' => $dataProvider,
            'layout' => "{summary}\n{items}\n{pager}",
            'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => ''],
            'tableOptions' =>['class' => 'table table-bordered','style' => ['border' => '1px solid #044368']],
            'rowOptions' => function($model){
                $color = '#ffffff';
                switch ($model['status']){
                    case 2:
                        $color = '#94E3FC';//blue
                        break;
                    case 3:
                        $color = '#EA94FF';//pink
                        break;
                    case 4:
                        $color = '#94E3FC';//blue
                        break;
                    case 6:
                        $color = '#f2f2f2';//gray
                        break;
                }
                $url = \yii\helpers\Url::to(['work/work-detail','id' => $model['id']]);
                return [
                        'onclick' => "window.location.href='{$url}'",
                        'style' => ['background-color' => $color,'text-align'=>'center']
                ];
            },
            'columns' => [
                [
                    'label' => Yii::t('app','Status'),
                    'value' => 'id'
                ],
                [
                    'label' => Yii::t('app','Status'),
                    'value' => 'status'
                ],
                [
                    'label' => Yii::t('app','Branch'),
                    'value' => 'branchName'
                ],
                [
                    'label' => Yii::t('app','Office'),
                    'value' => 'officeName'
                ],
                [
                    'label' => Yii::t('app','FSC'),
                    'value' =>'fscName'
                ],
                [
                    'label' => Yii::t('app','Facility'),
                    'value' => 'facilityName'
                ],
                [
                    'label' => Yii::t('app','User Name'),
                    'value' => 'userName',
                ],
                [
                    'label' => Yii::t('app','Project Type'),
                    'value' => 'faOperationKind',
                    'attribute' => 'faOperationKind'
                ],
                [
                    'label' => Yii::t('app','Work Content'),
                    'value' => function($model){
                        $value = '';
                        if($model['officeContent']){
                            $value = $model['officeContent'];
                        }
                        if($model['selfContent']){
                            $value = $model['selfContent'];
                        }
                        return $value;
                    }
                ],
                [
                    'label' => Yii::t('app','Start Time'),
                    'value' => 'start',
                    'attribute' => 'start'
                ],
                [
                    'label' => Yii::t('app','Duration'),
                    'value' => function($model){
                        return  strtotime('now') ;
                    }

                ],
                [
                    'label' => Yii::t('app','Finish Time'),
                    'value' => 'end',
                    'attribute' => 'end'
                ],

            ],

        ]) ?>
<?php Pjax::end()?>
<script>
    var urlUpdateData = "<?= \yii\helpers\Url::to(['dash-board/facility-company'])?>"
    var organizationId = "<?= $id ?>";
</script>
<?php $this->registerCssFile('@web/css/dash-board.css')?>
<?php $this->registerJsFile('@web/js/dash-board.js',[
    'depends' => [
        \yii\web\JqueryAsset::className()
    ]]);?>
