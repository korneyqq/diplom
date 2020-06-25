<?php

// ссылка на страницу и информация по ней
class PageLinks
{
	// страница
	var $page;
	// информация по ней
	var $link_info;
}

// проверить строку на фильтр
function check_filter($page)
{
	global $filter;
	
	if($filter=="")
	 return true;
	
	// разобрать фильтры в массив
	$a_flt=explode(",",$filter);
	
   for($i=0;$i<count($a_flt);$i++)
   {
		if(strpos($page,$a_flt[$i]))
			return false;
   }
  
   return true;
}

// проверить текущюю страницу заданный текст
function check_page_404($str)
{
   global $error_404,$webpage,$path_bad_links,$textfile;

   if(!strpos($webpage->get_body(),$error_404))
      return false;
   
   // добавить ссылку во временный файл
   $textfile->add_string_to_file($path_bad_links,trim($str)."\n",60) ;
     
  return true;
}
// проверить есть ли в массиве страница
function is_a_exists($page)
{
   global $a_links;

   if(count($a_links)<=1)
       return false;

   // пройдёмся по массиву и проверим есть ли заданный элемент
   for($i=0;$i<count($a_links);$i++)
   {
       if($a_links[$i]->page==$page)
         return true;  
   }

   return false;
}
// получить часть строки разделёной ;
function get_string_value($str,$num)
{
    if($str=="")
       return false;
    // разобрать в массив
    $a_str=explode(";",$str);

    if(count($a_str)<=1)
       return false;

   // пройдёмся по всем элементам
   for($i=0;$i<count($a_str);$i++)
   {
        if($i==$num)
             return trim($a_str[$i]);
   }
    
 return false;
}
// get string by prefix
function get_string($str1, $pr1, $pr2, $ind_st=0)
{
     //echo $str1."<br>";
     $ind1 = strpos ($str1,$pr1,$ind_st);
    // echo "index 1 ".$ind1."<br>";
    if($ind1===false)
      return "";
     
     $ind2 = strpos ($str1,$pr2,$ind1);
    //echo "index 2 ".$ind2."<br>";
     if($ind2===false)
        return "";

     $sres = substr($str1,$ind1+strlen($pr1), $ind2-$ind1-strlen($pr1));
     //echo $sres; 

    return trim($sres); 
}

// next page
function next_page($url="")
{
      global $anchor, $browser, $app,$crnt_page,$cnt_pages;
     
      if($url!="")
      {
            $browser->navigate($url);
            // wait on browser
            $browser->wait_for();
      }
      // count of pages
      $crnt_page=$crnt_page+1;
      if($cnt_pages!=-1)
      {
            // stop script
            if($crnt_page>$cnt_pages)
            { 
              debug_mess("get from all pages ".($crnt_page-1));
              return false;
            }
      }
     
      sleep(1);

      // go to next page
      if(!$anchor->click_by_inner_text($crnt_page,true))
      {
           debug_mess("get from all pages ".($crnt_page-1));
           return false;
      }
       debug_mess("<b>go to page number $crnt_page</b>");
       // wait on browser
      $browser->wait_for();
      return true;
}

// show message in debug panel
function  debug_mess($mess)
{
   global $dbg;
   // debug message
   if($dbg)
      echo $mess."<br>";
}
?>