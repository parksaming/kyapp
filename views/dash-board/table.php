<?php
use yii\helpers\Url;
/** @var id $organizationId */
?>
<div class="item">
    <div class="name">
        <span> <a href="<?= Url::to(['dash-board/facility-company','organizationId' => $data['id'] ])?>"><?= $data['name']?></a></span>
        <?php
            $bgColor = '#EA94FF';
            $value = $data['peopleAreWaiting'];
            if($value == 0){
                $bgColor = '';
                $value = '';
            }
        ?>
        <div class="item-value" style="background-color:<?= $bgColor ?>"><?= $value ?></div>
        <div class="item-value">
            <?php
                echo ($data['peopleAreWorking'] > 0) ? $data['peopleAreWorking'] : '';
            ?>
        </div>
        <div class="item-value">
            <?php
            echo ($data['numberOfUser'] > 0) ? $data['numberOfUser'] : '' ;
            ?>
        </div>
    </div>

    <div class="child">
        <?php
        if(isset($data['child'])){
            foreach ($data['child'] as $item){
                echo $this->render('table',['data'=>$item]);
            }
         }

        ?>
    </div>
</div>
