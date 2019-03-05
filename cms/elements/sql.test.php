<?php
require ("sql.php");


$structure = json_decode('[
        {"label":"ID", "table":"t", "field":"id", "as":"a_id"},
        {"label":"NAME", "table":"t", "field":"name", "as":"a_name"},
        {"label":"SURNAME", "table":"t", "field":"surname", "as":"a_surname"},
        {"label":"SURNAME", "table":"c", "field":"other", "as":"a_other"}
]');

$from = json_decode('[
        {"table":"test", "as":"t"},
        {"table":"conn", "as":"c", "join":"left join", 
            "on": {
                "table1":"c",
                "field1":"conn_id",
                "compare":"=",
                "table2":"t",
                "field2":"id"
            }
        }
]');

var_dump ($from);

$sql = new buildSelectSQL($structure, $from);
echo $sql->getQuery();

