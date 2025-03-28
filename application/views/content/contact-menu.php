<div class="span" >
<ul class="corporate_profile">
<li>
<a href="<?php echo route_to("contact"); ?>" class="<?php if($this->router->class == 'contact' && $this->router->method == 'index') {?>active<?php }?>"><?php echo lang("regional head office"); ?></a>
</li>
<!--<li>
<a href="<?php //echo route_to("contact/service_center_location"); ?>" class="<?php //if($this->router->class == 'contact' && $this->router->method == 'service_center_location') {?>active<?php //}?>"><?php //echo lang("service center location"); ?></a>
</li>-->
<li>
<a href="<?php echo route_to("contact/inquiry"); ?>" class="<?php if($this->router->class == 'contact' && $this->router->method == 'inquiry') {?>active<?php }?>"><?php echo lang("inquiry"); ?></a>
</li>
</ul>
</div>