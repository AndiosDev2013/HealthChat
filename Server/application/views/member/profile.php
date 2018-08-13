<h3>Reset your password</h3>
<form class="form-signin" method="post" action="<?php echo $this->config->site_url(); ?>member/profile" onsubmit="return on_profile();">
    <legend><?php echo $user->email; ?></legend>
    <div class="control-group">
        <label class="control-label" for="old_pwd">Current Password</label>
        <div class="controls">
            <input type="password" id="old_pwd" name="old_pwd" placeholder="Current Password" class="input-xxlarge" required="true" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="new_pwd">New Password</label>
        <div class="controls">
            <input type="password" id="new_pwd" name="new_pwd" placeholder="New Password" class="input-xxlarge" required="true" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="confirm_pwd">Confirm Password</label>
        <div class="controls">
            <input type="password" id="confirm_pwd" name="confirm_pwd" placeholder="Confirm Password" class="input-xxlarge" required="true" />
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button class="btn btn-success btn-large" type="submit">Save</button>
        </div>
    </div>
    <input type="hidden" name="digest1" id="digest1" value=""/>
    <input type="hidden" name="digest2" id="digest2" value=""/>
</form>
