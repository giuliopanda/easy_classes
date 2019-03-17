<html>
  <?php
  require ($router->getDir()."themes/".$template."/head.php");
  ?>
  <body>
    <div class="ecs-content ecs-ml1 ecs-mr1">
      <div class="ecs-d-flex  ecs-align-items-center">
        <h3>pagina interna</h3>
      </div>
      <p>
      Ecco la pagina principale
      </p> 
      <?php echo $table->draw($table_template); ?>
    </div>
  </body>
</html>