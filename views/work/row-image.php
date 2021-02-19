<?php
/** @var string $image */
use yii\helpers\Url;
$imagePath = Url::to(['/media/view' ,'image' => $image]);
?>
<div class="col-md-4 col-image">
    <h4>Image</h4>
    <div class="row">
        <div class="col-md-7" style="height: 350px">
            <div style="background-color:#858585;height: 59%;margin: 70px 0;"></div>
        </div>
        <div class="col-md-5 image-right">
                <img src=<?= $imagePath ?> alt="Image" class="img-responsive" style="height: 350px">

        </div>
    </div>

</div>

