<h3>Sign Up For Free</h3>
<form class="form-signup" method="post" action="<?php echo $this->config->site_url(); ?>member/signup" onsubmit="return on_signup();">
    <div class="control-group">
        <label class="control-label" for="email">E-mail Address</label>
        <div class="controls">
            <input type="email" id="email" class="input-xxlarge" name="email" placeholder="E-mail Address" value="<?php echo (isset($email) ? $email : ''); ?>" required="true" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">Password</label>
        <div class="controls">
            <input type="password" id="password" class="input-xxlarge" name="password" placeholder="Password" required="true" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">Confirm Password</label>
        <div class="controls">
            <input type="password" id="confirm_pwd" class="input-xxlarge" name="confirm_pwd" placeholder="Confirm Password" required="true" />
        </div>
    </div>
    <button class="btn btn-success btn-large" type="submit">Sign Up</button>
    <a href="login">Already have an account?</a>
    <input type="hidden" name="digest" id="digest" value=""/>
</form>
