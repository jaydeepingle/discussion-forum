<?php
//This page let users create new topics
include('config.php');
//check for parent id of topic
if(isset($_GET['parent']))
{
	//get parent id of topic
	$id = intval($_GET['parent']);
//check if user is logged in
if(isset($_SESSION['username']))
{
	$dn1 = mysql_fetch_array(mysql_query('select count(c.id) as nb1, c.name from categories as c where c.id="'.$id.'"'));
if($dn1['nb1']>0)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>New Topic - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
		<script type="text/javascript" src="functions.js"></script>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
//check for unread messages of user
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $id; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; New Topic
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
	<div class="clean"></div>
</div>
<?php
//check if all fields are set
if(isset($_POST['message'], $_POST['title']) and $_POST['message']!='' and $_POST['title']!='')
{
	include('bbcode_function.php');
	$title = $_POST['title'];
	$message = $_POST['message'];
	if(get_magic_quotes_gpc())
	{
		$title = stripslashes($title);
		$message = stripslashes($message);
	}
	$title = mysql_real_escape_string($title);
	$message = mysql_real_escape_string(bbcode_to_html($message));
	//make an entry in the database
	if(mysql_query('insert into topics (parent, id, id2, title, message, authorid) select "'.$id.'", ifnull(max(id), 0)+1, "1", "'.$title.'", "'.$message.'", "'.$_SESSION['userid'].'" from topics'))
	{
			header("Location:list_topics.php?parent=$id");
			die();
	}
	else
	{
		echo 'An error occurred while creating the topic.';
	}
}
else
{
//display form to enter the data
?>
<form action="new_topic.php?parent=<?php echo $id; ?>" method="post">
	<label for="title">Title</label><input type="text" name="title" id="title"  /><br />
    <label for="message">Message</label><br />
    <div class="message_buttons">
        <input type="button" value="Bold" onclick="javascript:insert('[b]', '[/b]', 'message');" /><!--
        --><input type="button" value="Italic" onclick="javascript:insert('[i]', '[/i]', 'message');" /><!--
        --><input type="button" value="Underlined" onclick="javascript:insert('[u]', '[/u]', 'message');" /><!--
        --><input type="button" value="Image" onclick="javascript:insert('[img]', '[/img]', 'message');" /><!--
        --><input type="button" value="Link" onclick="javascript:insert('[url]', '[/url]', 'message');" /><!--
        --><input type="button" value="Left" onclick="javascript:insert('[left]', '[/left]', 'message');" /><!--
        --><input type="button" value="Center" onclick="javascript:insert('[center]', '[/center]', 'message');" /><!--
        --><input type="button" value="Right" onclick="javascript:insert('[right]', '[/right]', 'message');" />
    </div>
    <textarea name="message" id="message" cols="70" rows="6"></textarea><br />
    <input type="submit" value="Send" />
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
	echo '<h2>The category you want to add a topic doesn\'t exist.</h2>';
}
}
else
{
//if user is not logged in display login form
?>
<h2>You must be logged to access this page.</h2>
<div class="box_login">
	<form action="login.php" method="post">
		<label for="username">Username</label><input type="text" name="username" maxlength=20 placeholder="username" id="username" /><br />
		<label for="password">Password</label><input type="password" name="password" maxlength=20 placeholder="password" id="password" /><br />
        <div class="center">
	        <input type="submit" value="Login" /> <input type="button" onclick="javascript:document.location='signup.php';" value="Sign Up" />
        </div>
    </form>
</div>
<?php
}
}
else
{
	echo '<h2>The ID of the category you want to add a topic is not defined.</h2>';
}
?>
