<?
define(LISTPAGE, "/affiliates/");
define(LOGFILE, "log/counter.txt");
define(PASSWORD, "MhzkzdFloPQP2");

function showAdminLogin()
{
  header("Content-Type: text/html");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Affliates Admin Login</title>
  </head>
  <body>
<?
  $i_pass = htmlspecialchars(mb_convert_encoding($_POST['password'], 'UTF-8'));
  $i_mode = htmlspecialchars(mb_convert_encoding($_POST['mode'], 'UTF-8'));
  $i_type = htmlspecialchars(mb_convert_encoding($_POST['type'], 'UTF-8'));
  if ($i_pass && $i_pass == PASSWORD) {
?>
    <h1>Affliates Admin Panel</h1>
<?
    if ($i_mode && $i_mode == "show_table") {
?>
    <h2>Show Table</h2>
    <pre><? readfile(LOGFILE); ?></pre>
<?
    } else if ($i_mode && $i_mode == "add_entry") {
      $newline = $i_type.",0,0,\n";
      $fp = fopen(LOGFILE, "a");
      flock($fp, LOCK_EX);
      fputs($fp, $newline);
      flock($fp, LOCK_UN);
      fclose($fp);
      echo "<p>".$i_type." Added.</p>";
    }
  } else {
?>
    <h1>Affliates Admin Login</h1>
    <h2>Show Table</h2>
    <form method="post" action="admin">
      <p>Password: <input type="password" name="password" /></p>
      <p><input type="hidden" name="mode" value="show_table" /><input type="submit" value="Go" /></p>
    </form>
    <h2>Add Entry</h2>
    <form method="post" action="admin">
      <p>Number: <input type="text" name="type" /></p>
      <p>Password: <input type="password" name="password" /></p>
      <p><input type="hidden" name="mode" value="add_entry" /><input type="submit" value="Go" /></p>
    </form>
<?
  }
?>
  </body>
</html>
<?
  exit;
}

function showImage()
{
  $lines = file(LOGFILE);
  $find = FALSE;
  $i_type = htmlspecialchars(mb_convert_encoding($_GET['type'], 'UTF-8'));
  /*
  for ($i = 0; $i < count($lines); $i++) {
    list($type,$image_count,$download_count) = explode(",",$lines[$i]);
    if ($i_type == $type) {
      $find = TRUE;
      $image_count++;
      $lines[$i] = "$type,$image_count,$download_count,\n";
      break;
    }
  }
  if ($find) {
    if ($_SERVER['HTTP_REFERER'] != LISTPAGE) {
      $fp = fopen(LOGFILE, "w");
      flock($fp, LOCK_EX);
      fputs($fp, implode("", $lines));
      flock($fp, LOCK_UN);
      fclose($fp);
    }
    header("Content-Type: image/png");
    header("Content-Disposition: attachment; filename=$type.png");
    readfile("images/$type.png");
  }
  */
    header("Content-Type: image/png");
    // header("Content-Disposition: attachment; filename=$i_type.png");
    readfile("images/$i_type.png");
  exit;
}

function countDownload()
{
  $lines = file(LOGFILE);
  $find = FALSE;
  $i_type = htmlspecialchars(mb_convert_encoding($_GET['type'], 'UTF-8'));
  /*
  for ($i = 0; $i < count($lines); $i++) {
    list($type,$image_count,$download_count) = explode(",",$lines[$i]);
    if ($i_type == $type) {
      $find = TRUE;
      $download_count++;
      $lines[$i] = "$type,$image_count,$download_count,\n";
      break;
    }
  }
  if ($find) {
    if ($_SERVER['HTTP_REFERER'] != LISTPAGE) {
      $fp = fopen(LOGFILE, "w");
      flock($fp, LOCK_EX);
      fputs($fp, implode("", $lines));
      flock($fp, LOCK_UN);
      fclose($fp);
    }
    header("Location: http://mozilla.jp/firefox/");
  }
  */
    header("Location: http://mozilla.jp/firefox/");
  exit;
}

function showSample()
{
  header("Content-Type: text/html");
?>
<html>
<head>
<title>Images</title>
</head>
<body>
<?
if ($handle = opendir("images")) {
  while (false !== $file = readdir($handle)) {
    if (fnmatch("*png",$file)) {
      $type = str_replace(".png", "", $file);
      $alt = "Firefox 2 無料ダウンロード";
      echo "<p><img src=\"http://".$_SERVER['SERVER_NAME']."/affiliates/images/$type\" alt=\"\" /></p>\n";
      $html = "<a href=\"http://".$_SERVER['SERVER_NAME']."/affiliates/$type\" title=\"$alt\">";
      $html .= "<img src=\"http://".$_SERVER['SERVER_NAME']."/affiliates/$type/image\" alt=\"$alt\" border=\"0\" /></a>";
      $textarea = "<p><textarea style=\"width:95%\" rows=\"5\" readonly=\"readonly\" onclick=\"this.select()\" onfocus=\"this.select()\">";
      $textarea .= htmlspecialchars($html)."</textarea></p>\n";
      echo $textarea;
    }
  }
  closedir($handle);
}
?>
</body>
</html>
<?
}

$i_output = htmlspecialchars(mb_convert_encoding($_GET['output'], 'UTF-8'));
switch($i_output) {
  case "admin":
    showAdminLogin();
    break;
  case "image":
    showImage();
    break;
  case "link":
    countDownload();
    break;
  case "sample":
    showSample();
    break;
  default:
    break;
}
?>
