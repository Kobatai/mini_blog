<?php

require '../boostrap.php';
require '../MiniBlogApplication.php';

// デバッグモードをtrueでインスタンスを作成する
$app = new MiniBlogApplication(true);
$app->run();