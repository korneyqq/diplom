<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

//��������� �������
// ����������� ����
$site="https://isap.vstu.by/";
// ���� � ����� � ������� �������
$path_bad_links="/res/bad_links.txt";
// ����������� ������ 
$error_404="Error 404: File Not Found";
// ������ �� ������ � href
// �������� ����� ,
$filter="forum,doc,jpg,png,rar";

// ����� �������
$dbg=true;

// �������������� ������
// ������� 
require_once("functions.php");

// ������ �������
debug_mess(date("\[ m.d.y H:i:s\] ")." C����� ���������");

// ������� ������� �������� � ������
$pgl=new PageLinks();
$pgl->page=$site;
$pgl->link_info=$site;
$a_links=array($pgl);

// ������ ������
$file_os->delete($path_bad_links);

// �������� � ��������� 
for($k=0;$k<count($a_links);$k++)
{
    // �������� ��������
    $pg=$a_links[$k]->page;
      // ��������� �������
    if(!check_filter($pg))
       continue;
    
    // ��������� �� ����
    $browser->navigate($pg);
    // ��������� �� 404
    if(check_page_404($a_links[$k]->link_info))
       continue;

	 // ������� ��� href-� �� ��������
	$hrefs=$anchor->get_all_hrefs();
	// ����������� � ������
	$hrefs=explode("<br>",$hrefs);

    // �������� �� ���� hrefs � ������ ������
	for($ii=0; $ii<count($hrefs); $ii++)
	{
        $pg_href=trim($hrefs[$ii]);
		  // ��������� ���������� �� ������
		  if(strpos($pg_href,$site)===false)
					continue;

        // ������ ��� ���������� �����
        $pg_href1=str_replace($site,"",$pg_href);
        // ������ ��� ������� � ��
        $str="$pg;$pg_href;".$anchor->get_inner_text_by_href($pg_href1,false);
        // ���� ��� �� ��������� ��������
        // ������� ����������� ��������
        if(!is_a_exists($pg_href))
        {
			 $pgl=new PageLinks();
			 $pgl->page=$pg_href;
			 $pgl->link_info=$str;
			 $a_links[]=$pgl;   
        }
	}
}
debug_mess(date("\[ d.m.y H:i:s\] ")." C����� �������� ������<br>");

// Quit
$app->quit();
?>