<style>
    .description div{
        line-height: 1.6;
    }
</style>
<div class="description">
    <div><?php echo lang("LINE1") ?> </div>
    <div style="margin: 16px 0px 3px 0px;font-weight: bold;"><?php echo lang("NOTE") ?></div>
    <span><?php echo lang("LINE2") ?></span><span style="font-weight: bold;">[<?php echo lang("Notice") ?>]</span><span><?php echo lang("LINE2A") ?></span>
    <div style="font-weight: bold;margin-top: 16px;">[<?php echo lang("Notice") ?>]</div>
    <div> <?php echo lang("LINE3") ?></div>
    <div style="margin: 16px 0px 16px 0px"><?php echo lang("LINE4") ?></div>
    <div><?php echo lang("LINE5") ?></div>
    <div><?php echo lang("LINE6") ?></div>

    <div style="margin: 22px 0px 12px 0px;font-size: 20px;"><?php echo lang("DOWNLOADEFILE") ?></div>
    <?php if (!empty($documents)) { ?>
        <div class="download-center">
            <?php foreach ($documents as $itm) { ?>	

                <div class="download-item">
                    <h3 class="title"><?php echo $itm['name']; ?>
                        <span class="download-navs">
                            <a onclick="download_code(<?php echo $itm['id']; ?>)"><span class="download-icon"><i class="fa fa-arrow-down" aria-hidden="true"></i></span>
                                <span class="download-btn"><?php echo lang("download"); ?></span></a>
                        </span>
                    </h3>
                </div>
            <?php } ?>
        </div>
        <?php if ($this->pagination->create_links()) { ?>
            <div class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php } ?>
    <?php } else { ?>
        <span class="results-will-show-here"><?php echo lang("no results found.."); ?></span>
    <?php } ?>
 