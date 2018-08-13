<?php echo $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/default/scripts/hash.js"></script>

    <div class="container body_height">
    <div class="row-fluid">
        <div class="span4"></div>
        <div class="span4" style="margin-top: 50px;">
            <form class="form-signin" method="post" action="<?php echo $this->config->site_url();?>member/registor" onsubmit="return on_submit();">
                <h2 class="form-signin-heading">Registor</h2>
                <input type="text" id="username" name="username" class="input-block-level" placeholder="User Name" required>
                <input type="password" id="password" name="password" class="input-block-level" placeholder="Password" required>
                <input type="password" id="confirm_pwd" name="confirm_pwd" class="input-block-level" placeholder="Confirm Password" required>
                <button class="btn btn-inverse" type="submit">Registor</button>
                <input type="hidden" name="digest" id="digest" value="">
              </form>
        </div>
        <div class="span4"></div>
        </div>
    </div> 
    
<script type="text/javascript">
    var username = document.getElementById('username');
    var pwd = document.getElementById('password');
    var confirm = document.getElementById('confirm_pwd');
    var digest = document.getElementById('digest');
    
    function on_submit()
    {
        if (username.value == ''){
            alert('The User Name field is required.');
            return false;
        }
        
        if (pwd.value == ''){
            alert('The password field is required.');
            return false;
        }
        
        if (confirm.value == ''){
            alert('The confirm password field is required.');
            return false;
        }
        
        if (pwd.value != confirm.value){
            alert('The password and confirm Password is different.');
            return false;
        }
        
        digest.value = getDigest(pwd.value);
        confirm.value = "";
        pwd.value = "";
        
        return true;
    }
    var NO_ALERT=true;
</script>    
    
<?php echo $this->load->view('common/footer'); ?>