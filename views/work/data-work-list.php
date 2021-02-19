<?php

use app\Constant\Constant;
use toriphes\lazyload\LazyLoad;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\widgets\LinkPager;
/** @var $data \yii\data\ArrayDataProvider */
?>
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
    <?php foreach ($data as $subRow){?>
        <tr>
            <td><input type="checkbox" class="cb-download" name="cb-download-<?= $subRow['workId'] ?>" value="<?= $subRow['workId'] ?>"></td>
            <td><?= $subRow['workId'] ?></td>
            <td><?= $subRow['end'] ?></td>
            <td><?= $subRow['faOperationKind'] ?></td>
            <td>
                <?php
                $value = '';
                if($data = $subRow['officeArialWork'] || $data = $subRow['selfArialWork']){
                    $value = $data == Constant::HIGH_PLACE ? 'High place': 'Short place';
                }
                echo $value;
                ?>
            </td>
            <td><?= $subRow['branchName'].'-'.$subRow['officeName'] ?></td>
            <td><?= $subRow['fscName'] ?></td>
            <td></td>
            <td><?= $subRow['userName'] ?></td>
            <td><a href="<?= Url::to(['work/work-detail','id' => $subRow['workId']])?>"><?= $subRow['faOperationKind'].'-'.$subRow['start'].'-'.$subRow['workId'] ?></a></td>
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
            <td><?php if($subRow['imageName']['picture1']){
                    echo LazyLoad::widget(['src' => Url::to(['media/view', 'image' => $subRow['imageName']['picture1']])]);
                }?>


            </td>
            <td><?php if($subRow['imageName']['picture2']) {
                    echo LazyLoad::widget(['src' => Url::to(['media/view', 'image' => $subRow['imageName']['picture2']])]);
                }
                ?>

            </td>
            <td><?php if($subRow['imageName']['picture3']) {
                    echo LazyLoad::widget(['src' => Url::to(['media/view', 'image' => $subRow['imageName']['picture3']])]);
                }?>
            <td><?php
                if(is_array(isset($subRow['imageName'])) && count(isset($subRow['imageName']) > 3)){
                    echo '有';
                }else{
                    echo '無';
                }
                ?>
            </td>
            <!-- $subRow['audioName']-->
            <td style="color:lightskyblue;text-decoration: underline;" id="td-audio">
                <?php if($subRow['audioName']) {?>
                    <p class ="audios">Play</p>
                    <audio controls style="display: none;">
                        <source src=<?= Url::to(['media/audio','audio' => $subRow['audioName']]) ?> type="audio/mp4">
                        Your browser does not support the audio element.
                    </audio>
                <?php }?>
            </td>
            <td></td>
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
        </tr>
    <?php } ?>

        </tbody>
    </table>
<?php
/** @var Pagination $pages */
echo LinkPager::widget([
    'pagination' => $pages,
]);
$js = <<< JS

$('.audios').on('click',function () {
        $(this).siblings('audio')[0].play();
    });
 checkboxes = [];

// var currentCheckBoxes = getCookie('checkboxes');
$('.cb-download').on('change',function () {
     checkboxes.push($(this).val());

    });

// $(document).ajaxComplete(function() {
//  
// });
$('.pagination a').click(function(e) {
    e.preventDefault();
    setCookieCheckboxes(checkboxes);
    var url = $(this).attr('href');
    var data = $('#filter-search-form').serializeArray();
    $.ajax({
        url: url,
        type: 'post',
        dataType: 'html',
        data:data,
        success: function (result) {
            //  console.log(result);
            $('#data-work-list').html(result);
            setCheckBox();
        }
    });
    checkboxes = [];

});


 
 

JS;

$this->registerJs($js);
?>





