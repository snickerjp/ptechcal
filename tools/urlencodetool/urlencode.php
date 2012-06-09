<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>URLエンコード確認</title>
<meta http-equiv="Content-Style-Type" content="text/css">
</head>

<body>

<h1>URLエンコード結果</h1>

URLエンコード後の文字列：
<form>

<textarea name="46860" rows="5" cols="50" onfocus="this.select()">
<?php
$name = $_GET["onamae"];
print rawurlencode($name);
?>
</textarea>

</form>

</body>
</html>
