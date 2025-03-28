<?php if(!empty($results)) {
	?>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
    	<thead>
        	<tr>
            	<th><?php echo lang("country"); ?></th>
                <th><?php echo lang("state"); ?></th>
                <th><?php echo lang("product"); ?></th>
                <th><?php echo lang("address"); ?></th>
            </tr>
        </thead>
        <tbody>
        	<?php foreach($results as $res) {?>
            	<tr>
            		<td><?php echo $res['country_name']; ?></td>
                	<td><?php echo $res['state_name']; ?></td>
                	<td><?php echo $res['product_name']; ?></td>
                	<td><?php echo $res['address']; ?></td>
           		</tr>
            <?php }?>
        </tbody>
    </table>
<?php } else {?>
<span class="results-will-show-here"><?php echo lang("no results found"); ?>...</span>
<?php }?>