<?php
    function getItems(){
        global $page;
        if(isset($_GET['page'])) $page=$_GET['page'];
        else $page=1;
        $cnt=getItemsCnt();

        if((($page-1)*ITEMS_PER_PAGE)>=$cnt) return false;

        $table_index=floor(((($page-1)*ITEMS_PER_PAGE)/MAX_ITEMS_PER_TABLE))+1; // from which table to select?
        if(isset($_GET['page'])) $page=$_GET['page'];
        else $page=1;

        $res=mysql_query('SELECT *, "'.TABLE_NAME.$table_index.'" as table_name FROM `'.TABLE_NAME.$table_index.'`

                                   LIMIT '.(($page-1)*ITEMS_PER_PAGE-(($table_index-1)*MAX_ITEMS_PER_TABLE)).', '.ITEMS_PER_PAGE);
        // if not enough records were selected from the table the add records from next table
        if(mysql_num_rows($res)<ITEMS_PER_PAGE&&$table_index<ceil($cnt/MAX_ITEMS_PER_TABLE)){
            while($row=mysql_fetch_assoc($res))
                $rec=$row;
            $num_rows=mysql_num_rows($res);
            $res=mysql_query('(SELECT *,"'.TABLE_NAME.$table_index.'" as table_name FROM `'.TABLE_NAME.$table_index.'`

                                   LIMIT '.(($page-1)*ITEMS_PER_PAGE-(($table_index-1)*MAX_ITEMS_PER_TABLE)).', '.ITEMS_PER_PAGE.')
                                   UNION
                            (SELECT *,"'.TABLE_NAME.($table_index+1).'" as table_name FROM `'.TABLE_NAME.($table_index+1).'` i2
                               WHERE i2.title>"'.$rec['title'].'"
                               LIMIT 0, '.$num_rows.')');
        }

        $items=array();

        while($row=mysql_fetch_assoc($res)){
            $items[]=$row;
        }

        return array($cnt,$items);
    }

    function addItem($data){
        require_once(INC_PATH.'validators.php');
        $errors=array();

        if(!min_length($data['name'],4))
            $errors[]='Name is to short';
        elseif(!alpha_numeric($data['name'])){
            $errors[]='Name should be alphanumeric';
        }
        if(!max_length($data['name'],50))
            $errors[]='Name is to long';

        if(!min_length($data['title'],4))
            $errors[]='Title is to short';
        elseif(!alpha_numeric($data['title'])){
            $errors[]='Title should be alphanumeric';
        }
        if(!max_length($data['title'],50))
            $errors[]='Title is to long';

        if(!is_valid_url($data['url'])){
            $errors[]='URL has wrong format';
        }

        if(!empty($errors)) return $errors;
        else{
            $res=mysql_query('INSERT INTO '.TABLE_NAME.' SET parent_id="'.$_POST['parent_id'].'",
                                                    name="'.$_POST['name'].'",
                                                    title="'.$_POST['title'].'",
                                                    url="'.$_POST['url'].'"');
            return $res!==false;
        }
    }

    function editItem(){
        require_once(INC_PATH.'validators.php');
    }

    function deleteItem($table_name,$item_id){
        preg_match('/[0-9]{1,4}/',$table_name,$matches);
        $table_index=$matches[0];
        /*
         * 1. delete item from table
         * 2. insert last item from last table istead
         * 3. delete last item from last table
         * 4. update child items of replaced item
         * 5. if number of items in last table is zero drop that table
         * The aim is to keep all table with full number of rows, except the last one
         */
        $tables=getMenuTables();
        $last_table_index=count($tables);
        // 1
        mysql_query('DELETE FROM `'.$table_name.'` WHERE `item_id`="'.$item_id.'"');
        foreach($tables as $table)
            mysql_query('UPDATE `'.$table.'` SET parent_id=0,parent_table_name="" WHERE parent_id="'.$item_id.'" AND parent_table_name="'.$table_name.'"');
        if(mysql_error()!='') return false;
        if($table_name!=(TABLE_NAME.$last_table_index)){ // getting the last item from the last table
            $res=mysql_query('SELECT * FROM `'.TABLE_NAME.$last_table_index.'` WHERE 1 ORDER BY item_id DESC LIMIT 1');
            $last_item=mysql_fetch_assoc($res);



            // 2
            mysql_query('INSERT INTO `'.$table_name.'` SET
                                                        `unique_id`="'.$last_item['unique_id'].'",
                                                        `parent_id`="'.$last_item['parent_id'].'",
                                                        `parent_table_name`="'.$last_item['parent_table_name'].'",
                                                        `name`="'.$last_item['name'].'",
                                                        `title`="'.$last_item['title'].'",
                                                        `url`="'.$last_item['url'].'"');
            $res=mysql_query('SELECT LAST_INSERT_ID()');
            $last_insert_id_row=mysql_fetch_array($res);
            $last_insert_id=$last_insert_id_row[0];
            if(mysql_error()!='') return false;

            // 3
            mysql_query('DELETE FROM `'.TABLE_NAME.$last_table_index.'` WHERE `item_id`="'.$last_item['item_id'].'"');
            if(mysql_error()!='') return false;

            // 4
            foreach($tables as $table){
                mysql_query('UPDATE `'.$table.'` SET `parent_table_name`="'.$table_name.'",
                                                     `parent_id`="'.$last_insert_id.'"
                                                 WHERE `parent_table_name`="'.TABLE_NAME.$last_table_index.'"
                                                   AND `parent_id`="'.$last_item['item_id'].'"');
                if(mysql_error()!='') return false;
            }

            // 5
            $res=mysql_query('SELECT count(*) FROM `'.TABLE_NAME.$last_table_index.'`');
            if(mysql_num_rows($res)==0)
                mysql_query('DROP TABLE `'.TABLE_NAME.$last_table_index.'`');
        }
        return true;
    }

    function getItem(){

    }

    function getItemLevel(){

    }

    function getItemCategoryTree(){

    }

    function getItemsCnt(){
        $tables=getMenuTables();
        $cnt=0;
        foreach($tables as $table){
            $res=mysql_query('SELECT COUNT(*) as cnt FROM `'.$table.'`');
            $cnt_row=mysql_fetch_assoc($res);
            $cnt+=$cnt_row['cnt'];
        }

        return $cnt;
    }

?>