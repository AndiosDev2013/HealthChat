<?php

require_once 'Model.php';

class ResourceModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_RESOURCE, 'rid');
    }

}

?>
