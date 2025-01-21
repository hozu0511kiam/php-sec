<?php
//データの受け取り・受け渡しとDBへの処理を依頼する機能をまとめるファイル

require_once('connection.php');
//connection.phpのDB操作をPOSTされた際にデータを渡すための記述
session_start();//SESSION を使用する

function e($text)//XSS エスケープ処理 htmlspecialchars()
{
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function setToken()//CSRF SESSIONにtokenを格納する
{
    $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(16));
    //ランダムな16文字のバイト文字列を生成し、変換
    //生成された値を $_SESSION['token'] に格納
}

function checkToken($token)//サーバ側とクライアント側のtokenが同じか
{
    if (empty($_SESSION['token']) || ($_SESSION['token'] !== $token)) {
        $_SESSION['err'] = '不正な操作です';
        redirectToPostedPage();
        // SESSIONに格納されたtokenのチェック、SESSIONにエラー文を格納する
    }
}

function unsetError()//err時にブラウザにエラーメッセージを表示させない
{
    $_SESSION['err'] = '';
}

function redirectToPostedPage()
{
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

function createData($post)
{
    createTodoData($post['content']);
}
//functions.php で connection.php に記載した処理に登録したデータを渡している。

function getTodoList()
{
    return getAllRecords();
}
//getTodoList関数を index.php 内で呼び出して、TODOデータの一覧表示を行う
//DBに対して作成したSQL文を実行し、
//fetchAll() で実行結果を全件配列で取得し結果を返すため、返り値はその結果の値

function getSelectedTodo($id)
{
    return getTodoTextById($id);
}

//getRefererPath関数をsavePostedData関数で呼び出す処理
function savePostedData($post)
{
    checkToken($post['token']);
    validate($post);//バリデーション 入力必須項目の指定
    $path = getRefererPath();
    switch ($path) {
    //条件分岐をして処理を振り分け
        case '/new.php':
            createTodoData($post['content']);
            //新規作成ページからPOSTされた
            //createTodoData関数を実行（INSERT処理）
            break;
        case '/edit.php':
            updateTodoData($post);
            //編集ページからPOSTされた
            //updateTodoData関数を実行（UPDATE処理）
            break;
        case '/index.php':
            deleteTodoData($post['id']);
            //新規作成ページの削除からPOSTされた
            //deleteTodoData関数を実行（論理削除のDB処理）
            break;
        default:
            break;
    }
}

function validate($post)
{
    if (isset($post['content']) && $post['content'] === '') {
        $_SESSION['err'] = '入力がありません';
        redirectToPostedPage();
    }
}

//getRefererPath関数を定義
function getRefererPath()
{
    $urlArray = parse_url($_SERVER['HTTP_REFERER']);
    
    return $urlArray['path'];
    //リクエスト元のURLを文字列で取得しそのパスを返す（["path"]=>string(9) "/edit.php"）
}