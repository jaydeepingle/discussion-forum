<?php
//This page let move a category
include('config.php');
//check if right parameters ara set
if(isset($_GET['id'], $_GET['action']) and ($_GET['action']=='up' or $_GET['action']=='down'))
{
//get values of parameters
$id = intval($_GET['id']);
$action = $_GET['action'];
//fetch data from categories 
$dn1 = mysql_fetch_array(mysql_query('select count(c.id) as nb1, c.position, count(c2.id) as nb2 from categories as c, categories as c2 where c.id="'.$id.'" group by c.id'));
if($dn1['nb1']>0)
{
//check if user is logged in
if(isset($_SESSION['username']) and $_SESSION['username']==$admin)
{
	//check if action is up
	if($action=='up')
	{
		//check if position is not first
		if($dn1['position']>1)
		{
			//update position in database
			if(mysql_query('update categories as c, categories as c2 set c.position=c.position-1, c2.position=c2.position+1 where c.id="'.$id.'" and c2.position=c.position-1'))
			{
				header('Location: '.$url_home);
			}
			else
			{
				echo 'An error occured while moving the category.';
			}
		}
		else
		{
			echo '<h2>The action you want to do is impossible.</h2>';
		}
	}
	//if action is down
	else
	{
		//check if position is not last
		if($dn1['position']<$dn1['nb2'])
		{
			//update position in database
			if(mysql_query('update categories as c, categories as c2 set c.position=c.position+1, c2.position=c2.position-1 where c.id="'.$id.'" and c2.position=c.position+1'))
			{
				header('Location: '.$url_home);
			}
			else
			{
				echo 'An error occured while moving the category.';
			}
		}
		else
		{
			echo '<h2>The action you want to do is impossible.</h2>';
		}
	}
}
else
{
	echo '<h2>You must be logged as an administrator to access this page: <a href="login.php">Login</a> - <a href="signup.php">Sign Up</a></h2>';
}
}
else
{
	echo '<h2>The category you want to move doesn\'t exist.</h2>';
}
}
else
{
	echo '<h2>The ID of the category you want to move is not defined.</h2>';
}
?>
