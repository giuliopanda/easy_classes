<?php
require_once("table.class.php");
$structure = json_decode('[
        {"label":"ID","field":"a_id"},
        {"label":"NAME","field":"a_name"},
        {"label":"SURNAME","field":"a_surname"}
]');

$data = json_decode('[
    {"a_id":"1","a_name":"Giulio", "a_surname":"Panda"},
    {"a_id":"2","a_name":"Mario", "a_surname":"Cane"},
    {"a_id":"3","a_name":"Marco", "a_surname":"Koala"},
    {"a_id":"4","a_name":"Sara", "a_surname":"Lucertola"}
]');

$attr = json_decode('{
    "table":"class=\"ecs-table ecs-table-bordered\"",
    "thead":"class=\"ecs-thead-light\"",
    "tbody":"",
    "th":"data-label=\"{{ field }}\"",
    "tr":"data-label=\"{{ __count }}\"",
    "td":""
    }
');
$template = NULL;
$table = new HTMLTable($structure, $data, $attr);
echo $table->draw($template);