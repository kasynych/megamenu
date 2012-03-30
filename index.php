<?php
error_reporting(E_ERROR);
require_once 'includes/init.php';
$response=null;
$errors_exist=false;
$message=null;
if(isset($_POST['act'])){
    $response=addItem($_POST);
    if($response!==true) // if response is array of errors
        $errors_exist=true;
    else
        $message='Menu item was added';
}else{
    if(isset($_GET['act'])){
        switch($_GET['act']){
            case 'delete':
                if(deleteItem($_GET['table_name'],$_GET['item_id']))
                    $message="Item was deleted";
                else{
                    $errors_exist=true;
                    $error[]='Error deleting item';
                }
                break;
        }
    }
}
list($cnt,$items)=getItems();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Test Admin Panel</title>
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/functions.js"></script>
</head>
<body>
<!-- Header -->
<div id="header">
    <div class="shell">
        <!-- Logo + Top Nav -->

        <div id="top">
            <h1><a href="#">SpringTime</a></h1>
<!--            <div id="top-navigation">
                Welcome <a href="#"><strong>Administrator</strong></a>
                <span>|</span>
                <a href="#">Help</a>
                <span>|</span>
                <a href="#">Profile Settings</a>
                <span>|</span>
                <a href="#">Log out</a>
            </div>
-->        </div>
        <!-- End Logo + Top Nav -->

        <!-- Main Nav -->
<!--        <div id="navigation">
            <ul>
                <li><a href="#" class="active"><span>Dashboard</span></a></li>
                <li><a href="#"><span>New Articles</span></a></li>
                <li><a href="#"><span>User Management</span></a></li>
                <li><a href="#"><span>Photo Gallery</span></a></li>
                <li><a href="#"><span>Products</span></a></li>
                <li><a href="#"><span>Services Control</span></a></li>
            </ul>
        </div>
-->        <!-- End Main Nav -->
    </div>
</div>
<!-- End Header -->

<!-- Container -->
<div id="container">
<div class="shell">

<!-- Small Nav -->
<div class="small-nav">
    <a href="#">Dashboard</a>
    <span>&gt;</span>
    Menu management
</div>
<!-- End Small Nav -->

<!-- Message OK -->
<?php if(!is_null($message)){ ?>
<div class="msg msg-ok">
    <p><strong><?php echo $message ?></strong></p>
    <a href="#" class="close">close</a>
</div>
<?php }?>
<!-- End Message OK -->

<!-- Message Error -->
<?php if($errors_exist){ ?>
<div class="msg msg-error">
<p><strong>
<?php foreach($response as $error){?>
        <?php echo $error.'<br>' ?>
<?php }?>
</strong></p>
    <a href="#" class="close">close</a>
</div>
<?php }?>
<!-- End Message Error -->
<br />
<!-- Main -->
<div id="main">
<div class="cl">&nbsp;</div>

<!-- Content -->
<div id="content">

    <!-- Box -->
    <div class="box">
        <!-- Box Head -->
        <div class="box-head">
            <h2 class="left">Menu items</h2>
            <div class="right">
                <label>search items</label>
                <input type="text" class="field small-field" />
                <input type="submit" class="button" value="search" />
            </div>
        </div>
        <!-- End Box Head -->
<?php if(!empty($items)){?>
        <!-- Table -->
        <div class="table">
            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <th width="13">Parent</th>
                    <th width=220px>Name</th>
                    <th width=220px>Title</th>
                    <th>URL</th>
                    <th width="110" class="ac">Content Control</th>
                </tr>
<?php foreach($items as $index=>$item){?>
                <tr <?php echo ($index%2==0?'class="odd"':'') ?>>
                    <td><input type="radio" name="parent_id_radio" class="radio" value="<?php echo $item['item_id']; ?>" /></td>
                    <td><?php echo $item['name'] ?></td>
                    <td><?php echo $item['title'] ?></td>
                    <td><a href="<?php echo $item['url'] ?>" target="_blank"><?php echo $item['url'] ?></a></td>
                    <td><a href="?act=delete&table_name=<?php echo $item['table_name'] ?>&item_id=<?php echo $item['item_id']; ?>" class="ico del" onclick="return confirm('Are you sure?');">Delete</a><a href="#" class="ico edit">Edit</a></td>
                </tr>

<?php }?>
            </table>


            <!-- Pagging -->

             <?php echo getPagination($page,$cnt); ?>

            <!-- End Pagging -->

        </div>
        <!-- Table -->
<?php }else echo '<div style="width:100%;padding:10px 0px 10px 0px;text-align: center">empty result</div>';?>
    </div>
    <!-- End Box -->

    <!-- Box -->
    <div class="box">
        <!-- Box Head -->
        <div class="box-head">
            <h2>Add New Item</h2>
        </div>
        <!-- End Box Head -->

        <form action="" method="post">
            <input type="hidden" name="act" id="act" value="add" />
            <input type="hidden" name="parent_id" id="parent_id" value="0" />
            <!-- Form -->
            <div class="form">
                <p>
                    <span class="req">max 50 symbols</span>
                    <label>Userfriendly Item Name <span>(Required Field)</span></label>
                    <input type="text" class="field size1" name="name" id="name" value="<?php echo $_POST['name'] ?>" />
                </p>
                <p>
                    <span class="req">max 50 symbols</span>
                    <label>Item Title <span>(Required Field)</span></label>
                    <input type="text" class="field size1" name="title" id="title" value="<?php echo $_POST['title'] ?>" />
                </p>
                <p>
                    <span class="req">max 50 symbols</span>
                    <label>Item URL <span>(Required Field)</span></label>
                    <input type="text" class="field size1" name="url" id="url" value="<?php echo $_POST['url'] ?>" />
                </p>
            </div>
            <!-- End Form -->

            <!-- Form Buttons -->
            <div class="buttons">
                <input type="submit" class="button" value="submit" />
            </div>
            <!-- End Form Buttons -->
        </form>
    </div>
    <!-- End Box -->

</div>
<!-- End Content -->


<div class="cl">&nbsp;</div>
</div>
<!-- Main -->
</div>
</div>
<!-- End Container -->

<!-- Footer -->
<div id="footer">
    <div class="shell">
        <span class="left">&copy; 2010 - CompanyName</span>
		<span class="right">
			Design by <a href="http://chocotemplates.com" target="_blank" title="The Sweetest CSS Templates WorldWide">Chocotemplates.com</a>
		</span>
    </div>
</div>
<!-- End Footer -->

</body>
</html>