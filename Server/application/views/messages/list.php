<?php $this->load->view('common/header'); ?>
<div class="container body_height">
    <h2 class="head2">Mesages Information</h2>
  <div class="row-fluid">
    <div class="span12">
            <table class="table table-hover table-striped" style="table-layout:fixed;">
                <thead>
                    <tr>
                        <th width="15px"></td>
                        <th width="150px">Date</td>
                        <th width="150px">Form</td>
                        <th width="*">Message</td>
                        <th width="10px"></td>
                    </tr>
                </thead>
                <tbody>
                <?php
                if (isset($msgs)) 
                {
                    foreach ($msgs as $msg):
                ?>
                    <tr class="<?php echo (($msg->status == 0) ? 'email-unread' : 'email-read'); ?>" id="<?php echo $msg->mid; ?>">
                        <td class="first"></td>
                        <td><?php echo date('m/d/Y H:i:s', strtotime($msg->m_date)); ?></td>
                        <td><a href="<?php echo $this->config->site_url();?>messages/detail/<?php echo $msg->user_id; ?>"><?php echo (empty($msg->name) ? '[Unknown User]' : $msg->name); ?></a></td>
                        <td width="100px" class="ellips-text" title="<?php echo $msg->message; ?>"><?php echo $msg->message; ?></td>
                        <td>
                            <a href="<?php echo $this->config->site_url();?>messages/delete/<?php echo $msg->user_id; ?>"><img src="<?php echo $this->config->site_url();?>theme/default/images/trash.png"/></a>
                        </td>
                    </tr>
                <?php
                    endforeach;
                }
                ?>
                </tbody>
            </table>
            
            <div class="pagination text-center"><?php echo $pagination?></div>
    </div>
  </div>
</div>
<script>
    $('.table tbody tr').each(function(){
       if($(this).hasClass('email-unread'))
       {
            $('#' + $(this).attr('id')+ ' .first').addClass("email-icon-unread");
       }
       else
       {
            $('#' + $(this).attr('id')+ ' .first').addClass("email-icon-read");
       }
    });
    
    $('.table tbody tr').each(function(){
        return;
        $(this).click(function(){
            mid = $(this).attr('id');
            $.ajax({
                url: "<?php echo $this->config->site_url();?>ajax/update_msg",
                type: "get",
                data: 'mid=' + mid,
                error:function(request,status,error){
                            alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
                        },
                success: function(data) {
                    var json = $.parseJSON(data);
                    if (json['result'] == 1)
                    {
                        $('#' + mid + ' .first').removeClass("email-icon-unread");
                        $('#' + mid + ' .first').addClass("email-icon-read");
                        $('#' + mid).removeClass("email-unread");
                        $('#' + mid).addClass("email-read");
                        
                        if (parseInt(json['cnt']) > 0)
                        {
                           $('.badge-important').show();
                           $('.badge-important').text(json['cnt']);
                        }
                        else
                        {
                           $('.badge-important').hide();
                        }
                            
                    }
                }
            });
            
        });
    });    
    
</script>
 <?php $this->load->view('common/footer'); ?>