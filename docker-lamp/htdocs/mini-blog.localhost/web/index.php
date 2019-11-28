<?php

require '../boostrap.php';
require '../MiniBlogApplication.php';

$app = new MiniBlogApplication(false);
$app->run();