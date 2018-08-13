<?php $this->load->view('common/header'); ?>
<div class="container body_height">
    <h2 class="head2">Mesages Information</h2>
    <div class="row-fluid" style="margin-bottom: 10px;">
        <div class="span12">
            <div class="span2"></div>
            
            <div class="span8 msg">
            <?php
                $count = 0;
                $all_cnt = sizeof($messages);
                foreach ($messages as $message):
            ?>
                
                <div class="view" style="display: <?php echo (($is_collapsed == 0 && $count > 0 && $count < $all_cnt-1) ? 'none' : '')?>;">
                    <div class="title">
                    <?php
                        if ($message->reply == 0)
                        {
                            echo "<font style='color:#790619'>" . (empty($msg->name) ? '[Unknown User]' : $msg->name) . "</font><br>";
                        }
                        else
                        {
                            echo "<font style='color:#00681c'>Me</font><br/>";
                        }
                    ?>
                        <font class="date"><?php echo date('m/d/Y H:i:s', strtotime($message->m_date)); ?></font>
                    </div>
                    <div class="body"><?php echo $message->message; ?></div>
                    <div class="clear"></div>
                </div>
            <?php
                if ($is_collapsed == 0 && $all_cnt > 2 && $count == $all_cnt-2)
                {
            ?>
                <div class="msg_collapser"> ---------- <a href="#" id="msg_collapser"><?php echo $all_cnt-2; ?> messages</a> ---------- </div>
            <?php
                }
                    $count++;
                endforeach;
            ?>
                <div class="reply">
                    <form method="post" action="<?php echo $this->config->site_url(); ?>messages/detail" id="form" name="form">
                    <div class="control-group ">
                        <label class="control-label" for="fname">Reply</label>
                        <div class="controls">
                            <textarea id="reply_msg" name="reply_msg" required></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-inverse" style="width:100px;">Send</button>
                    <input type="hidden" id="user_id" name="user_id" value="<?php echo $user_id; ?>" />
                    <input type="hidden" id="is_collapsed" name="is_collapsed" value="<?php echo $is_collapsed; ?>" />
                    </form>
                </div>
            </div>

            <div class="span2"></div>
        </div>
    </div>
</div>
 <?php $this->load->view('common/footer'); ?>
 
 <script>
    $('#msg_collapser').click(function(){
        $('.view').each(function(){
           $(this).css('display', '');
        });
        
        $('.msg_collapser').hide();
        $('#is_collapsed').val(1);
    });
 </script>