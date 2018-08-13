<?= $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/bootstrap/js/bootstrap-alert.js"></script>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/default/scripts/hash.js"></script>
<div class="container body_height">
    <h2 class="head2">Admin Panel - Register a new client</h2>
    <div class="row-fluid">
        <div class="span3">
            <?php $this->load->view('common/admin_panel'); ?>
        </div>
        <div class="span6">
            <form class="form-horizontal" method="post" action="<?php echo $this->config->site_url(); ?>user/save" onsubmit="return on_submit();" enctype="multipart/form-data">
                <div class="control-group ">
                    <label class="control-label" for="email">E-Mail Address</label>
                    <div class="controls">
                        <input type="text" id="email" name="email" placeholder="E-Mail Address"/>
                    </div>
                </div>
                <!--div class="control-group ">
                    <label class="control-label" for="name">User Name</label>
                    <div class="controls">
                        <input type="text" id="name" name="name" placeholder="User Name" required>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="fullName">Full Name</label>
                    <div class="controls">
                        <input type="text" id="fullName" name="fullName" placeholder="Full Name" required>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="gendor">Gendor</label>
                    <div class="controls">
                        <select id="gendor" name="gendor" required>
                            <option value="0">Man</option>
                            <option value="1">Woman</option>
                        </select>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="birth">Birthday</label>
                    <div class="controls">
                        <input type="text" id="birth" name="birth" placeholder="Birthday"/>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="birth">Occupation</label>
                    <div class="controls">
                        <input type="text" id="occupation" name="occupation" placeholder="Occupation"/>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="phone">Phone</label>
                    <div class="controls">
                        <input type="text" id="phone" name="phone" placeholder="Phone"/>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="pwd">Password</label>
                    <div class="controls">
                        <input type="password" id="pwd" name="pwd" placeholder="Password" required>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="cpwd">Confirm Password</label>
                    <div class="controls">
                        <input type="password" id="cpwd" name="cpwd" placeholder="Confirm Password" required>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="level">Level</label>
                    <div class="controls">
                        <select id="level" name="level">
                            <option value="">----- SELECT -----</option>
                            <option value="1">Super Administrator</option>
                            <option value="2">Administrator</option>
                        </select>
                    </div>
                </div>
                <div class="control-group ">
                    <label class="control-label" for="interest">Interests</label>
                    <div class="controls">
                            <input type="text" id="interests" name="interests" placeholder="Interests" />
                    </div>
                </div>
                    <div class="control-group">
                        <label class="control-label" for="avatar">Avatar</label>
                        <div class="controls">
                            <input type="file" id="avatar" name="avatar">
                        </div>
                    </div-->
                <div class="control-group">
                    <div class="controls">
                        <input type="hidden" name="digest" id="digest" value=""/>
                        <button type="submit" class="btn btn-inverse" style="width: 100px;">Register</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="span3"></div>
    </div>

    <?php
    $error = validation_errors();

    if (!empty($error)) {
        ?>
        <div class="alert alert-error text-center" style="width: 410px;margin:auto">
            <button class="close" data-dismiss="alert" type="button">x</button>
            <?php echo validation_errors(); ?>
        </div>
        <?php
    }
    ?></div>
<!--script type="text/javascript">
    
    function on_submit()
    {
        if ($('#pwd').val() != $('#cpwd').val()){
            alert('The password and confirm Password is different.');
            return false;
        }
        
        $('#digest').val(getDigest(pwd.value));
        $('#pwd').val('');
        $('#cpwd').val('');
        
        return true;
    }
</script-->    

<?= $this->load->view('common/footer'); ?>
