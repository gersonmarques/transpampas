<?php 

$request = $_REQUEST;
if(!empty($request['task'])){
    new Courtyards($request);
}

class Courtyards{
    /**
     * Table for add informations
     */
    private $table = "patios";

    function __construct($args = null){
        if(!empty($args)){
            $this->rootDir = realpath(__DIR__.'/../../../../../');
            require_once  $this->rootDir . '/wp-config.php';
            $task = $args['task'];

            if($task === 'query_patios'){
                $this->$task(false,false,array($args['search'], $args['filter']));
            }else{
                $this->$task();
            }
           
        }
    }

    function addPatio(){
        global $wpdb;
        global $current_user;
        date_default_timezone_set("America/Sao_Paulo");

        $title = empty($_POST['title']) ? '' : $_POST['title'];
        $cep = empty($_POST['cep']) ? '' : $_POST['cep'];
        $street = empty($_POST['street']) ? '' : $_POST['street'];
        $number = empty($_POST['number']) ? '' : $_POST['number'];
        $neighborhood = empty($_POST['neighborhood']) ? '' : $_POST['neighborhood'];
        $city = empty($_POST['city']) ? '' : $_POST['city'];
        $state  = empty($_POST['state']) ? ''  : $_POST['state'];
        $reference  = empty($_POST['reference']) ? ''  : $_POST['reference'];
        try{
            $sql = array(
                'title'        => $title,
                'cep'          => $cep,
                'street'       => $street,
                'number'       => $number,
                'neighborhood' => $neighborhood,
                'city'         => $city,
                'state'        => $state,
            );
            
            if(!$this->validateFields($sql))
                return false;
            $sql['reference'] = $reference;                
            $table = $wpdb->prefix . $this->table;
            echo json_encode($wpdb->insert($table, $sql));
        }catch(Exception $e){
            echo json_encode($e->getMessage());
        }
    }
    /**
     * The query of areas
     * @return bool|string
     */
    public function query_patios($phpBool = false, $id = false, $params = false){
        global $wpdb;
        $table = $wpdb->prefix . $this->table;
        
        try{
            if(empty($params)) {
                $sql = "SELECT * FROM {$table}";
            }
            
            if($id){
                $sql = "SELECT * FROM  {$table} WHERE id = {$id}";
            }
            if($params){
                $sql = "SELECT * FROM  {$table} WHERE {$params[1]} like '%{$params[0]}%'";
            }

            $query = $wpdb->prepare($sql, '');
            if($phpBool){
               return $wpdb->get_results($query);
            }else{
                echo json_encode($wpdb->get_results($query));
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }
    
    public function updatePatio(){
        global $wpdb;

        $title = empty($_POST['title']) ? '' : $_POST['title'];
        $cep = empty($_POST['cep']) ? '' : $_POST['cep'];
        $street = empty($_POST['street']) ? '' : $_POST['street'];
        $number = empty($_POST['number']) ? '' : $_POST['number'];
        $neighborhood = empty($_POST['neighborhood']) ? '' : $_POST['neighborhood'];
        $city = empty($_POST['city']) ? '' : $_POST['city'];
        $state  = empty($_POST['state']) ? ''  : $_POST['state'];
        $reference  = empty($_POST['reference']) ? ''  : $_POST['reference'];

        try{
            $sql = array(
                'title'        => $title,
                'cep'          => $cep,
                'street'       => $street,
                'number'       => $number,
                'neighborhood' => $neighborhood,
                'city'         => $city,
                'state'        => $state,
            );
            $where = array('id' => $_POST['id']);
            if(!$this->validateFields($sql))
                return false;
            $sql['reference'] = $reference;
            echo  json_encode($wpdb->update($wpdb->prefix . $this->table, $sql, $where));
        }catch(Exception $e){
            echo json_encode($e->getMessage());
        }
    }

    public function deletePatio(){
        global $wpdb;
        date_default_timezone_set("America/Sao_Paulo");

        $id = empty($_POST['id']) ? '' : $_POST['id'];
       
        try{
            $where = array('id'=>$_POST['id']);
            echo json_encode($wpdb->delete($wpdb->prefix . $this->table, $where));
        }catch(Exception $e){
            echo $e->getMessage();
        }
    }

    public function validateFields($fields){

        if(empty($fields)){
            return false;
        }

        foreach( $fields as $key => $value ){
            if(empty($value))
                return false;
            if(is_array($value)){
                foreach($value as $k => $v){
                    if(empty($v)){
                        return false;
                    }
                }
            }        
        }
        return true;
    }

}