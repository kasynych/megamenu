<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Lanos
 * Date: 29.03.12
 * Time: 9:57
 * To change this template use File | Settings | File Templates.
 */

require_once('includes/config.php');
require_once(INC_PATH.'helpers.php');
require_once(INC_PATH.'functions.php');

mysql_connect(DB_HOST,DB_USER,DB_PASS);
mysql_select_db(DB_DATABASE );