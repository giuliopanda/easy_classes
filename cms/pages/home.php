<html>
  <?php
  require ($router->getDir()."themes/".$template."/head.php");
  ?>
  <body>
    <div class="ecs-content ecs-ml1 ecs-mr1">
      <div class="ecs-d-flex  ecs-align-items-center">
        <h3>Home page</h3>
      </div>
      <p>
      Sei in home page
      </p> 
      <?php echo $table->draw($table_template); ?>
    </div>
  </body>
</html>