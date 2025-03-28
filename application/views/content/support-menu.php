<div class="span" >
<ul class="corporate_profile center">
<li>
<a href="<?php echo route_to("support"); ?>" class="<?php if($this->router->class == 'support' && $this->router->method == 'index') {?>active<?php }?>"><?php echo lang("download center"); ?></a>
</li>
<li>
<a href="<?php echo route_to("support/service_center_location"); ?>" class="<?php if($this->router->class == 'support' && $this->router->method == 'service_center_location') {?>active<?php }?>"><?php echo lang("service center location"); ?></a>
</li>
</ul>
</div>