<table border="0" cellpadding="2" cellspacing="0" width="100%">
  <tr>
    <td><table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
          <th>Name</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Country</th>
          <th>State</th>
          <th>City</th>
        </tr>
        <tr>
          <td><?php echo $form['name']; ?></td>
          <td><a href="mailto:<?php echo $form['email']; ?>"><?php echo $form['email']; ?></a></td>
          <td><?php echo $form['phone']; ?></td>
          <td><?php echo get_cell_translate('countries','title','id_countries',$form['id_countries']); ?></td>
          <td><?php echo get_cell_translate('states','title','id_states',$form['id_states']); ?></td>
          <td><?php echo $form['city']; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr>
          <th>Experience</th>
          <th>Position</th>
          <th>Highest Qualification</th>
          <th>Division</th>
          <th>Department</th>
          <th>Region</th>
          <th>Experience Level</th>
        </tr>
        <tr>
          <td><?php echo 'Y: '.$form['experience_years'].', M: '.$form['experience_months']; ?></td>
          <td><?php echo $form['position']; ?></td>
          <td><?php echo $form['highest_qualification']; ?></td>
          <td><?php echo get_cell_translate('careers_divisions','title','id_careers_divisions',$form['id_careers_divisions']); ?></td>
          <td><?php echo get_cell_translate('departments','title','id_departments',$form['id_departments']); ?></td>
          <td><?php echo get_cell_translate('regions','title','id_regions',$form['id_regions']); ?></td>
          <td><?php echo get_cell_translate('experience_levels','title','id_experience_levels',$form['id_experience_levels']); ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td><table border="1" cellpadding="5" cellspacing="0" width="100%" align="left">
        <tr>
          <th align="left">Brief</th>
        </tr>
        <tr>
          <td><?php echo $form['brief']; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr><a href="<?php echo site_url("careers/downloadCV/".$form['id']); ?>">Click here</a> to download CV</tr>
</table>
