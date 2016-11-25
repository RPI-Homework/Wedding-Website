<?php
if(strtolower($_POST['bot']) == 'salomone')
{
if(strlen($_POST['message']) > 0)
{
$handle = fopen("./pages/guestbook.txt", "r");

$file = array();
while($line = fread($handle, 8192))
{
$file[] = $line;
}

fclose($handle);

$handle = fopen("./pages/guestbook.txt", "w");
if(strlen($_POST['name']) == 0)
{
$_POST['name'] = "Anonymous";
}
if(strlen($_POST['email']) > 0 && eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $_POST['email']))
{
fwrite($handle, "<h2><a href=\"mailo:" . $_POST['email'] . "\">" . htmlspecialchars($_POST['name']) . "</a></h2>\r\n");
}
else
{
fwrite($handle, "<h2>" . htmlspecialchars($_POST['name']) . "</h2>\r\n");
}
fwrite($handle, "Date: " . date("l j F Y g:i A") . "<br />\r\n");
fwrite($handle, nl2br(htmlspecialchars($_POST['message'])));
fwrite($handle, "<br />\r\n<br />\r\n");
foreach($file as $val)
{
fwrite($handle, $val);
}
fclose($handle);
}
}
header('Location: index.php?idx=guestbook');
?>