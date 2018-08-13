<?= $this->load->view('common/header'); ?>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/bootstrap/js/bootstrap-alert.js"></script>
<script type="text/javascript" src="<?php echo $this->config->site_url(); ?>theme/default/scripts/hash.js"></script>
<div class="container body_height">
    <h2 class="head2">Admin Panel - Registor User</h2>
    <div class="row-fluid">
        <div class="span3">
            <?php $this->load->view('common/admin_panel'); ?>
        </div>
        <div class="span9">
            <div class="row-fluid">
                <div class="span12">
                    <div class="span5">
                        <div style="padding: 10px;">
                            <img style="width:400px;height:250px;border:15px solid white;" src="<?php echo base_url() . $entity->picture_url; ?>"  alt="no picture"/>
                        </div>
                    </div>
                    <div class="span7">
                        <form class="form-horizontal" method="post" action="<?php echo $this->config->site_url(); ?>user/save" onsubmit="return on_submit();" enctype="multipart/form-data">
                            <div class="control-group ">
                                <label class="control-label" for="name">User Name</label>
                                <div class="controls">
                                    <input type="text" id="name" name="name" value="<?php echo (isset($entity)) ? $entity->name : ''; ?>" placeholder="User Name" required/>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="fullName">Full Name</label>
                                <div class="controls">
                                    <input type="text" id="fullName" name="fullName" value="<?php echo (isset($entity)) ? $entity->fullName : ''; ?>" placeholder="Full Name" required>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="gendor">Gendor</label>
                                <div class="controls">
                                    <select id="gendor" name="gendor" required>
                                        <option value="0" <?php echo (isset($entity) ? (($entity->gendor == 0) ? 'selected="true"' : '') : ''); ?>>Man</option>
                                        <option value="1" <?php echo (isset($entity) ? (($entity->gendor == 1) ? 'selected="true"' : '') : ''); ?>>Woman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="birth">Birthday</label>
                                <div class="controls">
                                    <input type="text" id="birth" name="birth" value="<?php echo (isset($entity)) ? date('m/d/Y', strtotime($entity->birth)) : ''; ?>" placeholder="Birthday"/>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="birth">Occupation</label>
                                <div class="controls">
                                    <input type="text" id="occupation" name="occupation"value="<?php echo (isset($entity)) ? $entity->occupation : ''; ?>" placeholder="Occupation"/>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="phone">Phone</label>
                                <div class="controls">
                                    <input type="text" id="phone" name="phone"  value="<?php echo (isset($entity)) ? $entity->phone : ''; ?>" placeholder="Phone"/>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="email">E-Mail</label>
                                <div class="controls">
                                    <input type="text" id="email" name="email" value="<?php echo (isset($entity)) ? $entity->email : ''; ?>" placeholder="E-Mail" required="" />
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="pwd">Password</label>
                                <div class="controls">
                                    <input type="password" id="pwd" name="pwd" placeholder="Password" />
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="cpwd">Confirm Password</label>
                                <div class="controls">
                                    <input type="password" id="cpwd" name="cpwd" placeholder="Confirm Password"/>
                                </div>
                            </div>
                            <div class="control-group ">
                                <label class="control-label" for="level">Level</label>
                                <div class="controls">
                                    <select id="level" name="level">
                                        <option value="">----- SELECT -----</option>
                                        <option value="1" <?php echo (isset($entity) ? (($entity->level == 1) ? 'selected="true"' : '') : ''); ?>>Super Administrator</option>
                                        <option value="2" <?php echo (isset($entity) ? (($entity->level == 2) ? 'selected="true"' : '') : ''); ?>>Administrator</option>
                                    </select>
                                </div>
                            </div>
                            <!--div class="control-group ">
                                <label class="control-label" for="interest">Interests</label>
                                <div class="controls">
                                        <input type="text" id="interests" name="interests" value="<?php echo (isset($entity)) ? $entity->interests : ''; ?>" placeholder="Interests" />
                                </div>
                            </div-->
                            <div class="control-group">
                                <label class="control-label" for="avatar">Avatar</label>
                                <div class="controls">
                                    <input type="file" id="avatar" name="avatar" />
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <input type="hidden" name="digest" id="digest" value=""/>
                                    <input type="hidden" name="user_id" id="user_id" value="<?php echo (isset($entity)) ? $entity->uid : 0; ?>"/>
                                    <button type="submit" class="btn btn-inverse" style="width: 100px;" <?php if (isset($entity)) { ?>name='active' value='<?php echo $entity->active?>'<?php } ?>>Save</button>
                                    <?php if (isset($entity)) { ?>
                                    <button type="submit" name="active" value="<?php echo ($entity->active == 0 ? 1 : 0); ?>" class="btn btn-inverse" style="width: 100px;"><?php echo ($entity->active == 0 ? 'Activate' : 'Suspense'); ?></button>
                                    <?php } ?>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="span3"></div>
    </div>
</div>
<script type="text/javascript">

                function on_submit()
                {
                    if ($('#pwd').val() != "" && $('#cpwd').val() != "")
                    {
                        if ($('#pwd').val() != $('#cpwd').val()) {
                            alert('The password and confirm Password is different.');
                            return false;
                        }

                        $('#digest').val(getDigest(pwd.value));
                        $('#pwd').val('');
                        $('#cpwd').val('');
                    }
                    return true;
                }
</script>    

<?= $this->load->view('common/footer'); ?>
