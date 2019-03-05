<?php
$structure = json_decode('{
    "data":[
        {"label":"ID","field":"a_id"},
        {"label":"NAME","field":"a_name"},
        {"label":"SURNAME","field":"a_surname"}
    ]
}');

$data = json_decode('[
    {"a_id":"1","a_name":"Giulio", "a_surname":"Panda"},
    {"a_id":"2","a_name":"Mario", "a_surname":"Cane"},
    {"a_id":"3","a_name":"Marco", "a_surname":"Koala"},
    {"a_id":"4","a_name":"Sara", "a_surname":"Lucertola"}
]');

$table = new gpHTMLTable();
var_dump($data);
$table->setStructure($structure->data);
$table->setData($data);

echo $table->draw();

class gpHTMLTable
{
    var $structure = array(); // la struttura della tabella
    var $data = array(); // I dati
    var $htmlHead = array();
    var $htmlBody = array();
    function setStructure($obj) {
        $this->structure = $obj;
    }
    function setData($obj) {
        $this->data = $obj;
    }
    function getHead() {
       
        foreach ($this->structure as $cols) {
           $this->htmlHead[] = '<th>'. $cols->label.'</th>';
         }
       return implode ("", $this->htmlHead);
    }

    function getBody() {
        foreach ($this->data as $d) {
            $row = array();
            foreach ($this->structure as $cols) { 
                $tField = $cols->field;
                if (is_array($d)) {
                    if (array_key_exists($tField, $d)) {
                        $row[] = '<td>'. $d[$tField].'</td>';
                    }
                } elseif (is_object($d)) {
                    if (property_exists($d, $tField)) {
                        $row[] = '<td>'. $d->$tField.'</td>';
                    }
                }
            } 
            $this->htmlBody[] = "<tr>".implode ("",$row)."</tr>";
        }
        return implode ("",$this->htmlBody);
    }

    function draw() {
        ob_start();
        ?>
        <table>
        <thead>
            <?php echo $this->getHead(); ?>
        </thead>
        <body>
            <?php echo $this->getBody(); ?>
        </body>
        </table>
        <?php
        return ob_get_clean();
    }
}