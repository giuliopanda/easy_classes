<?php 
$router = GpRouter::getInstance(); 
$registry = GpRegistry::getInstance();
?><div class="list-group" <?php echo Gp::log()->getDataLog(); ?>>
    <?php foreach ($items as $key=>$value): ?>
        <?php  
        if ($router->isActive($key, $router->getLink($registry->get('request')), array('page', 'id'))) {
            echo "<a class=\"list-group-item list-group-item-action active\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
        } else {
            echo "<a class=\"list-group-item list-group-item-action\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
        };
        ?>
    <?php endforeach; ?>
</div>