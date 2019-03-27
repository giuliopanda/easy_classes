<?php 
$router = gpRouter::getInstance(); 
$registry = gpRegistry::getInstance();
?>
<div class="list-group">
    <?php foreach ($data as $key=>$value): ?>
        <?php  
        if ($router->isActive($key, $router->getLink($registry->get('request')), array('page', 'view'))) {
            echo "<a class=\"list-group-item list-group-item-action active\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
        } else {
            echo "<a class=\"list-group-item list-group-item-action\" href=\"".$router->getLink($key)."\">".$value."</a> "; 
        };
        ?>
    <?php endforeach; ?>
</div>