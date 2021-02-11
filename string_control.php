<!DOCTYPE HTML>  
<html>
<head>
<style>
.error {color: #FF0000;}
</style>
</head>
<body>  

<?php
// define variables and set to empty values
$textErr = "";
$stringErr = "";
$text = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST["text"])) {
    $textErr = "Text is required";
  } else {
    $text = test_input($_POST["text"]);
	
	// check if text only contains letters and whitespace
    if (!preg_match("/^[a-zA-Z-' ]*$/",$text)) {
      $nameErr = "Only letters and white space allowed";
    }

    $turkish = array("ı", "ğ", "ü", "ş", "ö", "ç");//turkish letters
    $english   = array("i", "g", "u", "s", "o", "c");//english cooridinators letters

    $text = str_replace($turkish, $english, $text);
	
	$words = explode(" ", $text);
	
	$text_arr =[];
	foreach($words as $word){
		if(!is_string($word) || is_numeric($word)){
			$stringErr = "{$word} is numeric or not a string";
			echo $stringErr.' ';
			exit;
		}
		
		$text_arr[] = $word;
	}

	usort($text_arr, function ($a, $b){
	//return substr($b, -1) - substr($a, -1);
		$at = iconv('UTF-8', 'ASCII//TRANSLIT', substr($a, -1));
		$bt = iconv('UTF-8', 'ASCII//TRANSLIT', substr($b, -1));
		return strcmp($at, $bt);
	});

	$result = implode(' ',$text_arr); 
	
   
  }
}

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<h2>Sort by Words Last Characters of Text </h2>
<p><span class="error">* required field</span></p>
<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">  
  Your Text: <input type="text" name="text" value="<?php echo $text;?>">
  <span class="error">* <?php echo $textErr;?></span>

  <br><br>

  <input type="submit" name="submit" value="Submit">  
</form>

<h5>Canturk Degirmenci </h5>

<?php
echo "<h2>Your Input:</h2>";
echo $result;
echo "<br>";

?>

</body>
</html>