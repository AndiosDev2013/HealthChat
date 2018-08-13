<?php echo $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/default/scripts/hash.js"></script>

<div class="container body_height">
    <div class="row-fluid">
        <div class="span4"></div>
        <div class="span4" style="margin-top: 50px;">
            <form class="form-signin" method="post" action="<?php echo $this->config->site_url(); ?>member/reset_password" onsubmit="return on_submit();">
                <div class="control-group">
                    <label class="control-label" for="email">E-mail Address</label>
                    <div class="controls">
                        <input type="text" id="username" name="email" placeholder="E-mail Address" style="width:100%;" value="<?php if (isset($username)) {
    echo $username;
} ?>" required/>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <button type="submit" class="btn btn-inverse" style="width:100px;">Reset</button>
                        <p style="display: inline-block">Found your password?</p>
                        <a href="login">Login</a>
                    </div>
                </div>
                <input type="hidden" name="digest" id="digest" value=""/>
            </form>
        </div>
        <div class="span4"></div>
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
    ?>
</div>
<?php echo $this->load->view('common/footer'); ?>