<?php 

$request = $_REQUEST;
if(!empty($request['task'])){
    new RequestTransport($request);
}

class RequestTransport {
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

    function addPatios(){
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
                'reference'    => $reference,
            );
            
            if(!$this->validateFields($sql))
                return false;
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
    public function query_request_transport($phpBool = false, $id = false, $params = false){
        global $wpdb;
        $table = $wpdb->prefix . $this->table;

        try{
            if(empty($params)) {
                $sql = "SELECT * FROM {$table} where orcamento = 0  ORDER BY id DESC";
            }
            
            if($id){
                $sql = "SELECT * FROM  {$table} WHERE id = {$id} AND orcamento = 0  ORDER BY id DESC";
            }
            if($params){
                $params[0] = ( $params[1] === "criado" || $params[1] === "modificado" ) 
                ? implode('-', array_reverse(explode('/', $params[0])))
                : $params[0];

                $sql = "SELECT * FROM  {$table} WHERE {$params[1]} like '%{$params[0]}%' AND orcamento = 0  ORDER BY id DESC";
            }

            $query = $wpdb->prepare($sql, '');
            if($phpBool){
               return $wpdb->get_results($query);
            }else{
                if($params) {
                    $result = $wpdb->get_results($query);
                    foreach ($result as $key => $value) {   
                      if($value->id_user){
                        $resultUserMeta = $this->getUserMeta($value->id_user);
                        $result[$key] = array_merge( (array) $value, (array) $resultUserMeta);
                      }
                    }
                    echo json_encode($result);
                    return; 
                }
                echo json_encode($wpdb->get_results($query));
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function getUserMeta($id) {
        global $wpdb;
        $table = $wpdb->prefix . 'usermeta';
        try {
            $sql = "SELECT * FROM  {$table} WHERE user_id = {$id}";
            $query = $wpdb->prepare($sql, '');
            $response =  $wpdb->get_results($query);
            $arrayReturn = array();
            $keys = [
                'cpf' => 'user_dados_pessoais_cpf',
                'rg' => 'user_dados_pessoais_rg',
                'data_nasc_responsavel' => 'user_dados_pessoais_data_de_nascimento',
                'cnpj' => 'user_dados_pessoais_cnpj',
                'inscricao_estadual' => 'user_dados_pessoais_ie',
                'email' => 'user_contato_email',
                'whatsapp' => 'user_contato_whatsapp',
                'telefone_fixo' =>'user_contato_telefone_fixo',
                'nome' => 'first_name'
            ];
            $user_data = get_user_by('id', $id);
            foreach ($response as $key => $value) {
                foreach ($keys as $k => $v) {
                    if($v === $value->meta_key){
                        if($k === 'email') {
                            $arrayReturn[$k] = empty($value->meta_value) ? $user_data->user_email : $value->meta_value;
                        }else{
                            $arrayReturn[$k] = $value->meta_value;
                        }
                    }
                }
            } 
            return $arrayReturn;
        } catch(Exeption $e) {
            return array();
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
            if(!$this->validateFields($sql))
                return false;

            $sql['observacao']  = $observacao;  
            $sql['status']      = $status;  

            $this->saveImage($_POST['id']);

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

    function saveImage($idRequest){
        global $wpdb;
        $cnh_rg = $_FILES['cnh_rg'];
        $crlv = $_FILES['crlv'];
        $dir = wp_upload_dir();
        $path = str_replace('\\','/', $dir["basedir"].'/transporte/');

        if( !empty($crlv) ) {
            $extensionCrlv = pathinfo($crlv['name'], PATHINFO_EXTENSION);
            $imageNameCrlv = base64_encode($idRequest). "-crlv." . $extensionCrlv;
            $locationCrlv = $path . $imageNameCrlv;
            $file_pattern = $path . base64_encode($idRequest). "-crlv" . ".*";
            array_map( "unlink", glob( $file_pattern ) );

            $saved = file_put_contents($locationCrlv, file_get_contents($crlv['tmp_name']));

            if($saved){
                $location = substr(
                    $locationCrlv,
                    strpos($locationCrlv, 'wp-content')
                );
            
                $this->uploadUrlFiles( 
                    array('crlv' => $location),
                    $idRequest
                );
            }
        }

        if( !empty($cnh_rg) ) {
            $extensionCnh = pathinfo($cnh_rg['name'], PATHINFO_EXTENSION);
            $imageNameCnh = base64_encode($idRequest). "-cnh-rg." . $extensionCnh;
            $locationCnh = $path. $imageNameCnh;
            $savedFile = file_put_contents($locationCnh, file_get_contents($cnh_rg['tmp_name']));
            
            if($savedFile){
                $location = substr(
                    $locationCnh,
                    strpos($locationCnh, 'wp-content')
                );
                
                $this->uploadUrlFiles(
                    array("rg_cnh" =>  $location),
                    $idRequest
                );
            }
        }
    }

    function uploadUrlFiles($data, $id){
        global $wpdb;
        try {
            $data_where = array('id' => $id);
            $saved = $wpdb->update("{$wpdb->prefix}request_transport", $data, $data_where);
    
            return  $saved;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function getRequestUser($user_id) {
        global $wpdb;
        $table = $wpdb->prefix . 'request_transport';
        try {
            $sql = "SELECT * FROM  {$table} WHERE id_user = {$user_id} AND orcamento = 0";
            $query = $wpdb->prepare($sql, '');
            return $wpdb->get_results($query);
        } catch(Exeption $e) {
            return array();
        }
    }
}