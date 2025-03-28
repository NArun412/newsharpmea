<table border="1" cellpadding="10" cellspacing="0" width="100%">
	<tr>
    	<th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Country</th>
      <!--  <th>State</th>-->
        <th>City</th>
       <!-- <th>Address</th>-->
        <th>Subject</th>
        <th>Message</th>
    </tr>
    <tr>
    	<td><?php echo $form['name']; ?></td>
        <td><a href="mailto:<?php echo $form['email']; ?>"><?php echo $form['email']; ?></a></td>
        <td><?php echo $form['phone']; ?></td>
        <td><?php echo get_cell_translate('countries','title','id_countries',$form['id_countries']); ?></td>
       <!-- <td><?php //echo get_cell_translate('states','title','id_states',$form['id_states']); ?></td>-->
        <td><?php echo $form['city']; ?></td>
      <!--  <td><?php //echo $form['address']; ?></td>-->
       <!-- <td><?php //echo get_cell_translate('feedback_types','title','id_feedback_types',$form['id_feedback_types']); ?></td>-->
            <td><?php echo $form['subject']; ?></td>
        <td><?php echo $form['message']; ?></td>
    </tr>
</table>