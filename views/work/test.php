<?php
use app\Constant\Constant;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\datetime\DateTimePicker;

/** @var array $company */
/** @var array $data */

$rs = [];
foreach ($data as $row){
    $rs[$row['workId']][] = $row;
}

?>

<style>
    tr.header-table th{
        text-align: center;
    }
    .btn-event{
        width: 20%;
    }
    .form-inline .filter-form{
        width: 40%;
        margin:15px 0;
    }
    .form-inline .filter-form input,.form-inline .filter-form select{
        width:60%;
    }
    .form-inline .filter-form label{
        width: 23%;
        text-align: left;
    }
    .input-group{
        width: 60%;
    }

</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <button class="btn btn-default btn-event" id="btn-filter"><?= Yii::t('app', 'Filter')?></button>
            <button class="btn btn-default btn-event"><?= Yii::t('app', 'Clear')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default"><?= Yii::t('app', 'Download CSV')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default"><?= Yii::t('app', 'Download media')?></button>
        </div>
        <div class="col-md-2">
            <button class="btn btn-default"><?= Yii::t('app', 'Download log')?></button>
        </div>

    </div>
    <div style="margin:20px 0;">
        <table class="table table-bordered" style="text-align: center;">
        <thead>
        <tr class="header-table">
            <th rowspan="2"><?= Yii::t('app', 'DL')?></th>
            <th rowspan="2"><?= Yii::t('app', 'WorkId')?></th>
            <th rowspan="2"><?= Yii::t('app', 'Finish time')?></th>
            <th rowspan="2"><?= Yii::t('app', 'Operation Kind') ?></th>
            <th rowspan="2""><?= Yii::t('app', 'Identify the high place')?></th>
            <th colspan="4"><?= Yii::t('app', 'Organization') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Seri ID') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Date') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Work Content') ?></th>
            <th colspan="4"><?= Yii::t('app', 'Picture') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Audio') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'KY division') ?></th>
            <th rowspan="2"><?= Yii::t('app', 'Result') ?></th>
        </tr>
        <tr class="header-table">
            <th><?= Yii::t('app', 'Branch/Office') ?></th>
            <th><?= Yii::t('app', 'FSC/Facility')?></th>
            <th><?= Yii::t('app', 'Employee')?></th>
            <th><?= Yii::t('app', 'Working employee') ?></th>
            <th><?= Yii::t('app', 'Picture 1') ?></th>
            <th><?= Yii::t('app', 'Picture 2')?></th>
            <th><?= Yii::t('app', 'Picture 3') ?></th>
            <th><?= Yii::t('app', '#') ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($rs as $row){
            $firstSubRow = true;
            if(count($row) == 1){
        ?>
                <tr>
                    <td><input type="checkbox" name="cb-download"></td>
                    <td><?= $row[0]['workId'] ?></td>
                    <td><?= $row[0]['end'] ?></td>
                    <td><?= $row[0]['faOperationKind'] ?></td>
                    <td>No data</td>
                    <td><?= $row[0]['branchName'].'-'.$row[0]['officeName'] ?></td>
                    <td><?= $row[0]['fscName'] ?></td>
                    <td>No data</td>
                    <td><?= $row[0]['userName'] ?></td>
                    <td><a href="<?= Url::to(['work/work-detail','id' => $row[0]['workId']])?>"><?= $row[0]['faOperationKind'].'-'.$row[0]['start'].'-'.$row[0]['workId'] ?></a></td>
                    <td><?php
                        $value = $row[0]['date'];
                        if($row[0]['start']){
                            $value .= '-'.$row[0]['start'];
                        }
                        echo $value;
                        ?>
                    </td>
                    <td><?php
                        $value = '';
                        if ($row[0]['officeContent']) {
                            $value = $row[0]['officeContent'];
                        }
                        if ($row[0]['selfContent']) {
                            $value = $row[0]['selfContent'];
                        }
                        echo $value;
                        ?>
                    </td>
                    <td><?= $row[0]['imageName'] ?></td>
                    <td><?= $row[0]['imageName'] ?></td>
                    <td><?= $row[0]['imageName'] ?></td>
                    <td>有</td>
                    <!--$row[0]['audioName']-->
                    <td>Play</td>
                    <td>Division</td>
                    <td><?php
                        $value = '';
                        switch ($row[0]['status']){
                            case Constant::CONFIRM_WORK:
                                if($row[0]['officeComment']){
                                    $value = 'コメント有り';
                                }else{
                                    $value = '確認済';
                                }
                                break;
                            case Constant::DECLINE_WORK:
                                $value = '不備有り';
                                break;
                        }
                        echo $value;
                        ?>
                    </td>


                </tr>
           <?php  }
            else{ ?>
                <?php
                foreach ($row as $subRow) {?>
                <tr>
                    <td><input type="checkbox" name="cb-download"></td>
                    <?php if($firstSubRow) {?>
                    <td rowspan="<?= count($row)?>"><?= $row[0]['workId'] ?></td>
                    <td rowspan="<?= count($row)?>"><?= $row[0]['end'] ?></td>
                    <td rowspan="<?= count($row)?>"><?= $row[0]['faOperationKind'] ?></td>
                    <td rowspan="<?= count($row)?>">No data</td>
                    <td rowspan="<?= count($row)?>"><?= $row[0]['branchName'].'-'.$row[0]['officeName'] ?></td>
                    <td rowspan="<?= count($row)?>"><?= $row[0]['fscName'] ?></td>
                    <td rowspan="<?= count($row)?>">No data</td>
                    <?php $firstSubRow = false; }?>
                    <td><?= $subRow['userName'] ?></td>
                    <td><a href="<?= Url::to(['work/work-detail','id' => $row[0]['workId']])?>"><?= $subRow['faOperationKind'].'-'.$subRow['start'].'-'.$subRow['workId'] ?></a></td>
                    <td><?php
                        $value = $subRow['date'];
                        if($subRow['start']){
                            $value .= '-'.$subRow['start'];
                        }
                        echo $value;
                        ?>
                    </td>
                    <td><?php
                        $value = '';
                        if ($subRow['officeContent']) {
                            $value = $subRow['officeContent'];
                        }
                        if ($subRow['selfContent']) {
                            $value = $subRow['selfContent'];
                        }
                        echo $value;
                        ?>
                    </td>
                    <td><?= $subRow['imageName'] ?></td>
                    <td><?= $subRow['imageName'] ?></td>
                    <td><?= $subRow['imageName'] ?></td>
                    <td>有</td>
                    <!-- $subRow['audioName']-->
                    <td>Play</td>
                    <td>Division</td>
                    <td><?php
                        $value = '';
                        switch ($subRow['status']){
                            case Constant::CONFIRM_WORK:
                                if($subRow['officeComment']){
                                    $value = 'コメント有り';
                                }else{
                                    $value = '確認済';
                                }
                                break;
                            case Constant::DECLINE_WORK:
                                $value = '不備有り';
                                break;
                        }
                        echo $value;
                        ?>
                    </td>
                  <?php } ?>


                </tr>
            <?php }?>

        <?php } ?>
        </tbody>
    </table>
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
<div style="text-align: center">
    <div class="form-inline">
        <div class="form-group filter-form" >
            <label for="workId"><?= Yii::t('app','Date')?></label>
            <?= DatePicker::widget([
                'name' => 'start-date',
                'value' => Date('Y-m-d'),
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])?>
        </div>
        <div class="form-group filter-form">
            <label>~</label>
            <?= DatePicker::widget([
                'name' => 'end-date',
                'value' => Date('Y-m-d'),
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Finish time')?></label>
            <?= DatePicker::widget([
                'name' => 'start-finish',
                'value' => Date('Y-m-d'),
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])?>
        </div>
        <div class="form-group filter-form">
            <label>~</label>
            <?= DatePicker::widget([
                'name' => 'end-finish',
                'value' => Date('Y-m-d'),
                'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                'removeButton' => false,
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'yyyy-mm-dd'
                ]
            ])?>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form" >
            <label for="workId"><?= Yii::t('app','Work Id')?></label>
            <input type="number" min="0" class="form-control" id="workId">
        </div>
        <div class="form-group filter-form">
            <label for="workType"><?= Yii::t('app','Work type')?></label>
            <select class="form-control" name="" id="workType">
                <option value="">Select</option>
            </select>
        </div>
    </div>
    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Identify high place')?></label>
            <select class="form-control" name="" id="highPlace">
                <option value="">Select</option>
            </select>
        </div>
        <div class="form-group filter-form">
            <label for="workType"><?= Yii::t('app','Branch')?></label>
            <select class="form-control" name="" id="branch">
                <option value="">Select Option</option>
                <?php foreach ($company['branchName'] as $row) {?>
                    <option value=""><?= $row['branchName'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Office')?></label>
            <select class="form-control" name="" id="office">
                <option value="">Select Option</option>
                <?php foreach ($company['officeName'] as $row) {?>
                    <option value=""><?= $row['officeName'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group filter-form">
            <label for="workType"><?= Yii::t('app','FSC')?></label>
            <select class="form-control" name="" id="fsc">
                <option value="">Select Option</option>
                <?php foreach ($company['fscName'] as $row) {?>
                    <option value=""><?= $row['fscName'] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Facility')?></label>
            <select class="form-control" name="" id="facility">
                <option value="">Select Option</option>
                <?php foreach ($company['facilityName'] as $row) {?>
                    <option value=""><?= $row['facilityName'] ?></option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group filter-form">
            <label for="workType"><?= Yii::t('app','Employee')?></label>
            <input type="text" class="form-control" id="employee">
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Working employee')?></label>
            <input type="number" min="0" class="form-control" id="workingEmployee">
        </div>
        <div class="form-group filter-form">
            <label for="workType" ><?= Yii::t('app','Work content')?></label>
            <select class="form-control" name="" id="workContent">
                <option value="">Select</option>
            </select>
        </div>
    </div>

    <div class="form-inline">
        <div class="form-group filter-form">
            <label for="workId"><?= Yii::t('app','Ky division')?></label>
            <select class="form-control" name="" id="kyDivision">
                <option value="">Select</option>
            </select>
        </div>
        <div class="form-group filter-form">
            <label for="workType"><?= Yii::t('app','Result')?></label>
            <select class="form-control" name="" id="result">
                <option value="">Select</option>
            </select>
        </div>
    </div>

    <div>
        <button class="btn btn-primary" style="width: 200px;margin:20px 0"><?= Yii::t('app','Filter')?></button>
    </div>
</div>
<?php Modal::end(); ?>
<script>

</script>
<?php $this->registerJsFile('@web/js/work-list.js',['depends' => 'yii\web\JqueryAsset'])?>



