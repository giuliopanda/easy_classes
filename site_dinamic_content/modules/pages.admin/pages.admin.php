<?php

require_once(dirname(__FILE__)."/model.php");
Gp::load()->append("pages_admin", 'modules', "pages.admin");
class module_pages_admin {
    //
    var $model;
    function __construct() {
        $this->model = model_pages_admin::getInstance();
    }
    /**
     * Questo il controller centrale. Deve poter essere sostituito all'intero delle pagine?
     */
    function chooseCommand($cmd) {
        switch ($cmd) {
            case 'edit':
                return $this->edit(Gp::data()->get('request.idform'));
                break;
            case 'save':
                $ris = $this->save(Gp::data()->get('request.form'));
                if ($ris['save']) {
                    return $this->list();
                } else {
                    return $this->edit(0, '', $ris['errors']);
                }
                break;
            case 'list':
            case 'close':
            default:
                return $this->list();
                break;
        }
        //var_dump (Gp::data()->get('request'));
        //print "CMD: ".$cmd;
       
    }
    /**
     * Estrae le pagine dal sito e le mostra in una tabella
     */
    function list() {
        $data = array('items'=>$this->model->getList());
        ob_start();
        Gp::load()->require("pages_admin", "table.php", $data);
        return  ob_get_clean() ;
    }
    /**
     * Mostra la form di inserimento
     */
    function edit($id=0, $msg="", $errors=array()) {
        ob_start();
        $data = array('fields'=>$this->model->getForm(), 'msg'=>$msg, 'errors'=>$errors);
        if ($id > 0) {
            $data['item'] = $this->model->getRow($id);
        }
        Gp::load()->require("pages_admin", "form.php", $data);
        return  ob_get_clean() ;
    }
    /**
     * Salva la form
     */
    function save($data) {
        $errors = array();
        if ($data['view'] == "") {
            $errors['view'] = 'is-invalid';
        }
        if ($data['link'] == "") {
            $errors['link'] = 'is-invalid';
        }
        $ris = false;
        if (count($errors) == 0) {
            $ris = $this->model->save($data);
        }
        return array('save'=>$ris, 'errors'=>$errors);
    }
    /**
     * Chiama la parte di form a seconda della pagina che si sceglie
     */
    function getForm($pageFile) {
        $formHtml = $this->model->getCustomForm($pageFile);
        return ($formHtml);
    }

}
