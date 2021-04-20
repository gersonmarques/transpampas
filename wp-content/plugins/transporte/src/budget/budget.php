<?php 

$request = $_REQUEST;
if(!empty($request['task'])){
    new Budget($request);
}

class Budget {
    /**
     * Table for add informations
     */
    private $table = "request_transport";

    public $status = [
        'Aguardando',
        'Em andamento',
        'Concluído',
        'Fechado'
    ];

    function __construct($args = null){
        if(!empty($args)){
            $this->rootDir = realpath(__DIR__.'/../../../../../');
            require_once  $this->rootDir . '/wp-config.php';
            $task = $args['task'];

            if($task === 'query_request_transport'){
                $this->$task(false,false,array($args['search'], $args['filter']));
            }else{
                $this->$task();
            }
        }
    }

    /**
     * The query of areas
     * @return bool|string
     */
    public function query_request_transport($phpBool = false, $id = false, $params = false){
        global $wpdb;
        $table = $wpdb->prefix . $this->table;

        try{
            if(empty($params)) {
                $sql = "SELECT * FROM {$table} where orcamento = 1";
            }
            
            if($id){
                $sql = "SELECT * FROM  {$table} WHERE id = {$id} AND orcamento = 1";
            }
            if($params){
                $params[0] = ( $params[1] === "criado" || $params[1] === "modificado" ) 
                ? implode('-', array_reverse(explode('/', $params[0])))
                : $params[0];

                $sql = "SELECT * FROM  {$table} WHERE {$params[1]} like '%{$params[0]}%' AND orcamento = 1";
            }

            $query = $wpdb->prepare($sql, '');
            if($phpBool){
               return $wpdb->get_results($query);
            }else{
                if($params) {
                    $result = $wpdb->get_results($query);
                    echo json_encode($result);
                    return; 
                }
                echo json_encode($wpdb->get_results($query));
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function getSource($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'request_transport_source';
        try {
            $sql = "SELECT * FROM  {$table} WHERE id = {$id}";
            $query = $wpdb->prepare($sql, '');
            return $wpdb->get_results($query);
        } catch(Exeption $e) {
            return array();
        }
    }

    public function getTarget($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'request_transport_target';
        try {
            $sql = "SELECT * FROM  {$table} WHERE id = {$id}";
            $query = $wpdb->prepare($sql, '');
            return $wpdb->get_results($query);
        } catch(Exeption $e) {
            return array();
        }
    }
    
    public function updateRequest(){
        global $wpdb;

        $modelo_veiculo = empty($_POST['modelo_veiculo']) ? '' : $_POST['modelo_veiculo'];
        $ano_veiculo = empty($_POST['ano_veiculo']) ? '' : $_POST['ano_veiculo'];
        $fipe_veiculo = empty($_POST['fipe_veiculo']) ? '' : $_POST['fipe_veiculo'];
        $situacao_veiculo = empty($_POST['situacao_veiculo']) ? '' : $_POST['situacao_veiculo'];
        $cor_veiculo = empty($_POST['cor_veiculo']) ? '' : $_POST['cor_veiculo'];
        $placa_veiculo = empty($_POST['placa_veiculo']) ? '' : $_POST['placa_veiculo'];
        $rg_cnh_veiculo  = empty($_POST['rg_cnh_veiculo']) ? ''  : $_POST['rg_cnh_veiculo'];
        $crlv_veiculo  = empty($_POST['crlv_veiculo']) ? ''  : $_POST['crlv_veiculo'];
        $observacao  = empty($_POST['observacao']) ? ''  : $_POST['observacao'];
        $status  = empty($_POST['status']) ? ''  : $_POST['status'];

        try{
            $sql = array(
                'modelo_veiculo'    => $modelo_veiculo,
                'ano_veiculo'       => $ano_veiculo,
                'fipe'              => $fipe_veiculo,
                'situacao_veiculo'  => $situacao_veiculo,
                'cor'               => $cor_veiculo,
                'placa'             => $placa_veiculo,
            );
            $where = array('id' => $_POST['id']);
            if(!$this->validateFields($sql)) return false;
            
            $sql['observacao']  = $observacao;  
            $sql['status']      = $status;

            $updated = $wpdb->update($wpdb->prefix . $this->table, $sql, $where);
            $result = $updated === false ? 0 : 1;
            echo json_encode($result);
        }catch(Exception $e){
            echo json_encode($e->getMessage());
        }
    }

    public function deleteRequest(){
        global $wpdb;
        date_default_timezone_set("America/Sao_Paulo");
        $dir = wp_upload_dir();
        $path = str_replace('\\','/', $dir["basedir"].'/transporte/');
        $id = empty($_POST['id']) ? '' : $_POST['id'];
        $file_pattern = $path . base64_encode($id). "-crlv" . ".*";
        array_map( "unlink", glob( $file_pattern ) );

        $sql = "SELECT * FROM  {$wpdb->prefix}request_transport WHERE id = {$id}";

        try{
            $query = $wpdb->prepare($sql, '');
            $data = $wpdb->get_results($query);

            $where = array('id'=> $_POST['id']);
            $result =  $wpdb->delete($wpdb->prefix . $this->table, $where);
            if($result) {
                $target =  $wpdb->delete("{$wpdb->prefix}request_transport_source",array('origem_id'=> $data[0]->origem_id));
                $source =  $wpdb->delete("{$wpdb->prefix}request_transport_target", array('destino_id'=> $data[0]->destino_id));
            }
            echo json_encode($result);
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

    function getRequestUser($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'request_transport';
        try {
            $sql = "SELECT * FROM  {$table} WHERE id_user = {$user_id} AND orcamento = 1";
            $query = $wpdb->prepare($sql, '');
            return $wpdb->get_results($query);
        } catch(Exeption $e) {
            return array();
        }
    }
}