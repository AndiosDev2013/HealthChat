<h3>Login</h3>
<form class="form-signin" method="post" action="<?php echo $this->config->site_url(); ?>member/login" onsubmit="return on_login();">
    <div class="control-group">
        <label class="control-label" for="email">E-mail Address</label>
        <div class="controls">
            <input type="text" id="email" class="input-xxlarge" name="email" placeholder="E-mail Address" value="<?php echo (isset($email) ? $email : ''); ?>" required="true" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="password">Password</label>
        <div class="controls">
            <input type="password" id="password" class="input-xxlarge" name="password" placeholder="Password" required="true" />
        </div>
    </div>
    <button class="btn btn-success btn-large" type="submit">Login</button>
    <a href="reset_password">Forgot your password?</a>
    <a href="signup">Don't have an account?</a>
    <input type="hidden" name="digest" id="digest" value=""/>
</form>
