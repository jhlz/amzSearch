<?php/*** Test for Amazon search**/
require_once "amzSearch.php";	
	if (isset($_POST['firstname-amz']) AND isset($_POST['lastname-amz']))
	{
			$res = searchAmazon("DE",$_POST['firstname-amz']." ".$_POST['lastname-amz'],$_POST['album-amz']);			$resA= $res['0'];
						$found = strpos($resA, "http");					if ($found !== false){				echo "<a href='".$resA."'>".$resA."</a>";			}			else {echo $resA;}
	}
		
?>	
<h2>Amazon Search Test</h2>
<form action='test.php' method='post'>
<p>firstname: <input type='text' name='firstname-amz' /></p>
<p>lastname: <input type='text' name='lastname-amz' /></p>
<p>album: <input type='text' name='album-amz' /></p>
<p><input type='submit' /></p></form>

