<?php
//This page let users sign up
include('config.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <link href="<?php echo $design; ?>/style.css" rel="stylesheet" title="Style" />
        <title>Sign Up</title>
    </head>
    <body>
    	<div class="header">
        	<a href="<?php echo $url_home; ?>"><img src="<?php echo $design; ?>/images/logo.png" alt="Espace Membre" /></a>
	    </div>
<?php
///check if all fields are set
if(isset($_POST['username'], $_POST['password'], $_POST['passverif'], $_POST['email']) and $_POST['username']!='')
{
		$_POST['username'] = stripslashes($_POST['username']);
		$_POST['password'] = stripslashes($_POST['password']);
		$_POST['passverif'] = stripslashes($_POST['passverif']);
		$_POST['email'] = stripslashes($_POST['email']);
	//check if both passwords match				  
	if($_POST['password']==$_POST['passverif'])
	{
		//check if password length greater than 6
		if(strlen($_POST['password'])>=6)
		{
			//check email grammer
			if(preg_match('#^(([a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+\.?)*[a-z0-9!\#$%&\\\'*+/=?^_`{|}~-]+)@(([a-z0-9-_]+\.?)*[a-z0-9-_]+)\.[a-z]{2,}$#i',$_POST['email']))
			{
				$username = mysql_real_escape_string($_POST['username']);
				$password = mysql_real_escape_string(sha1($_POST['password']));
				$email = mysql_real_escape_string($_POST['email']);
				//check if username is already in use
				$dn = mysql_num_rows(mysql_query('select id from users where username="'.$username.'"'));
				if($dn==0)
				{
					$dn2 = mysql_num_rows(mysql_query('select id from users'));
					$id = $dn2+1;
					//make entry in database
					if(mysql_query('insert into users(id, username, password, email) values ('.$id.', "'.$username.'", "'.$password.'", "'.$email.'")'))
					{
						$form = false;
//display message to inform that signup is successful
?>
<div class="message">You have successfully been signed up. You can now log in.<br />
<a href="index.php">Log in</a></div>
<?php
					}
					else
					{
						$form = true;
						$message = 'An error occurred while signing you up.';
					}
				}
				else
				{
					$form = true;
					$message = 'Another user already uses this username.';
				}
			}
			else
			{
				$form = true;
				$message = 'The email you typed is not valid.';
			}
		}
		else
		{
			$form = true;
			$message = 'Your password must have a minimum of 6 characters.';
		}
	}
	else
	{
		$form = true;
		$message = 'The passwords you entered are not identical.';
	}
}
else
{
	$message = 'All fields must be filled';
	$form = true;
}
//display error message
if($form)
{
	if(isset($message))
	{
		echo '<div class="message">'.$message.'</div>';
	}
///show login form
?>
<div class="content">
<div class="box">
	<div class="box_left">
    	<a href="<?php echo $url_home; ?>">Forum Index</a> - <a href="users.php">List of Users</a>
    </div>
	<div class="box_right">
    	<a href="signup.php">Sign Up</a> - <a href="login.php">Login</a>
    </div>
    <div class="clean"></div>
</div>
    <form action="signup.php" method="post">
        Please fill this form to sign up:<br />
        <div class="center">
            <label for="username">Username</label><input type="text" maxlength=20 placeholder="username" name="username" value="<?php if(isset($_POST['username'])){echo htmlentities($_POST['username'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <label for="password">Password<span class="small">(6 characters min.)</span></label><input type="password" name="password" placeholder="password" maxlength=20/><br />
            <label for="passverif">Password<span class="small">(verification)</span></label><input type="password" placeholder="verify password" name="passverif" maxlength=20 /><br />
            <label for="email">Email</label><input type="text" placeholder="example@sample.com" name="email" value="<?php if(isset($_POST['email'])){echo htmlentities($_POST['email'], ENT_QUOTES, 'UTF-8');} ?>" /><br />
            <br />
            <input type="submit" name="upload" value="Sign Up" />
		</div>
    </form>
</div>
<?php
}
?>
		<div class="foot">&copy;Copyrights Reserved.</div>
	</body>
</html>
