<?php if(!defined('HDPHP_PATH'))EXIT;
$db->exe("REPLACE INTO ".$db_prefix."content_single (`aid`,`title`,`new_window`,`thumb`,`click`,`source`,`redirecturl`,`allowreply`,`author`,`addtime`,`updatetime`,`color`,`template`,`ishtml`,`arc_sort`,`state`,`cid`,`seo_title`,`keywords`,`description`,`content`,`html_path`,`uid`) VALUES('1','a','0','','100','','','1','admin','1390690118','1390690115','','{style}/article_single.html','1','100','1','0','','','','<p>a<br/></p>','single/{aid}.html','1')");
