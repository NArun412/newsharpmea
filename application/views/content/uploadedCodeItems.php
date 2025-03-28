<style>
    table tr.oddRow td {
        border-color: #e79898;
        background: #f9a8a8;
            color: black;
    }
    table tr.evenRow td {
        border-color: #f9f9f9;
    background: #f7f6f6;
        color: black;
    }
</style>
<?php if($this->session->userdata("success_message")){ ?>
<div class="alert alert-success" style="    margin-left: 10%;color:#93f993">
<?= $this->session->userdata("success_message"); ?>
</div>
<?php } ?>
<div class="download-center" style="margin-left: 10%;    margin-top: 4%;">
    <table class="table table-hover mb-0" width="100%">
        <thead>
            <tr>
                <th style="width:37px">Action</th>
                <th style="width:300px">File Name</th>
            </tr>
        </thead>
        <?php if (!empty($codeList)) {  $index = 0;?>    
            <tbody>
            <?php foreach ($codeList as $itm) { ?>	
                
                    <tr class="<?php echo $index%2 == 0 ? 'oddRow' :'evenRow' ?>">
                        <td class="small" style="text-align:center;"><a  style="color:black !important;cursor: pointer" title="Delete" href="<?= base_url(); echo $this->langue; ?>/codeUploader/delete/<?= $itm['id']; ?>"><i class="fa fa-trash" aria-hidden="true"></i></a></td>
                        <td class="small"><?php echo $itm['name'] ?></td>
                    </tr>
                

            <?php $index++ ;} ?>
                </tbody>
        <?php }else{ ?>
                <tbody>
                    <tr><td colspan="2">No records</td></tr>
                </tbody>
        <?php } ?>
    </table>
</div>
<?php if($this->pagination->create_links()) {?>
 <div  style="margin-left: 10%; " class="pagination-box"><?php echo $this->pagination->create_links(); ?></div><?php }?>
 
<?php 
$this->session->unset_userdata("success_message"); 
?> 

