<?php
//This page let delete a post
include('config.php');
//check for id of topic and parent
if((isset($_GET['id'])) and (isset($_GET['id2'])))
{
	//get id of topic and parent
	$id = intval($_GET['id']);
	$id2 = intval($_GET['id2']);
//check if uesr is logged in
if(isset($_SESSION['username']))
{
	//fetch data of topic
	$dn1 = mysql_fetch_array(mysql_query('select count(t.id) as nb1, t.title, t.parent, c.name from topics as t, categories as c where t.id="'.$id.'" and t.id2="'.$id2.'" and c.id=t.parent group by t.id'));
if($dn1['nb1']>0)
{
	?>
	<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Delete a post - <?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
//check for messages of user
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Delete the Post
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
    <div class="clean"></div>
</div>
<?php
//delete a post if confirmed
if(isset($_POST['confirm']))
{
	//if it is first post, delete all the posts below it.
	if($id2 == 1)
	{
		if(mysql_query('delete from topics where id="'.$id.'" and parent="'.$dn1['parent'].'"'))
		{
			$parent = $dn1['parent'];
			header("Location:list_topics.php?parent=$parent");
		}
		else
		{
			echo 'An error occured while deleting the topic.';
		}
	}
	//else delete only dat post
	elseif(mysql_query('delete from topics where id="'.$id.'" and id2="'.$id2.'" and parent="'.$dn1['parent'].'"'))
	{
		header("Location:read_topic.php?id=$id");
		die();
	}
	//display error message if there is any error
	else
	{
		echo 'An error occured while deleting the topic.';
	}
}
else
{
//confirm deletion
?>
<form action="delete_post.php?id=<?php echo $id;?>&id2=<?php echo $id2;?>" method="post">
	Are you sure you want to delete this post?
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
	echo '<h2>The post you want to delete doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a href="login.php">Login</a> - <a href="signup.php">Sign Up</a></h2>';
}
}
else
{
	echo '<h2>The post you want to delete is not defined.</h2>';
}
?>
