<?php
$output = simplexml_load_string(file_get_contents("http://www.thomas-bayer.com/sqlrest/CUSTOMER/"));
?>

<?php foreach ($output[0] as $key => $value) :?>
 	<tr>
 		<td> <a href="http://www.thomas-bayer.com/sqlrest/CUSTOMER/<?= $value ?>">
 		<?= $value ?>
 		</a>
 		</td>
 		<br/>
 	</tr>
<?php endforeach; ?>
