<ul class="nav navbar-nav">

    <?php
    $menu = $this->session->userdata("menu");
    
    foreach ($menu as $i => $value) {
        ?>
        <li class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?php echo ucwords($value["title"]) ?><span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <?php
                if (isset($value["node"])) {
                    foreach ($value["node"] as $j => $val) {
                        ?>
                        <li><a href='<?php echo base_url() . $val["url"] ?>' ><?php echo ucwords($val["title"]) ?></a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </li>
        <?php
    }
    ?>
</ul>


