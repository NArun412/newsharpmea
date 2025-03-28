<aside id="sidebar">
  <div id="sidebar-wrap">
    <div class="panel-group slim-scroll" role="tablist">
      <div class="panel panel-default">
        <div class="panel-heading" role="tab">
          <h4 class="panel-title"> <a data-toggle="collapse" href="#sidebarNav"> Navigation <i class="fa fa-angle-up"></i> </a> </h4>
        </div>
        <div id="sidebarNav" class="panel-collapse collapse in" role="tabpanel">
          <div class="panel-body">
            <?php 
	 //$method = $this->router->method;
	 $class = '';
	 $controller = $this->router->class;
	 $method = $this->router->method;
	 if(isset($this->module))
	 $class = $this->module;
	?>
            <!-- ===================================================
                                        ================= NAVIGATION Content ===================
                                        ==================================================== -->
            <ul id="navigation">
              <li class="<?php if($class == "home") echo "active"; ?>"><a href="<?= admin_url('dashboard'); ?>"> <i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
              <li class="<?php if($class == "home") echo "active"; ?>"><a href="<?= admin_url('CodeUploader'); ?>"> <i class="fa fa-dashboard"></i> <span>Source Upload</span></a></li>
              <li class="<?php if($class == "manage/display/compliance") echo "active"; ?>"><a href="<?= admin_url('manage/display/compliance'); ?>"> <i class="fa fa-dashboard"></i> <span>Compliance</span></a></li>
              <?php
											foreach(admin_menu_groups() as $k => $grp) {
											$cond=array('menu_group'=>$k);
											$menu_left=getAll_cond('content_type','sort_order',$cond);
											if(count($menu_left) > 0){
												$class_open = '';
												$display = FALSE;
												foreach($menu_left as $ml) {
													if($class == str_replace(" ","_",$ml["name"]))
													$class_open = 'open';
													if (has_permission(str_replace(" ","_",$ml["name"]),'view')) $display = TRUE;
												}
												if($display) {
											?>
              <li class="<?php echo $class_open; ?>"> <a role="button" tabindex="0"><i class="fa fa-list"></i> <span><?php echo ucfirst($grp); ?></span> <!--<span class="badge bg-lightred">6</span>--></a>
                <ul style=" display:none ">
                  <?php
												$i=0;
												foreach($menu_left as $val){ 
												$module = str_replace(" ","_",$val["name"]);
												$i++;
												if (has_permission($module,'view')){ 
												$link = admin_url('manage/display/'.str_replace(" ","_",$val["name"]));
												if(in_array($module,static_modules()))
												$link = admin_url($module);
												?>
                  <li class="<?php if($class == $module) echo 'active'; ?>" style="text-transform:capitalize;"> <a href="<?php echo $link; ?>"> <i class="fa fa-caret-right"></i> <?php echo ucfirst($module); ?></a></li>
                  <?php } ?>
                  <?php } ?>
                </ul>
              </li>
              <?php }}} ?>
              <?php if(has_permission("emails_templates","view")) { ?>
              <li class="<?php if($class == "emails_templates"){ echo 'active';  } ?>" > <a role="button" tabindex="0" href="<?php echo admin_url('manage/display/emails_templates'); ?>"> <i class="fa fa-envelope-o"></i> <span>Email Templates</span></a></li>
              <?php } ?>
            <?php if(user_role() == 1 || user_role() == 2) {?>
        <!--      <li class="<?php if($this->router->class == "trash"){ echo 'active';  } ?>" > <a role="button" tabindex="0" href="<?php echo admin_url('trash'); ?>"> <i class="fa fa-trash-o"></i> <span>Trash</span></a></li>-->
              
              <li class="<?php if($this->router->class == "log"){ echo 'active';  } ?>" > <a role="button" tabindex="0" href="<?php echo admin_url('log'); ?>"> <i class="fa fa-list"></i> <span>Log</span></a></li>
              <?php } ?>
              <?php if(user_role() == 1) {?>
              
              <li class="<?php if($controller == "control") echo 'open'; ?>"> <a role="button" tabindex="0"><i class="fa fa-cog"></i> <span>Developer Panel</span></a>
                <ul>
                  <li class="<?php if($method != "menu_groups") echo 'active'; ?>"> <a href="<?php echo admin_url('control'); ?>"> <i class="fa fa-caret-right"></i> Database</a></li>
                  
                  <li class="<?php if($method == "menu_groups") echo 'active'; ?>"> <a href="<?php echo admin_url('control/menu_groups'); ?>"> <i class="fa fa-caret-right"></i> Menu Groups</a></li>
                  
                </ul>
              </li>
              <?php }?>
            </ul>
            <!--/ NAVIGATION Content --> 
            
          </div>
        </div>
      </div>
    </div>
  </div>
</aside>
