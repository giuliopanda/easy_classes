<?php 
class model_pages_admin {
    private static $instance = null;
    var $table = "#__pages";
    var $limit = 20;
	/**
	 * Ritorna il singleton della classe
	 * @return  	singleton GpLoad
	**/
	public static function getInstance() {
   	    if(self::$instance == null) {
   	      $c = __CLASS__;
   	      self::$instance = new $c;
		}
		return self::$instance;
    }
    /**
     * Ritorna la lista dei valori
     */
    function getList($start = 0, $limit = 0) {
        $db = Gp::db();
        if ($limit == 0) {
            $limit = $this->limit;
        }
        $data = $db->getResults("SELECT * FROM ".$this->table);
        return $data;
    }
      /**
     * Ritorna la lista dei valori
     */
    function getRow($id) {
        $data = Gp::db()->getRow("SELECT * FROM ".$this->table." WHERE id =" .(int)$id);
        return $data;
    }
    function getForm() {
        $pages = Gp::load()->get('pages');
        $page = scandir($pages[0]);
        $options = array();
        foreach ($page as $p) {
            if (is_file($pages[0]."/".$p) && substr($p,-4) == ".php") {
                if (is_file($pages[0]."/".substr($p,0, -4).".json")) {
                    $data = file_get_contents($pages[0]."/".substr($p,0, -4).".json");
                    $data = json_decode($data, true);
                    if (json_last_error() == JSON_ERROR_NONE) {
                        $options[] = array('value'=>substr($p,0, -4),'label'=>$data['title']);
                    } else {
                         $options[] = array('value'=>substr($p,0, -4),'label'=>substr($p,0, -4)." ERROR JSON");
                    }
                } else {
                    $options[] = array('value'=>substr($p,0, -4),'label'=>substr($p,0, -4));
                }
            }
        }
        $fields = array(
        ['label'=>'Page', 'name'=>'page', 'type'=>"page", 'options' => $options],
        ['label'=>'Link', 'name'=>'link', 'type'=>"text"],
        ['label'=>'View', 'name'=>'view', 'type'=>"text"],
        ['label'=>'Title', 'name'=>'title', 'type'=>"text"],
        ['label'=>'Params', 'name'=>'params', 'type'=>"text"],
        ['label'=>'Access', 'name'=>'access', 'type'=>"text"],
        ['label'=>'Status', 'name'=>'status', 'type'=>"text"]
        );
        return $fields;
    }
    /**
     * Ritorna l'html del form da aggiungere alla pagina
     */
    function getCustomForm($pageName) {
        $pages = Gp::load()->get('pages');
        if (is_file($pages[0] ."/" . $pageName . ".json")) {
            $data = file_get_contents($pages[0] ."/" . $pageName . ".json");
            $data = json_decode($data, true);
            if (json_last_error() == JSON_ERROR_NONE) {
                if (isset($data['formFile']) && is_file($pages[0] ."/" . $data['formFile'])) {
                    ob_start();
                    require ( $pages[0] ."/" . $data['formFile']);
                    return ob_get_clean();
                } else {
                    return "";
                }
            } else {
                return "Error json parse";
            }
        } else {
            return "";
        }
    }


    function save($data) {
        $checkExistsRow = 0;
        if (isset($data['id']) && $data['id'] > 0) {
            $checkExistsRow = Gp::db()->getVar('SELECT count(*) FROM '.$this->table." WHERE id = ".Gp::db()->q($data['id']));
        }
        if (!isset($data['status']) ) {
            $data['status'] = 1;
        } else {
            $data['status'] = (int)$data['status'];
        }
        if ($checkExistsRow) {
            $id = $data['id'];
            unset($data['id']);
            return Gp::db()->update($this->table, $data, array('id'=>$id));
        } else {
            return Gp::db()->insert($this->table, $data);
        }
    }
}