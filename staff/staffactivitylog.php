<?php
if ($link == 'nope'){
?>

<table class="table table-bordered table-striped table-hover data-table">
	<thead>
		<tr>
			<th>ID</th>
			<th>LOGS</th>
			<th>Time</th>
		</tr>
	</thead>
	<tbody>
	<?php
		
		$getLogs = $odb->prepare("SELECT * FROM `logs` ORDER BY id DESC");
		$getLogs->execute();
		
		while($record = $getLogs->fetch(PDO::FETCH_ASSOC)){
		?>
			<tr class="gradeX">
				<td><center><?php echo($record['id']); ?></center></td>
				<td><center><?php echo($record['log']); ?></center></td>
				<td><center><?php echo($record['timestamp']); ?> </center></td>
			</tr>
		<?php
		}
		?>						
	</tbody>
</table>

<?php

} else { 
	echo "Someone is where they're not suppose to be...";
}
?>