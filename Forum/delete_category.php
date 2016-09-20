<?php
//This page allows admin to delete a category
include('config.php');
//check the id is set
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	//fetch data of category from database
	$dn1 = mysql_fetch_array(mysql_query('select count(id) as nb1, name, position from categories where id="'.$id.'" group by id'));
	if($dn1['nb1']>0)
	{
		//check if admin is logged in
		if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
		{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Delete a category - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
//check for new messages of admin
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> &gt; Delete the category
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
    <div class="clean"></div>
</div>
<?php
//confirm to delete a category
if(isset($_POST['confirm']))
{
	//delete the category and all posts under that category
	if(mysql_query('delete from categories where id="'.$id.'"') and mysql_query('delete from topics where parent="'.$id.'"') and mysql_query('update categories set position=position-1 where position>"'.$dn1['position'].'"'))
	{
		header("Location:index.php");
		die();
	}
	//if database query fails display error
	else
	{
		echo 'An error occured while deleting the category and it topics.';
	}
}
else
{
//confirm deletion
?>
<form action="delete_category.php?id=<?php echo $id; ?>" method="post">
	Are you sure you want to delete this category and all it topics?
    <input type="hidden" name="confirm" value="true" />
    <input type="submit" value="Yes" /> <input type="button" value="No" onclick="javascript:history.go(-1);" />
</form>
<?php
}
?>
		</div>
		<div class="foot">&copy; Copyrights Reserved.</div>
	</body>
</html>
<?php
}
//if user is not administrator
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a href="login.php">Login</a> - <a href="signup.php">Sign Up</a></h2>';
}
}
//if category is not in database
else
{
	echo '<h2>The category you want to delete doesn\'t exist.</h2>';
}
}
//if id is not set
else
{
	echo '<h2>The category you want to delete is not defined.</h2>';
}
?>
