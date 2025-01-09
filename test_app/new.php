<?php
require_once('functions.php');
setToken();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>新規作成</title>
</head>
<body>
  <?php if (!empty($_SESSION['err'])): ?>
    <p><?= $_SESSION['err']; ?></p>
  <?php endif; ?>
<!-- データを保存する機能がある場所に送信する -->
  <form action="store.php" method="post">
    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
    <input type="text" name="content">
    <input type="submit" value="作成">
  </form>
  <div>
  <a href="index.php">一覧へ戻る</a>
  </div>
  <?php unsetError(); ?>
</body>
</html>

<!-- フォームの送信ボタンを押下した際にstore.phpの$_POSTにどんな値が格納されるか
〇formタグのaction属性で送信先のファイルのパス（store.php）を指定
送信ボタンを押した際にここで指定したファイルにデータの送信と同時に遷移（移動）を行う
・method属性でどういった方法でデータを送信するかを指定
　get - 入力したフォーム内容のデータをURIにくっつけて送信
　post - 入力したフォーム内容をURIとは別の場所に保管してデータ送信(安全性が高い)
〇inputタグでフォーム内に表示する項目を指定する
・type属性でhiddenを指定することで、送信したいデータがブラウザに表示させない
・name属性で入力データを取得する。（$_POST['content']とし、出力したときに値を表示する）
・value属性は空欄のため、'content'の部分はとくに指定なし
〇buttonタグで削除ボタンを表示。

store.phpの$_POSTは、name属性の'content'をキーとしてvalue属性を格納する -->