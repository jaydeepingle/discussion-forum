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
//check if user is logged in
if(isset($_SESSION['username']))
{
//if yes count number of messages
$nb_new_pm = mysql_fetch_array(mysql_query('select count(*) as nb_new_pm from pm where ((user1="'.$_SESSION['userid'].'" and user1read="no") or (user2="'.$_SESSION['userid'].'" and user2read="no"))'));
$nb_new_pm = $nb_new_pm['nb_new_pm'];
//as user is logged in display messages
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
<!---Search form --->
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

<!---Search by Date form --->
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
else
{
?>
	<div class="box">
        <div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a>
    </div>
    <div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
	</div>
	<div class="clean"></div>
<br/>
<!---Search form --->
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
<!---Search by Date form --->
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
 //This is only displayed if they have submitted the form 
 $searching = $_POST['searching'];
 if ($searching =="yes") 
 { 
 echo "<h2>Results</h2><p>"; 
 //If they did not enter a search term we give them an error 
 $find = $_POST['find'];
 if ($find == "") 
 { 
 	echo "<p>You forgot to enter a search term"; 
 	die(); 
 } 
  
  $field = $_POST['field'];
 // We preform a bit of filtering 
 $find = strtoupper($find); 
 $find = strip_tags($find); 
 $find = trim ($find); 
 
 //check if searched for user
 if($field == 'users')
 {
	//fetch data of the user
 	$data = mysql_query('SELECT * FROM users WHERE upper(username)="'.$find.'"');
	 $anymatches = mysql_num_rows($data);
	if( $data == NULL || $anymatches == 0)
 {
 	echo "Sorry, but we can not find an entry to match your query<br><br>"; 
	}
 else
 {
 //And we display the results
 session_start();
 $result = mysql_fetch_array($data);
 	$_SESSION['id'] = $result['id'];
	 header("Location:profile1.php");
	 die();
	}
}
//check if searched for topic
else if($field == 'topic')
{
	//fetch data related to topic
	$data = mysql_query("SELECT * FROM topics WHERE id2=1 and title LIKE'%$find%'");
	$anymatches = mysql_num_rows($data);
	if( $data == NULL || $anymatches == 0)
 	{
 		echo "Sorry, but we can not find an entry to match your query<br><br>"; 
	}
	else
	{
		//display information about topic
		while($result = mysql_fetch_array($data))
		{
			$data2 = mysql_query('SELECT * FROM categories WHERE id="'.$result['parent'].'"');
			$result2 = mysql_fetch_array($data2);
?>
		<table class="search_table">
		<tr>
    	<th class="forum_cat">Category</th>
    	<th class="forum_search">Searched Topic</th>
	</tr>
	<tr>
    	<td class="forum_cat"><a href="list_topics.php?parent=<?php echo $result['parent']; ?>" class="title"><?php echo htmlentities($result2['name'], ENT_QUOTES, 'UTF-8'); ?></a>
        <div class="description"><?php echo $result2['description']; ?></div></td>
        <td class="forum_search"><a href="read_topic.php?id=<?php echo $result['id']; ?>" class="title"><?php echo htmlentities($result['title'], ENT_QUOTES, 'UTF-8'); ?></a></td>
        </tr>
        </table>
        
        
        
<?php        
	}
}
}
}
 ?>
 </div>
		<div class="foot">&copy; Copyrights Reserved.</div>
	</body>
</html>
