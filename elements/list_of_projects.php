<html>
<?php
$query_structure = json_decode('[
        {"label":"ID", "table":"p", "field":"id", "as":"p_id"},
        {"label":"Title", "table":"p", "field":"title", "as":"p_name"},
        {"label":"Alias", "table":"p", "field":"alias", "as":"p_alias"},
        {"label":"Params", "table":"p", "field":"params", "as":"p_params"}
]');
$query_from = json_decode('[
        {"table":"gp_projects", "as":"p"}
]');
$table_attr = json_decode('{
    "table":"class=\"ecs-table ecs-table-bordered\"",
    "thead":"class=\"ecs-thead-light\"",
    "tbody":"",
    "th":"data-label=\"{{ field }}\"",
    "tr":"data-label=\"{{ __count }}\"",
    "td":""
    }
');


$sql = new buildSelectSQL($query_structure, $query_from);
//print ($sql->getQuery());
$data = $db->getResults($sql->getQuery());
//var_dump ($data);
$table_template = $router->getDir()."themes/".$template."/table.html.php";
$table = new HTMLTable($query_structure, $data, $table_attr);


require ($router->getDir()."themes/".$template."/head.php");
?>
<body>
  <div class="ecs-content ecs-ml1 ecs-mr1">
    <div class="ecs-d-flex  ecs-align-items-center">
      <h3>Elenco progetti</h3>
      <div class="ecs-ml-auto">
        <div class="ecs-btn ecs-btn-1">Crea un nuovo progetto</div>
      </div>
    </div>
    <p>
    Per ora è una demo in cui non ci sono permessi, utenti, aree etc...<br>
    Ogni utente può creare nuovi progetti, duplicarli o creare istanze da dei master
    </p> 
    <?php echo $table->draw($table_template); ?>
  </div>
</body>
</html>