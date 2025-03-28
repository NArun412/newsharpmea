<?php
$cl_b = 'no-banner';
if (isset($banners) && !empty($banners))
    $cl_b = '';
?>
<style>
    .complianceLink{
        cursor: pointer;
        /*margin-left: 18%;*/
        font-size: 22px;
        color: #0aa2fb;
    }
    a.complianceLink:hover, a.complianceLink.active {
        color: red;
    }
    .content_inner h1, .content_inner h2{
        color: white;
    }
    .contentContainer{
        background: #141414;
    padding-top: 20px;
    border-radius: 4px;
    /* box-shadow: 0px 2px 1px 4px; */
    color: #333;
    margin: 10px 10px 0px 10px;
    padding: 10px;
    width: 97%;
    }
    .contentTitle{
        font-weight: bold;
        margin: 10px;
        font-size: 18px;
        color:#e6000d !important;
        margin-bottom: 2px !important;
    }
    .oneColInner{
            margin: 23px !important;
    width: auto !important;
    }
</style>
<div class="content_inner <?php echo $cl_b; ?>" >
    <div class="centered2" >


        <div class="span" >
            <div class="">
                <?php
                if ($isEdit == 0) {
                    ?>    

                    <h1 class="head-title with-border center">
                        <?php echo lang('COMPLIANCEMENU'); ?>  
                    </h1>
                    <div class="custom-form-style service_center_form full "> 
                        <?php
                        foreach ($codeList as $val) {
                            ?>
                            <a  class="complianceLink" title="View Detail" href="<?= current_url(); ?>/edit/<?= $val['id_compliance']; ?>"><?php echo $val["title"]; ?></a><br><br>
                            <?php
                        }
                    } else {
                        ?>
                            <div ><h1 class="contentTitle"><?php echo $title ?></h1></div>
                        <div class="contentContainer span">
                            <div><?php echo html_entity_decode($content) ?></div>
                        </div>
                    <?php } ?>
                </div>
            </div>

        </div>

    </div>

</div>
<?php
$this->session->unset_userdata("error_message");
?> 
