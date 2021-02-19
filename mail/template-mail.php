<?php
/** @var \app\models\User $user */

use yii\helpers\Html;
use yii\helpers\Url;
$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['setting-password', 'token' => $user->token]);
?>
<div>
    <p>※本メールは安全KYシステムより自動で送信されております。 </p>
    <p><?= $user->userName?></p>
    <p>安全KYシステムのアカウントパスワードを発行するには、本メール受信の1時間以内に下記のURLよりパスワードの設定を実施してください。 </p>
    <p>■申請情報再設定URL </p>
    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>
    <p>このメールに心当たりがない場合、他の方がパスワードをリセットする際に誤った社員コードを入力した可能性があります。リクエストした覚えがない場合は、何も行わずにこのメールを破棄してください。 </p>
    <p>※本メールは送信専用メールアドレス</p>
    <p>(noreply@web.anzen-ky.ft-ai.jp)から配信されています。</p>
    <p></p>ご返信いただいてもお答えできませんのでご了承ください。</p>
</div>
