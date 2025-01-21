<?php
//データを保存する機能がある場所に送信する

require_once('functions.php');

//var_dump($_POST);
//exit;

//array(1) { ["content"]=> string(4) "入力した値" }
//name属性の'content'をキーとしてvalue属性を格納する

// functions.php 内の createData関数 にPOSTデータを渡すことが可能に
//createData($_POST);
savePostedData($_POST);
header('Location: ./index.php');
//header関数の引数を「Location：遷移先」にして実行
//指定したパスに存在する指定したファイルに遷移することができる
