  <?php if($this->router->method == "index"){?>  <div class="head-title with-border"><?php echo lang("search results"); ?> - <?php echo $keywords; ?></div><?php }?>
  <?php if(!empty($results)) {
		$cc = count($results);$i=0;
		$starttable = "";
		$header = "";
		$display_header = FALSE;
		foreach($results as $val){
			$i++;
			$text_1 = 'read more';
			$link = '';
			list($id,$table,$text) = explode('s:',$val['id']);
			if($starttable != $table) {
				$starttable = $table;
				$header = '<h2 class="ignore-click">'.lang($text).'</h2>';
				$display_header = TRUE;
			}
			$class = "";
			switch($table) {
					case 'divisions':
						$img_path = 'divisions/image/480x290/';
						$link = route_to("products/index/".$id);
						$title = $val['title'];
						if(empty($val['image']))
						$class = "full";
						break;
					case 'categories':
						$link = "products/index";
						$ids_arr = array($id);
						$parents = array();
						$cat = $this->db->where("id_categories",$id)->get("categories")->row_array();
						$div_id = $cat['id_divisions'];
						$parent = $this->db->where("id_categories",$cat['id_parent'])->get("categories")->row_array();
						while(!empty($parent)) {
							$div_id = $parent['id_divisions'];
							array_push($ids_arr,$parent['id_categories']);
							$parent = $this->db->where("id_categories",$parent['id_parent'])->get("categories")->row_array();
						}
						$link .= '/'.$div_id;
						for($i = (count($ids_arr) - 1);$i >= 0;$i--) $link .= '/'.$ids_arr[$i];
						$link = route_to($link);
						$img_path = 'categories/image_small/560x500/';
						if(empty($val['image']))
						$class = "full";
						$title = $val['title'];
						break;
					case 'products':
						$img_path = 'products/image/710x450/';
						$link = route_to("products/details/".$id);
						$title = $val['title'];
						if(empty($val['image']))
						$class = "full";
						break;
			}
	/*		$find = array($keywords,strtolower($keywords),strtoupper($keywords),ucfirst($keywords));
			$replace = array("<span class='keywordColor'>".$keywords."</span>","<span class='keywordColor'>".strtolower($keywords)."</span>","<span class='keywordColor'>".strtoupper($keywords)."</span>","<span class='keywordColor'>".ucfirst($keywords)."</span>");*/
			?>
            <?php if($display_header) echo $header; $display_header = FALSE; ?>
            <div class="search-result-item <?php echo $class; ?> <?php if($this->router->method != "index"){?>ignore-click<?php }?> <?php if($i == $cc) echo ' last'; ?>">
           	 	<?php if(!empty($val['image'])) {?>
            	<div class="img ignore-click"><a href="<?php echo $link; ?>"><img src="<?php echo dynamic_img_url(); ?><?php echo $img_path; ?><?php echo $val['image']; ?>" alt="<?php echo $val['title']; ?>" title="<?php echo $val['title']; ?>" /></a></div>
                <?php }?>
            	<h3 class="ignore-click"><a class="ignore-click" href="<?php echo $link; ?>"><?php echo $title; ?></a></h3>
            </div>
            <?php }?>
            <?php } ?>