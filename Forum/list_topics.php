<?php
//This page let display the list of topics of a category
include('config.php');
//check for parent id i.e. id of category
if(isset($_GET['parent']))
{
	//get parent id
	$id = intval($_GET['parent']);
	//fetch topics under that category
	$dn1 = mysql_fetch_array(mysql_query('select count(c.id) as nb1, c.name,count(t.id) as topics from categories as c left join topics as t on t.parent="'.$id.'" where c.id="'.$id.'" group by c.id'));
if($dn1['nb1']>0)
{
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?> - Forum</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
//check if user is logged in
if(isset($_SESSION['username']))
{
//check for unread messages
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no")) and id2="1"'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $id; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
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
    	<a href="<?php echo $url_home; ?>">Forum Index</a> &gt; <a href="list_topics.php?parent=<?php echo $id; ?>"><?php echo htmlentities($dn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
    </div>
	<div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
	<div class="clean"></div>
</div>
<?php
}
//display topics under category
$dn2 = mysql_query('select t.id, t.title, t.authorid, u.username as author, count(r.id) as replies from topics as t left join topics as r on r.parent="'.$id.'" and r.id=t.id and r.id2!=1  left join users as u on u.id=t.authorid where t.parent="'.$id.'" and t.id2=1 group by t.id order by t.timestamp desc');
if(mysql_num_rows($dn2)>0)
{
?>
<table class="topics_table">
	<tr>
    	<th class="forum_tops">Topic</th>
    	<th class="forum_auth">Author</th>
    	<th class="forum_nrep">Replies</th>
<?php
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
?>
    	<th class="forum_act">Action</th>
<?php
}
?>
	</tr>
<?php
while($dnn2 = mysql_fetch_array($dn2))
{
//diplay contents
?>
	<tr>
    	<td class="forum_tops"><a href="read_topic.php?id=<?php echo $dnn2['id']; ?>"><?php echo htmlentities($dnn2['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><a href="profile.php?id=<?php echo $dnn2['authorid']; ?>"><?php echo htmlentities($dnn2['author'], ENT_QUOTES, 'UTF-8'); ?></a></td>
    	<td><?php echo $dnn2['replies']; ?></td>
<?php
//check if admin is logged in
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
//id admin is logged in, show delete option
?>
    	<td><a href="delete_topic.php?id=<?php echo $dnn2['id']; ?>"><img src="<?php echo $design; ?>/images/delete.png" alt="Delete" /></a></td>
<?php
}
?>
    </tr>
<?php
}
?>
</table>
<?php
}
else
{
?>
<div class="message">This category has no topic.</div>
<?php
}
if(isset($_SESSION['username']))
{
//if user is logged in, show new topic option
?>
	<a href="new_topic.php?parent=<?php echo $id; ?>" class="button">New Topic</a>
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
		<div class="foot">&copy; Copyrights Reserved.</div>
	</body>
</html>
<?php
}
else
{
	echo '<h2>This category doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of the category you want to visit is not defined.</h2>';
}
?>
