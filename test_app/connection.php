<?php
// config.phpを読み込み、中に記載あるものが使用可能になる
require_once('config.php');

// PDOクラスのインスタンス化
function connectPdo()
//返り値はPDO
//PDO(PHP Data Objects:DBとのやり取りをすることができるメソッドが詰め込まれた定義済みのクラス)をインスタンス化している。
{
    try {
        return new PDO(DSN, DB_USER, DB_PASSWORD);
    } catch (PDOException $e) {
        echo $e->getMessage(); // 例外メッセージを取得する
        exit();
    }
}
//try-catch:例外処理を実装するための構文
//try：エラーが発生する可能性のあるコード・catch(Exception $e)：エラー処理

//①例外を発生させる処理が書いていない
//PDOExceptionは、DBとの接続に失敗した場合などにおける例外のため、処理内容の記載はしない。

//②catchの引数がExceptionではなくPDOExceptionという別のクラスになっている。
//ExceptionクラスはPHPの例外階層の基底クラス
//PDOExceptionクラスはPDOを使用してDB操作を行う際の例外を表すクラス

//何らかの理由で指定したDBに接続できなかった場合、PDOクラスは例外（PDOException）を発生する
//例外によってスクリプト全体が停止しないようにするために例外処理をする必要がある

function createTodoData($todoText)
{
    $dbh = connectPdo();
    //DBへ接続する connectPdo関数 を呼びだし、返り値を $dbh に格納
    $sql = 'INSERT INTO todos (content) VALUES (:todoText)';// :todoText→プレースホルダー
    //実行したいSQL文 を作成し、$sql に格納
    $stmt = $dbh->prepare($sql);//prepare()でSQL文を実行する準備
    $stmt->bindValue(':todoText', $todoText, PDO::PARAM_STR);//bindValue()でプレースホルダに値をセット
    $stmt->execute();//実行
}

//データ取得処理 登録したデータをDBから全件取得する
function getAllRecords()
{
    $dbh = connectPdo();
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL';
    //todosテーブルから、削除されていないレコードを全件取得する
    return $dbh->query($sql)->fetchAll();//メソッドチェーン:メソッド2は、メソッド1の返り値から呼ばれている
    //PDO::query() は変数$sqlを引数とし、SQL文の結果を返り値とし、PDOStatementオブジェクトを返す
    //PDOStatement::fetchAll() はqueryメソッドの返り値から呼ばれ、結果の要素全てを返す
}

function updateTodoData($post)
{
    $dbh = connectPdo();
    //connectPdo関数を呼びだし、返り値を $dbh に格納
    //DBとのやりとり
    $sql = 'UPDATE todos SET content = :todoText WHERE id = :id';
    $stmt = $dbh->prepare($sql);//prepare()でSQL文を実行する準備
    $stmt->bindValue(':todoText', $post['content'], PDO::PARAM_STR);//bindValue()でプレースホルダに値をセット
    $stmt->bindValue(':id', (int) $post['id'], PDO::PARAM_INT);//bindValue()でプレースホルダに値をセット
    $stmt->execute();//実行
}

function getTodoTextById($id)
{
    $dbh = connectPdo();
    //connectPdo関数 を呼びだし、返り値を $dbh に格納
    //DBとのやりとり
    $sql = 'SELECT * FROM todos WHERE deleted_at IS NULL AND id = :id';
    $stmt = $dbh->prepare($sql);//prepare()でSQL文を実行する準備
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);//bindValue()でプレースホルダに値をセット
    $stmt->execute();//実行
    $todo = $stmt->fetch();
    return $todo['content'];
    //変数$sqlにSQL文（文字列）を代入してfetch関数に$data['content']を返す
    //$data['content']は、結果の中のcontent（ページ内の'内容に当たる'）
}


function deleteTodoData($id)
{
    $dbh = connectPdo();
    //connectPdo関数 を呼びだし、返り値を $dbh に格納
    //DBとのやりとり
    $now = date('Y-m-d H:i:s');
    //変数$nowにdate関数を代入
    //現在時刻をdate関数で定義
    $sql = 'UPDATE todos SET deleted_at = :now WHERE id = :id' ;
    $stmt = $dbh->prepare($sql);//prepare()でSQL文を実行する準備
    $stmt->bindValue(':now', $now, PDO::PARAM_INT);//bindValue()でプレースホルダに値をセット
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);//bindValue()でプレースホルダに値をセット
    $stmt->execute();//実行
    //echo $sql;
}

//cross site request forgery