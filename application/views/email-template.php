<table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" style="padding:20px 0px; margin:0; font-size:16px; color:#777777; background-color:#e8e8e8;">
    <tr>
        <td align="center" valign="top">
            <table border="0" cellpadding="20" cellspacing="0" width="850" style="border-top:5px solid #c30113;background-color:#FFFFFF;">
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr height="80">
                                <td align="center" valign="middle">
                                   <img src="<? echo base_url(); ?>images/email_logo.png"  />
                                   <div style="float:right" >
                                   <a href="<?php echo prep_url($settings["facebook"]); ?>" target="_blank" ><img src="<?php echo base_url(); ?>images/facebook-email-icon.png"  /></a>
                                   <?php echo nbs(1); ?>
                                   <a href="<?php echo prep_url($settings["twitter"]); ?>" target="_blank" ><img src="<?php echo base_url(); ?>images/twitter-email-icon.png"  /></a>
                                   </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center" valign="top">
                                   <img src="<? echo base_url(); ?>images/email_banner.png"  />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr >
                    <td align="left" valign="top"  >
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" height="150" style="padding:0px 40px;font-size:16px; color:#777777;">
                            <tr>
                                <td align="left" valign="top">
                                    <h1 style="color:#c30113; font-size:26px; border-bottom:1px solid #d8d8d8; padding-bottom:15px; margin-bottom:20px; margin-top:0px; font-weight:normal;">
                                    <?php echo $title; ?></h1>
                                    <? echo $message; ?>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td align="center" valign="top">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-top:5px solid #c30113;" >
                            <tr height="60" style="background-color:#282828;">
                                <td align="center" valign="middle" style="color:#FFFFFF;">
                                    <b style=" font-size:20px; font-weight:normal; ">Get In Touch:</b><?php echo nbs(5); ?><b style="font-weight:normal;" >Phone:</b>&nbsp;<?php echo $settings["phone"]; ?><?php echo nbs(10); ?><b style="font-weight:normal;" >E-mail:</b>&nbsp;<?php echo $settings["email"]; ?>
                                </td>
                            </tr>
                            <tr height="30" style=" background-color:#3e3e3e; ">
                            <td align="center" valign="middle">
                            	<a href="<?php echo $settings["website"]; ?>" style="color:#FFFFFF; text-decoration:none;" ><?php echo $settings["website"]; ?></a>
                            </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>