<div class="content-header">
    <div class="content-tools">
        <!--a href="<?php echo $this->config->site_url(); ?>topic/register">Register a new topic</a-->
    </div>
    <h3>Topics</h3>
</div>
<div class="content-body">
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th></th>
                <th>Title</th>
                <th>Description</th>
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
                        <td><?php echo $entity->title; ?></td>
                        <td><?php echo $entity->desc; ?></td>
                        <td>
                            <a onclick="return (confirm('Continue?'));" href="<?php echo $this->config->site_url(); ?>topic/delete/<?php echo $entity->tid; ?>"><img src="<?php echo $this->config->site_url(); ?>theme/default/images/trash.png"/>
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
