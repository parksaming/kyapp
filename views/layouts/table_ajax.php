<?php

use app\assets\AppAsset;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

AppAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <?php $this->registerCsrfMetaTags() ?>
</head>
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
<body>
<?php $this->beginBody() ?>

        <?= $content ?>


<?php $this->endBody() ?>
</body>
<?php $this->registerJsFile('@web/js/work-list.js',['depends' => 'yii\web\JqueryAsset'])?>
</html>
<?php $this->endPage() ?>
