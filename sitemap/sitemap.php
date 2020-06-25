<?php

$xhe_host ="127.0.0.1:7012";

// The following code is required to properly run XWeb Human Emulator
require("../../Templates/xweb_human_emulator.php");

// ////////// ��������� �������///////////////////
// ����������� ����
$site="https://isap.vstu.by/";
// ������� �������� �����
$main_page=$site."index.php";
// ���� � ������� ��� �������� ����� �����
$temp_path="/data/template.php";
// ����� � ������������
$res_path="/res/";

// ����������� ������ 
$error_404="ERROR 404 NOT FOUND";

// ������ �� ������ � href
// �������� ����� , 
// ����� ������ �� ������������
$filter="/forum/,/images/,#,.jpg,.mp4,sitemap,.pdf";
// ����� �������
$dbg=true;

// /////////////////// �������������� ������ /////////////////////
// ������� 
require_once("functions.php");

// ///////////////////// script /////////////////////////////////////////////////////////
debug_mess(date("\[ d.m.y H:i:s\] ")." ������ ���������");

// ������ ���������� ������
$file_os->delete($res_path."sitemap.php");
$file_os->delete($res_path."sitemap.xml");
$file_os->delete($res_path."tmp.txt");

// ������� sitemap.xml
create_xml($main_page);
// ������� ������� �������� � ������
$pgl=new PageLinks();
$pgl->page=$main_page;
$pgl->link_info=$main_page;
$a_links=array($pgl);
// �������� � ��������� 
for($k=0;$k<count($a_links);$k++)
{
    // �������� ��������
    $pg=trim($a_links[$k]->page);

      // ��������� �������
    if(!check_filter($pg)) 
       continue;
  
    // ��������� �� ����
    $browser->navigate($pg);

    // ��������� �� 404
    if(check_page_404($pg))
       continue;

    // ������� � sitemap.xml 
    if($k>0)
    {
       add_to_xml($pg);
       // ������� �� ��������� ����
		$textfile->add_string_to_file($res_path."tmp.txt",$a_links[$k]->link_info."\n",60) ;
    }
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
       
        // ���� ��� �� ��������� ��������
        // ������� ����������� ��������
        if(!is_a_exists($pg_href))
        {
            // ������ ��� ���������� �����
            $pg_href1=str_replace($site,"",$pg_href);
				// ������ ��� ������ �� ��������� ����
				$in_txt=$anchor->get_inner_text_by_href($pg_href1,false);
				$str="";
				// ���� ��� ������ ������ ���� href 
				if($in_txt=="")
					$str="<a href=\"/$pg_href1\">$pg_href</a><br>";
				else
					$str="<a href=\"/$pg_href1\">$in_txt</a><br>";
				
				$pgl=new PageLinks();
				$pgl->page=$pg_href;
				$pgl->link_info=$str;
				$a_links[]=$pgl;   
        }
	}
}

// ������� � ���� ����������� ���
$textfile->add_string_to_file($res_path."sitemap.xml","\n</urlset>\n",60);

// �������� sitemap.php ���� 
$links=$textfile->read_file($res_path."tmp.txt",60) ;
$templ =$textfile->read_file($temp_path,60);
$templ=str_replace("{SITE_MAP}",$links,$templ);
// ������� ����
$textfile->write_file($res_path."sitemap.php",$templ,60) ;

debug_mess(date("\[ d.m.y H:i:s\] ")." ������ �������� ������<br>");

// Quit
$app->quit();
?>