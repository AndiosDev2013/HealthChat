<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
    <head>
        <title>HealthChat Admin</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/bootstrap/css/bootstrap-overrides.css" />
        <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/font-awesome/css/font-awesome.min.css" />
        <!--[if IE 7]>
          <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/font-awesome/css/font-awesome-ie7.min.css">
        <![endif]-->
        <!--[if lt IE 9]>
            <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="<?php echo $this->config->base_url(); ?>theme/default/css/style.css" />
    </head>
    <body class="<?php echo $module_name; ?><?php if (isset($user) && $user != null) { ?> with-logged-in<?php } else { ?> with-not-logged-in<?php } ?>">
        <div class="navbar navbar-inverse">
            <div class="navbar-inner">
                <div class="container-fluid">
                    <a class="brand" href="<?php echo $this->config->base_url()?>">
                        <span class="icon-home"></span>
                        HealthChat Admin
                    </a>
                    <ul class="nav pull-right">
                        <?php if (isset($user) && $user != null) { ?>
                            <li class="settings">
                                <a><i class="icon-user"></i>
                                    <span class="hidden-phone">Welcome, </span><?php echo $user->display_name; ?>
                                </a>
                            </li>
                            <li class="settings">
                                <a href="<?php echo $this->config->base_url() . 'member/profile' ?>" role="button">
                                    <i class="icon-cog"></i>
                                </a>
                            </li>
                            <li class="settings">
                                <a href="<?php echo $this->config->base_url() . 'member/logout' ?>" role="button">
                                    <i class="icon-share-alt"></i>
                                </a>
                            </li>
                        <?php } else { ?>
                            <!--li class="settings">
                                <a href="<?php echo $this->config->base_url() . 'member/signup' ?>" role="button">
                                    <i class="icon-user"></i>
                                    <span class="title">Sign Up</span>
                                </a>
                            </li-->                            
                            <li class="settings">
                                <a href="<?php echo $this->config->base_url() . 'member/login' ?>" role="button">
                                    <i class="icon-reply"></i>
                                    <span class="title">Login</span>
                                </a>
                            </li>                            
                        <?php } ?>

                    </ul>
                </div>
            </div>
        </div>
        <div id="sidebar">
            <ul id="menu" class="<?php echo (!isset($module_name) || $module_name == MODULE_NAME_HOME) ? 'menu-home' : 'menu-not-home'; ?>">
                <li class="<?php echo (isset($mtype) && $mtype == MODULE_TYPE_HOME ? 'active' : ''); ?>" title="Home">
                    <a href="<?php echo $this->config->base_url(); ?>">
                        <span class="icon-home"></span>
                        <span class="title">Home</span>
                    </a>
                </li>
                <li class="<?php echo (isset($mtype) && $mtype == MODULE_TYPE_TOPIC ? 'active' : ''); ?>" title="Topics">
                    <a href="<?php echo $this->config->base_url(); ?>topic">
                        <span class="icon-user"></span>
                        <span class="title">Topics</span>
                    </a>
                </li>
                <li class="<?php echo (isset($mtype) && $mtype == MODULE_TYPE_USER ? 'active' : ''); ?>" title="Users">
                    <a href="<?php echo $this->config->base_url(); ?>user">
                        <span class="icon-user"></span>
                        <span class="title">Users</span>
                    </a>
                </li>
            </ul>
        </div>
        <div class="content">
            <div class="container-fluid">
                <div id="pad-wrapper">
                    <?php
                    if (isset($msg) && $msg) {
                        if ($msg->status == MSG_TYPE_SUCCESS) {
                            ?>
                            <div class="alert alert-success"><i class="icon-ok-sign"></i>
                            <?php } elseif ($msg->status == MSG_TYPE_INFO) { ?>
                                <div class="alert alert-info"><i class="icon-exclamation-sign"></i>
                                <?php } elseif ($msg->status == MSG_TYPE_WARNING) { ?>
                                    <div class="alert alert-warning"><i class="icon-warning-sign"></i>
                                    <?php } elseif ($msg->status == MSG_TYPE_ERROR) { ?>
                                        <div class="alert alert-error"><i class="icon-remove-sign"></i>
                                        <?php } else { ?>
                                            <div class="alert alert-error"><i class="icon-remove-sign"></i>
                                            <?php } ?>
                                            <a class="close" data-dismiss="alert">&times;</a>
                                            <?php echo $msg->data; ?>
                                        </div>
                                    <?php } ?>
