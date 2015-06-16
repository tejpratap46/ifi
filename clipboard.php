<?php
error_reporting ( 0 );
require 'connection.php';
// $websiteurl = "http://www.brainstrom.zz.mu/ifi/" // to use for calling api using file_get_contents (Using to send push notification call in clipnoard.php)
if ($_POST ['clipText']) {
	$insert = mysql_query ( "INSERT INTO `clipboard` (`username`, `text`) VALUES ('" . $_COOKIE ['ifiusername'] . "','" . $_POST ['clipText'] . "')" ) or die ( "Error , cannot add" );
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" href="favicon.ico">

<title>If i :: Clipboard</title>

<!-- Bootstrap core CSS -->
<link href="css/bootstrap.min.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

<!-- Custom styles for this template -->
<link href="navbar-fixed-top.css" rel="stylesheet">
</head>

<body>
	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed"
					data-toggle="collapse" data-target="#navbar" aria-expanded="false"
					aria-controls="navbar">
					<span class="sr-only">Toggle navigation</span> <span
						class="icon-bar"></span> <span class="icon-bar"></span> <span
						class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="index.php">If I</a>
			</div>
			<div id="navbar" class="navbar-collapse collapse">
				<ul class="nav navbar-nav">
					<li><a href="index.php">Home</a></li>
					<li><a href="notification.php">Notifications</a></li>
					<li class="active"><a href="clipboard.php">Clipboard</a></li>
					<li><a href="top.php">Top Formulas</a></li>
					<li><a href="remote.php">Remote</a></li>
				</ul>
				<ul class="nav navbar-nav navbar-right">
				<?php
				if ($_COOKIE ['ifiusername']) {
					echo "<li class='dropdown'><a href='#' class='dropdown-toggle' data-toggle='dropdown' role='button' aria-expanded='false'>" . $_COOKIE ['ifiusername'] . "<span class='caret'></span></a>";
					echo "<ul class='dropdown-menu' role='menu'>";
					echo "<li><a href='#'>Profile</a></li>";
					echo "<li class='divider'></li>";
					echo "<li class='dropdown-header'>Say Good Bye</li>";
					echo "<li><a href='logout.php'>Logout</a></li>";
					echo "</ul>";
					echo "</li>";
				} else {
					echo '<a type="button" class="btn btn-default navbar-btn" href="login.php">Sign in</a>';
				}
				?>
				</ul>
			</div>
			<!--/.nav-collapse -->
		</div>
	</nav>
	<div class="notification">Loading...</div>
	<div class="container" style="width: 100%; margin-top: 70px;">
		<!-- Main component for a primary marketing message or call to action -->
		<div class="jumbotron">
			<h1>Clipboard</h1>
			<p>Your Personal Clipboard.</p>
			<form method="post">
				<div class="input-group" style="padding: 1%;">
					<span class="input-group-addon">Add This Text Also</span> <input
						name="clipText" type="text" class="form-control"
						placeholder="Text To Store, Press Enter Key To Save" required />
				</div>
			</form>
			<?php // showClipboard();?>
			<div id="formulas"></div>
			<div class="jumbotron" id="loading">
				<div class="row well" id="items">
					<img class="center-image" alt="loading..."
						src="loading.gif">
				</div>
			</div>
		</div>
	</div>
	<!-- /container -->
	<!-- Bootstrap core JavaScript
    ================================================== -->
	<!-- Placed at the end of the document so the pages load faster -->
	<script
		src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script type='text/javascript'>
	$(document).click(function(e) {
      $id = $(e.target).attr('id');
      if ($id == 'sendToPhone') {
      	$('.notification').show();
		var message = $(e.target).parent().parent().find('#push').text();
		$('.notification').load('api/push/pushbotsPush.php',{
		m: message, i: <?php echo "'".$_COOKIE['ifiusername']."'" ?>} ,
			function(){
			/* Stuff to do after the page is loaded */
			$('.notification').stop().text('Sent To Phone').fadeIn(400).delay(3000).fadeOut(400);
		});
      }
    });
	</script>

	<script type="text/javascript">
	var pg = 1;
	$(document).ready(function() {
		ajaxCall(pg);
	});

	$(window).scroll(function() {
	   if($(window).scrollTop() + $(window).height() == $(document).height()) {
		   pg = pg + 1;
		   ajaxCall(pg);
	   }
	});


	count = 0;
	function ajaxCall(page){
		$('#loading').show();
		$.getJSON('api/clipboard/clipboard.showall.php?apikey=tejpratap&username=' + <?php echo "'".$_COOKIE['ifiusername']."'" ?> + '&page=' + page, function(json, textStatus) {
			$('#loading').hide();
			try{
				formulas = json.text;
				// $.each(formulas, function(arrayID,formula) {
				//             console.log(formula);
				// });
				for (var i = 0; i < formulas.length; i++) {
					count++;
					jsonObj = formulas[i];
					// console.log(jsonObj);
					title = jsonObj.text;
					show = '';
					show = show + '<div class="thumbnail">';
					// show = show + '<h1><span class="badge" style="font-size: 40px;">' + count + '</span> ' + name + '</h1>';
					show = show + '<h1>' + count + ' : ' + title + '</h1>';
					show = show + '</div>';
					prev = $('#formulas').html();
					$('#formulas').html(prev + show);
				}
			}catch(err){
				$('#loading').hide(100);
				$('#formulas').html('<h2 class="bold">Login First</h2>');
			}

		});

	}
	</script>
</body>
</html>
<?php
function showClipboard() {
	if ($_COOKIE ['ifiusername']) {
		if ($_POST ['clipText']) {
			$insert = mysql_query ( "INSERT INTO `clipboard` (`username`, `text`) VALUES ('" . $_COOKIE ['ifiusername'] . "','" . $_POST ['clipText'] . "')" ) or die ( "Error , cannot add" );
				// echo '<a href="api/pushbotsPush.php?message='.$_POST ['clipText'].'&gcmid='.$pushArray['gcmid'].'">link</a>';
				// $res = file_get_contents('http://www.brainstrom.zz.mu/ifi/api/pushbotsPush.php?message='.$_POST ['clipText'].'&gcmid='.$pushArray['gcmid']);
				// echo $res;
		}
		
		$query = mysql_query ( "SELECT * FROM `clipboard` WHERE username = '" . $_COOKIE ['ifiusername'] . "' ORDER BY `clipboard`.`index` DESC" );
		$rows = mysql_num_rows ( $query );
		if ($rows > 0) {
			for($i = 0; $i < $rows; $i ++) {
				$qarray = mysql_fetch_array ( $query );
				echo '<div class="row" colalert alert-info" role="alert" style="background-color: #F8F8F8;">';
				echo '<div class="col-md-10">';
				echo '<p style="font-size: 24px; padding: 1%;" id="push"><span class="badge" style="font-size: 24px;">' . ($i + 1) . '</span> ' . $qarray ['text'] . "</p>";
				echo '</div>';
				echo '<div class="col-md-2">';
				echo '<button style="height: 70px; width: 100%; text-align: center;" class="btn btn-primary" id="sendToPhone">Send To Phone</button>';
				echo '</div>';
				echo '</div>';
			}
		} else {
			echo '<div class="alert alert-danger" role="alert">';
			echo "<p><strong>Nothing Here,</strong> Please enable Clipboard Listener from home page of Ifi Andtoid App if you want to use this feature.</p>";
			echo '</div>';
		}
	} else {
		echo "<h2 style='text-align: center;'>You are not logged in!</h2>";
		echo '<a style="width: 100%;" class="btn btn-primary" type="button" href="login.php">
				  Login <span class="badge">Here</span>
				</a>';
	}
}
?>