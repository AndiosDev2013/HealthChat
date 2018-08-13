<div class="content-header">
    <div class="content-tools">
        <!--a href="<?php echo $this->config->site_url(); ?>user/register">Register a new user</a-->
    </div>
    <h3>Users</h3>
</div>
<div class="content-body">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Name</th>
                <th>E-mail Address</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (isset($entities)) {
                $index = 1;
                foreach ($entities as $entity):
                    ?>
                    <tr>
                        <td><?php //echo $index;     ?></td>
                        <td><?php echo $entity->name; ?></td>
                        <td><?php echo $entity->email; ?></td>
                        <td>
                            <?php if ($entity->active == 1) { ?>
                                <a href="<?php echo $this->config->site_url(); ?>user/suspense/<?php echo $entity->uid; ?>">Suspense</a>
                            <?php } else { ?>
                                <a href="<?php echo $this->config->site_url(); ?>user/activate/<?php echo $entity->uid; ?>">Activate</a>
                            <?php } ?>
                            &nbsp;&nbsp;
                            <a onclick="return (confirm('Continue?'));" href="<?php echo $this->config->site_url(); ?>user/delete/<?php echo $entity->uid; ?>"><img src="<?php echo $this->config->site_url(); ?>theme/default/images/trash.png"/>

                        </td>
                    </tr>
                    <?php
                    $index++;
                endforeach;
            }
            ?>
        </tbody>
    </table>

    <div class="pagination text-center"><?php echo $pagination ?></div>
</div>
