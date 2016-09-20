<?php
//This page display a topic
include('config.php');
//check for id
if(isset($_GET['id']))
{
	$id = intval($_GET['id']);
	//fetch data related to the topic
	$dn1 = mysql_fetch_array(mysql_query('select count(t.id) as nb1, t.title, t.parent, count(t2.id) as nb2, c.name from topics as t, topics as t2, categories as c where t.id="'.$id.'" and t.id2=1 and t2.id="'.$id.'" and c.id=t.parent group by t.id'));
if($dn1['nb1']>0)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?> - <?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
//check if user is logge in
if(isset($_SESSION['username']))
{
//check unread messages of user
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Read the topic
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
    <div class="clean"></div>
</div>
<?php
}
else
{
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $dn1['parent']; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; <a href="read_topic.php?id=<?php echo $id; ?>"><?php echo htmlentities($dn1['title'], ENT_QUOTES, 'UTF-8'); ?></a> &gt; Read the topic
    </div>
	<div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
    <div class="clean"></div>
</div>
<?php
}
?>
<h1><?php echo $dn1['title']; ?></h1>
<?php
//fetch data related to topic
$dn2 = mysql_query('select t.id, t.id2, t.authorid, t.message, t.timestamp, u.username as author from topics as t, users as u where t.id="'.$id.'" and u.id=t.authorid order by t.timestamp asc');
?>
<table class="messages_table">
	<tr>
    	<th class="author">Author</th>
    	<th>Message</th>
	</tr>
<?php
//display data
while($dnn2 = mysql_fetch_array($dn2))
{
?>
	<tr>
    	<td class="author center"><?php
?><br /><a href="profile.php?id=<?php echo $dnn2['authorid']; ?>"><?php echo $dnn2['author']; ?></a></td>
    	<td class="left"><?php if(isset($_SESSION['username']) and ($_SESSION['username']==$dnn2['author'] or $_SESSION['username']==$admin)){ ?><div class="edit"><a href="edit_post.php?id=<?php echo $id; ?>&id2=<?php echo $dnn2['id2']; ?>"><img src="<?php echo $design; ?>/images/edit.png" alt="Edit" /></a></div>
		<div class="edit"><a href="delete_post.php?id=<?php echo $id; ?>&id2=<?php echo $dnn2['id2']; ?>"><img src="<?php echo $design; ?>/images/delete.png" alt="Edit" /></a></div>
		<?php } ?><div class="date">Date sent: <?php echo $dnn2['timestamp']; ?></div>
        <div class="clean"></div>
    	<?php echo $dnn2['message']; ?></td>

    </tr>
<?php
}
?>
</table>
<?php
//check if user is logged in
if(isset($_SESSION['username']))
{
	//display reply option
?>
	<a href="new_reply.php?id=<?php echo $id; ?>" class="button">Reply</a>
<?php
}
else
{
//if user is not logged in
?>
<div class="box_login">
	<form action="login.php" method="post">
		<label for="username">Username</label><input type="text" name="username" placeholder="username" maxlength=20 id="username" /><br />
		<label for="password">Password</label><input type="password" name="password" placeholder="password" maxlength=20 id="password" /><br/>
		<br/>
        <div class="center">
	        <input type="submit" value="Login" /> <input type="button" onclick="javascript:document.location='signup.php';" value="Sign Up" />
        </div>
    </form>
</div>
<?php
}
?>
		</div>
		<div class="foot">&copy;Copyrights Reserved.</div>
	</body>
</html>
<?php
}
else
{
	echo '<h2>This topic doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of this topic is not defined.</h2>';
}
?>
