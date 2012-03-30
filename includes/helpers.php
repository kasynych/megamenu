<?php
function getPagination($page,$cnt){
    $num_limit=2;
    $pages_cnt=ceil($cnt/ITEMS_PER_PAGE);
    $from=($page-1)*ITEMS_PER_PAGE+1;
    $to=$page<$pages_cnt?$page*ITEMS_PER_PAGE:$cnt;
    $num_from=($page<$num_limit+1)?1:$page-$num_limit;
    $num_to=$page<$pages_cnt-$num_limit?$page+$num_limit:$pages_cnt;

    $pagination='            <div class="pagging">
                <div class="left">Showing '.$from.'-'.$to.' of '.$cnt.'</div>

                <div class="right">';
    $pagination.='&nbsp;&nbsp;Jump to:<input type="text" id="jump_to_text" /><input type="button" id="jump_to_button" value="Go"/>';
    if($cnt<ITEMS_PER_PAGE)
        return $pagination.'</div></div>';
    if($page>1)
        $pagination.='<a href="?page=1">First</a><a href="?page='.($page-1).'">Previous</a>';

    for($i=$num_from;$i<$page;$i++)
        $pagination.='<a href="?page='.$i.'">'.$i.'</a>';
    $pagination.='<a href="?page='.$page.'" class="current">'.$page.'</a>';
    for($i=$page+1;$i<=$num_to;$i++)
        $pagination.='<a href="?page='.$i.'">'.$i.'</a>';
    if($page<$pages_cnt)
        $pagination.='<a href="?page='.($page+1).'">Next</a><a href="?page='.$pages_cnt.'">Last</a>';
    return $pagination.'</div></div>';
}

function getMenuTables(){
    $res=mysql_query('SHOW TABLES LIKE "'.TABLE_NAME.'%"');

    $tables=array();
    if(mysql_num_rows($res))
        while($row=mysql_fetch_array($res))
            $tables[]=$row[0];;
    natsort($tables);
    return $tables;
}