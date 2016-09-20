<?php
//This page allows admin to delete a topic
include('config.php');
//check if id of topic is set
if(isset($_GET['id']))
{
	//get id of topic
	$id = intval($_GET['id']);
if(isset($_SESSION['username']))
{
	//fetch data about topic
	$dn1 = mysql_fetch_array(mysql_query('select count(t.id) as nb1, t.title, t.parent, c.name from topics as t, categories as c where t.id="'.$id.'" and t.id2=1 and c.id=t.parent group by t.id'));
if($dn1['nb1']>0)
{
	//check if the admin is logged in
if($_SESSION['username']==$admin)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Delete a topic - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
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
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Delete the topic
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
    <div class="clean"></div>
</div>
<?php
//delete a topic if confirmed
if(isset($_POST['confirm']))
{
	//delete the topic and go to page of topic list
	if(mysql_query('delete from topics where id="'.$id.'"'))
	{
			$parent = $dn1['parent'];
			header("Location:list_topics.php?parent=$parent");
	}
	else
	{
		echo 'An error occured while deleting the topic.';
	}
}
else
{
//confirm deletion
?>
<form action="delete_topic.php?id=<?php echo $id; ?>" method="post">
	Are you sure you want to delete this topic?
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
else
{
	echo '<h2>You don\'t have the right to delete this topic.</h2>';
}
}
else
{
	echo '<h2>The topic you want to delete doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a href="login.php">Login</a> - <a href="signup.php">Sign Up</a></h2>';
}
}
else
{
	echo '<h2>The topic you want to delete is not defined.</h2>';
}
?>
