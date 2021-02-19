<div class="item">
    <div class="name">
        <?= $data['name']?>
        <?php
            $bgColor = '#EA94FF';
            $value = isset($data['totalPeopleAreWaiting']) ? $data['totalPeopleAreWaiting'] : $data['peopleAreWaiting'];
            if($value == 0){
                $bgColor = '';
                $value = '';
            }
        ?>
        <div class="item-value" style="background-color:<?= $bgColor ?>"><?= $value ?></div>
        <div class="item-value">
            <?php
                echo isset($data['totalPeopleAreWorking']) ? $data['totalPeopleAreWorking'] : $data['peopleAreWorking'];
            ?>
        </div>
        <div class="item-value">
            <?php
            echo isset($data['totalUser']) ? $data['totalUser'] : $data['numberOfUser'];
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
