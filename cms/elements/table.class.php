<?php
class HTMLTable
{  
    var $structure = array();
    var $data = array();
    var $attr = array();
    function __construct($structure, $data, $attr = NULL) {
        $this->structure = $structure;
        if ($attr != NULL) {
            $this->attr = $attr;
        }
        $k = 0;
        foreach ($data as $d) {
            $row = new StdClass();
            $k++;
            $row->__count = $k;
            foreach ($structure as $cols) { 
                $tField = str_replace(array(" ","-"), "_", $cols->field);
                if (is_array($d)) {
                    if (array_key_exists($tField, $d)) {
                        $row->$tField = $d[$tField];
                    }
                } elseif (is_object($d)) {
                    if (property_exists($d, $tField)) {
                        $row->$tField =  $d->$tField;
                    }
                }
            } 
            $this->data[] = $row;
        }
    }

    private function attr($eventName) {
        if (property_exists($this->attr, $eventName)) {
            echo $this->attr->$eventName;
        } 
    }

    function draw($templatePath = NULL) {
        ob_start();
        if ($templatePath != NULL && is_file($templatePath)) {
            require ($templatePath);
        } else {
            require (dirname(__FILE__)."/table.html.php");
        }
        return ob_get_clean();
    }
}