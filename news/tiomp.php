<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

// navigate to https://tiomp.vstu.by/category/news/
$browser->navigate("https://tiomp.vstu.by/category/news/");

$path_news="./res/tiomp.txt";
$file_os->delete($path_news);

require_once("functions.php");
$dbg=true;
debug_mess(date("\[ d.m.y H:i:s\] ")." ������ ���������");

$res_path="/res/";
$site = "https://tiomp.vstu.by/category/news/";


for ($i=35;$i<41;$i++)
	{
	$news=$textfile->read_file($res_path."tiomp.txt",5) ;
	$inf = $anchor->get_inner_text_by_number($i);
	$textfile->add_string_to_file($res_path."tiomp.txt","�� ����� ��� ���������� � ���� :",5);
	$textfile->add_string_to_file($res_path."tiomp.txt"," ".$inf."\n",5);
	}		

// Quit
debug_mess(date("\[ d.m.y H:i:s\] ")." ������ �������� ������<br>");
$app->quit();
?>