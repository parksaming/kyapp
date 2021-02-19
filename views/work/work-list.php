<?php
use app\Constant\Constant;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;
use yii\bootstrap\ActiveForm;
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use toriphes\lazyload\LazyLoad;

/** @var array $company */
/** @var array $data */
/** @var array $workContent */
/** @var \app\models\FilterForm $model*/
/** @var array $originalData */
//
//echo '<pre>';
//print_r($result);
//echo '</pre>';
$workType = [
        Constant::OFFICE_TASK => 'Office task',
        Constant::SELF_TASK => 'Self task',
            ];
$identifyPlace = [
        Constant::SHORT_PLACE => 'Short place',
        Constant::HIGH_PLACE => 'High place',
];
$workContentArr = ArrayHelper::getColumn($workContent, 'name');
$workContentArr[] = 'abc';
$statusArr = ['Confirmed','Comment','Not finish yet','Blank'];

?>

<style>
    tr.header-table th{
        text-align: center;
    }
    .btn-event{
        width: 20%;
    }
    .form-inline .filter-form{
        width: 49%;
        margin:15px 0;
    }
    .form-inline .filter-form input,.form-inline .filter-form select{
        width:70%;
    }
    .form-inline .filter-form label{
        width: 19%;
        text-align: left;
    }
    .input-group{
        width: 70%;
    }
    div.filter-form div.form-group{
        width: 90%;
    }
    .error-summary{
        background: none;
        border-left: none;
    }
    .error-summary ul {
        list-style: none;
    }
    td#td-audio:hover{
        cursor: pointer;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-default btn-event" id="btn-filter"><?= Yii::t('app', 'Filter')?></button>
            <button class="btn btn-default btn-event" id="btn-clear"><?= Yii::t('app', 'Clear')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default" id="btn-download-csv"><?= Yii::t('app', 'Download CSV')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default" id="btn-download-media"><?= Yii::t('app', 'Download media')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default"><?= Yii::t('app', 'Download log')?></button>
        </div>

    </div>
    <div style="margin:20px 0;" id="data-work-list">
        <?php
            /** @var Pagination $pages */
            echo $this->render('data-work-list',['data' => $data,'pages' => $pages])
        ?>
    </div>
</div>
<!--Filter modal-->
<?php
Modal::begin([
    'header' =>  Yii::t('app','Filter'),
    'id' => 'filter-modal',
    'closeButton' => false,
    'size' => 'modal-lg'
]);
?>
<?php Pjax::begin(
    [
        'enablePushState' => false
    ]

)?>
<?php $form = ActiveForm::begin(
    [
        'id' => 'filter-search-form',
        'fieldConfig' => [
            'enableError' => false,
        ],
        'options' => ['data-pjax' => true],

    ]
)
?>

<div style="text-align: center">
    <div class="form-inline">
        <div class="form-group filter-form" >
            <?= $form->field($model,'startDate')->widget(
                DatePicker::className(),
                [
                    'name' => 'startDate',
                    'value' => Date('Y-m-d'),
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
//                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]
            )->label(Yii::t('app','Date'))?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'endDate')->widget(
                DatePicker::className(),
                ['name' => 'endDate',
                    'value' => Date('Y-m-d'),
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
//                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]
            )->label('~')?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'startFinishTime')->widget(
                DatePicker::className(),
                [
                    'name' => 'startFinishTime',
                    'value' => Date('Y-m-d'),
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
//                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]
            )->label(Yii::t('app','Finish time'))?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'endFinishTime')->widget(
                DatePicker::className(),
                [
                    'name' => 'endFinishTime',
                    'value' => Date('Y-m-d'),
                    'type' => DatePicker::TYPE_COMPONENT_PREPEND,
//                    'removeButton' => false,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'yyyy-mm-dd'
                    ]
                ]
            )->label('~')?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form" >
            <?= $form->field($model,'projectId')->label(Yii::t('app','Project Id'))->input('text',['class' =>'form-control','id' => 'projectId'])?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'workType')->dropDownList(
                $workType,
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Work type'))?>
        </div>
    </div>
    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'identifyPlace')->dropDownList(
                $identifyPlace,
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Identify place'))?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'branch')->dropDownList(
                array_combine($company['branchName'],$company['branchName']),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Branch'))?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'office')->dropDownList(
                array_combine($company['officeName'],$company['officeName']),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Office'))?>

        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'fsc')->dropDownList(
                array_combine($company['fscName'],$company['fscName']),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','FSC'))?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'facility')->dropDownList(
                array_combine($company['facilityName'],$company['facilityName']),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Facility'))?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'employee')->label(Yii::t('app','Employee'))->input('text',['class' => 'form-control','id' => 'employee'])?>

        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'workingEmployee')->label(Yii::t('app','Working employee'))->input('text',['class' => 'form-control','id' => 'workingEmployee'])?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'workContent')->dropDownList(
                array_combine($workContentArr,$workContentArr),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Work content'))?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <?= $form->field($model,'kyDivision')->dropDownList(
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Ky division'))?>
        </div>
        <div class="form-group filter-form">
            <?= $form->field($model,'result')->dropDownList(
                array_combine($statusArr,$statusArr),
                ['prompt'=>'Select Option']
            )->label(Yii::t('app','Result'))?>
        </div>
    </div>


        <p style="color:red;"><?= $form->errorSummary($model,['header' => ''])?></p>



    <div>
        <button class="btn btn-primary" id="btn-submit-form"style="width: 200px;margin:20px 0"><?= Yii::t('app','Filter')?></button>
    </div>
</div>

<?php ActiveForm::end() ?>
<?php Pjax::end()?>
<?php Modal::end(); ?>
<form action="<?= Url::to(['work/download-works-media'])?>" method="POST" id="id-work-form">
    <input type="hidden" id="workArray">
</form>
<script>
    var urlFilterSearch = "<?= Url::to(['work/filter-search'])?>";
    var urlWorkList = "<?= Url::to(['work/work-list'])?>";
    var urlDownloadMedia = "<?= Url::to(['work/download-works-media'])?>";
    var urlDownload = "<?= Url::to(['media/download'])?>";

</script>
<?php $this->registerJsFile('@web/js/work-list.js',['depends' => 'yii\web\JqueryAsset'])?>



