<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<table>
<?php
$output = json_decode(file_get_contents("http://jsonplaceholder.typicode.com/posts"),true);
?>
<?php for ($i=0; $i < sizeof($output)-1; $i++) : ?> 
	<tr>
	<td><?=  $output[$i]["userid"] ?></td>
	<td><?=  $output[$i]["id"] ?></td>
	<td><?=  $output[$i]["title"] ?></td>
	<td><?=  $output[$i]["body"] ?></td>
	</tr>
<?php endfor; ?>
</table> 

