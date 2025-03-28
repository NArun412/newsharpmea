<?php if(!empty($items)) {
	?>
    <?php 
	if(isset($selected_category)) echo '<label>'.get_cell_translate("categories","title","id_categories",$selected_category).'</label>';
	echo support_pagination($items_count,$limit,$offset); ?>
    <table border="1" cellpadding="10" cellspacing="0" width="100%">
    	<thead>
        	<tr>
            	<th><?php echo lang("model name"); ?></th>
                <th><?php echo lang("language"); ?></th>
                <th width="500"><?php echo lang("downloads"); ?></th>
            </tr>
        </thead>
            <tbody>
        <?php 
			$id = 0;
			foreach($items as $res) {
				$n = 0;
		        if($id != $res['id']) { $id = $res['id']; $n = 1;}
			?>
<tr>
            <td valign="middle"><?php echo $res['title']; ?></td>
            <td valign="middle"><?php echo $res['language']; ?></td>
           	<td width="500" valign="middle"><?php echo $res['document_type']; ?>
            	
                <a class="support-download" href="<?php echo route_to("support/download/".$res['id']); ?>"><?php echo lang("download"); ?></a>
            </td>
</tr><?php }?>
        </tbody>
    </table>
    <?php echo support_pagination($items_count,$limit,$offset); ?>
<?php } else {?>
<span class="results-will-show-here"><?php echo lang("no results found"); ?>...</span>
<?php }?>