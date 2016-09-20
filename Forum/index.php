<?php
//This page displays the list of the forum's categories
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Forum</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Forum" /></a>
	    </div>
        <div class="content">
<?php
// check whether user is logged in
if(isset($_SESSION['username']))
{
//check for messages of user
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no"))'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> - <a href="users.php">List of Users</a>
    </div>
	<div class="box_right">
    	<a href="list_pm.php">Your messages(<?php echo $nb_new_pm; ?>)</a> - <a href="profile.php?id=<?php echo $_SESSION['userid']; ?>"><?php echo htmlentities($_SESSION['username'], ENT_QUOTES, 'UTF-8'); ?></a> (<a href="login.php">Logout</a>)
    </div>
	<div class="clean"></div>
<br/>
<!--- Display search form--->
<form name="search" method="post" action="search.php">
 Search for: <input type="text" name="find" /> in 
 <Select name="field" id="field">
 <Option value="users">Users</option>
 <Option value="topic">Topic</option>
 </Select>
 <input type="hidden" name="searching" value="yes" />
 <input type="submit" name="search" value="Search" />
 </form>
 <br/>
	
	<div class="clean"></div>
<!--- Display search date form--->
<form name="date" method="post" action="date.php">
<link rel="stylesheet" href="default/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom.css" />
  <script src="default/jquery-ui-1.10.2.custom/js/jquery-1.9.1.js"></script>
  <script src="default/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.js"></script>
  <link rel="stylesheet" href="default/style.css" />
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
  <a>Date: <input type="text" name="datepicker" id="datepicker" /></a>
  &nbsp;&nbsp;&nbsp;
  <input type="hidden" name="searching" value="yes" />
  <input type="submit" name="search" value="Search" />
  </form>
</div>
<?php
}
//user is not logged in
else
{
?>
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> - <a href="users.php">List of Users</a>
    </div>
    <div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
    <br/>
    <br/>
<!--- Display search form--->
 <form name="search" method="post" action="search.php">
 Search for: <input type="text" name="find" /> in 
 <Select name="field" id="field">
 <Option value="users">Users</option>
 <Option value="topic">Topic</option>
 </Select>
 <input type="hidden" name="searching" value="yes" />
 <input type="submit" name="search" value="Search" />
 </form>
 <br/>
	
	<div class="clean"></div>
<!--- Display search date form--->
<form name="date" method="post" action="date.php">
<link rel="stylesheet" href="default/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom.css" />
  <script src="default/jquery-ui-1.10.2.custom/js/jquery-1.9.1.js"></script>
  <script src="default/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.js"></script>
  <link rel="stylesheet" href="default/style.css" />
  <script>
  $(function() {
    $( "#datepicker" ).datepicker();
  });
  </script>
  <a>Date: <input type="text" name="datepicker" id="datepicker" /></a>
  &nbsp;&nbsp;&nbsp;
  <input type="hidden" name="searching" value="yes" />
  <input type="submit" name="search" value="Search" />
  </form>
</div>
<?php
}
?>
<table class="categories_table">
	<tr>
    	<th class="forum_cat">Category</th>
    	<th class="forum_ntop">Topics</th>
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
//fetch categories from table and display
$dn1 = mysql_query('select c.id, c.name, c.description, c.position, (select count(t.id) from topics as t where t.parent=c.id and t.id2=1) as topics, (select count(t2.id) from topics as t2 where t2.parent=c.id and t2.id2!=1) as replies from categories as c group by c.id order by c.position asc');
$nb_cats = mysql_num_rows($dn1);
while($dnn1 = mysql_fetch_array($dn1))
{
?>
	<tr>
    	<td class="forum_cat"><a href="list_topics.php?parent=<?php echo $dnn1['id']; ?>" class="title"><?php echo htmlentities($dnn1['name'], ENT_QUOTES, 'UTF-8'); ?></a>
        <div class="description"><?php echo $dnn1['description']; ?></div></td>
    	<td><?php echo $dnn1['topics']; ?></td>
    	<td><?php echo $dnn1['replies']; ?></td>
<?php
//check if admin is logged in
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
//if admin is logged in display move, delete and edit options
?>
    	<td><a href="delete_category.php?id=<?php echo $dnn1['id']; ?>"><img src="<?php echo $design; ?>/images/delete.png" alt="Delete" /></a>
		<?php if($dnn1['position']>1){ ?><a href="move_category.php?action=up&id=<?php echo $dnn1['id']; ?>"><img src="<?php echo $design; ?>/images/up.png" alt="Move Up" /></a><?php } ?>
		<?php if($dnn1['position']<$nb_cats){ ?><a href="move_category.php?action=down&id=<?php echo $dnn1['id']; ?>"><img src="<?php echo $design; ?>/images/down.png" alt="Move Down" /></a><?php } ?>
		<a href="edit_category.php?id=<?php echo $dnn1['id']; ?>"><img src="<?php echo $design; ?>/images/edit.png" alt="Edit" /></a></td>
<?php
}
?>
    </tr>
<?php
}
?>
</table>
<?php
//check if admin is logged in
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
//if admin is logged in display new category option
?>
	<a href="new_category.php" class="button">New Category</a>
<?php
}
//if user is not logged in, show log in form
if(!isset($_SESSION['username']))
{
?>
<div class="box_login">
	<form action="login.php" method="post">
		<label for="username">Username</label><input type="text" name="username" placeholder="username" maxlength=20 id="username" /><br />
		<label for="password">Password</label><input type="password" name="password" placeholder="password" maxlength=20 id="password" /><br /><br/>
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
