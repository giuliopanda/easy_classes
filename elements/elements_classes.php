<?php
/**
 * CREATE TABLE
 */
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
                $tField = str_replace(array(" ","-"), "_", $cols->as);
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

/**
 * Create MYSQL SELECT
 */
class buildSelectSQL
{  
    var $select = array();
    var $from = array();
    var $fromJoin = array();
    var $where = array();

    function __construct($select, $from, $where = "") {
        foreach ($select as $s) {
            $this->addSelect($s->table, $s->field, $s->as);
        }
        foreach ($from as $f) {
            if (property_exists($f, 'join' )) {
                $this->addForm($f->table, $f->as, $f->join, $f->on);
            } else if (property_exists($f, 'table' )) {
              
                $this->addForm($f->table, $f->as);
            }
        }
    }
    /**
     * ADD FORM query part
     * @param String $table Table name
     * @param String $field Field name
     * @param String $as field alias
     */
    function addSelect($table, $field, $as = "") {
        if ($as == "") {
            $as = $table."_".$field;
        }
        $string = $this->qn($table).".".$this->qn($field) ." AS ".$this->qn($as);
        if (!in_array($string, $this->select)) {
            $this->select[] = $string;
        }
    }
    /**
     * ADD FORM query part
     * @param String $table Table name
     * @param String $as table alias
     * @param String $join  the join type
     * @param Object $on  (table1, field1, compare, table2, field2).
     */
    function addForm($table, $as, $join = null, $on = null) {
        if ($join == null) {
            $var = $this->qn($table)." ".$as;
            if (!in_array($var, $this->from)) {
                $this->from[] = $var;
            }
        } elseif ($join != null && is_object($on)) {
            $string = strtoupper($join)." ".$this->qn($table)." ".$as." ON ".$this->qn($on->table1).".".$this->qn($on->field1)." ".$on->compare. " ".$this->qn($on->table2).".".$this->qn($on->field2);
            if (!in_array($string, $this->from)) {
                $this->fromJoin[] =$string;
            }
        }
    }
    /**
     * GET sql query
     */
    function getQuery() {

        if (count($this->fromJoin) > 0) {
            $fromJoin = " ".implode(" ", $this->fromJoin);
        } else {
            $fromJoin = "";
        }
        return "SELECT ".implode(", ", $this->select)." FROM ".implode(",", $this->from)." ".$fromJoin;
    }

     /**
     * QUOTE name or table
     * @param String $val
     * @return String
     */
    function qn($val) {
        return '`'.trim($val).'`';
    }
    /**
     * Quote value
     *  @param String $val
     * @return String
     */
    function quote($val) {
        return "'".$this->mysqli->real_escape_string($val)."'";
    }
}