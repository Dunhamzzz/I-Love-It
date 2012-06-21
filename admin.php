<?php
require_once('config.php');
	//Display Admin
	$urls = $db->query("SELECT * FROM `urls`");
	?>
<table>
	<thead>
		<tr>
			<th>URL</th>
			<th>Tag</th>
			<th>Hits</th>
			<th>Created On</th>
		</tr>
	</thead>
	<tbody>
	<?php while($url = $urls->fetch_array()):?>
		<tr>
			<td><?php echo $url['url'];?></td>
			<td><?php echo $url['tag'];?></td>
			<td><?php echo $url['count'];?></td>
			<td><?php echo $url['created'];?></td>
		</tr>
	<?php endwhile;?>
	</tbody>
</table>