<?php
    function getInfo(WP_REST_Request $request, $raw = false, $onlyId = false) 
    {
        
        if( (!isset($request['cpf']) || empty($request['cpf'])) && (!isset($request['cnpj']) || empty($request['cnpj'])) ) {
            return array(
                "erro_code" => "400",
                "message" => "Parâmetros obrigatório não enviado"
            );
        }

        $cpf = $request['cpf'];
        $cnpj = $request['cnpj'];

        if(empty($cpf)){
            $where = "WHERE  meta_key = 'user_dados_pessoais_cnpj' and  meta_value  = '{$cnpj}'"; 
        }else{
            $where = "WHERE  meta_key = 'user_dados_pessoais_cpf' and  meta_value  = '{$cpf}'";
        }
        
        global $wpdb;
        try {
            $results = $wpdb->get_results( "SELECT user_id FROM {$wpdb->prefix}usermeta {$where}", ARRAY_A);
            $id = $results[0]['user_id'];
            
            if($onlyId) return $id;
            
            $query = "SELECT  wu.display_name, wum.meta_key, wum.meta_value FROM {$wpdb->prefix}usermeta wum
                INNER JOIN  {$wpdb->prefix}users wu on  wu.ID = '{$id}'
                WHERE user_id = '{$id}' AND wum.meta_key like 'user_%'";
            $data = $wpdb->get_results($query, ARRAY_A);

            if($raw) return $data;
            
            foreach ($data as $key => $value) {
                if($data[$key]['meta_key'] == 'user_contato_whatsapp'){
                    $data[$key]['meta_value'] = "*********". substr($value['meta_value'], -2);
                }
                if($data[$key]['meta_key'] == 'user_contato_telefone_fixo'){
                    $data[$key]['meta_value'] = "********". substr($value['meta_value'], -2);
                }
                if($data[$key]['meta_key'] == 'user_contato_email'){
                    $mail = $value['meta_value'];
                    if(empty($mail)) continue;
                    
                    $data[$key]['meta_value'] = substr_replace($mail, '*****', 1, strpos($mail, '@') - 2);
                }
            }
            return $data;
        } catch (\Throwable $th) {
            return array();
        }
        
    }

    function saveInfo(WP_REST_Request $request)
    {
        global $wpdb;
        $data = $_POST;
        $files = $_FILES;
        $source = [];
        $target = [];

        foreach ($data as $key => $value) {
            if(empty($value)) {
                return array(
                    'status' => false,
                    'code' => 400,
                    'message' => 'Os campos não foram preenchidos corretamente'
                );
            }

            if(is_object($value) || is_array($value)){
                foreach ($value as $k => $v) {
                    if(empty($value)) {
                        return array(
                            'status' => false,
                            'code' => 400,
                            'message' => 'Os campos não foram preenchidos corretamente'
                        );
                    }
                } 
            }

            $posOrigem = strpos($key, "origem");
            if($posOrigem !== false){
                $name = str_replace("origem-","",$key);
                $source[$name] = $value;
            }

            $posDestino = strpos($key, "destino");
            if($posDestino !== false){
                $name = str_replace("destino-","",$key);
                $target[$name] = $value;
            }
        }

        $sourceId = saveSource($source);
        if(!$sourceId) {
            return array(
                'status' => false,
                'code' => 422,
                'message' => 'Não foi possível salvar os dados de origem'
            );
        }

        $targetId = saveTarget($target);
        if(!$targetId) {
            return array(
                'status' => false,
                'code' => 422,
                'message' => 'Não foi possível salvar os dados do destino'
            );
        }


        $data['origem_id'] = $sourceId;
        $data['destino_id'] = $targetId;
       
        $request_transport = $data['type_account'] === "pessoa_fisica" ?  modeloPessoaFisica($data,  $request) : modeloPessoaJuridica($data);

        try {
            $saved = $wpdb->insert( 
                "{$wpdb->prefix}request_transport", 
                $request_transport
            );
            $id = ($saved) ? $wpdb->insert_id : false;

            if(!$id){
                return array(
                    'status' => false,
                    'code' => 422,
                    'message' => 'Não foi possível salvar os dados'
                );
            } 

            saveImage($id);

            return array(
                'status' => true,
                'code' => 200,
                'message' => ''
            );
        } catch (\Exception $e) {
            return false;
        }
    }

    function saveSource($data){
        global $wpdb;
        $courtyards = $data; 
        $levar = !isset($data['cep']) ? 1 : 0;
        $buscar = isset($data['cep']) ? 1 : 0;

        if(!isset($data['cep'])) {
            $courtyards = getPatios($data);
        }

        try {
            $saved = $wpdb->insert( 
                "{$wpdb->prefix}request_transport_source", 
                array( 
                    'cep' => $courtyards['cep'], 
                    'estado' => $courtyards['estado'], 
                    'cidade' => $courtyards['cidade'], 
                    'bairro' => $courtyards['bairro'], 
                    'endereco' => $courtyards['endereco'], 
                    'numero' => $courtyards['numero'],
                    'levar' =>  $levar,
                    'buscar' =>  $buscar,
                )
            );
            return  ($saved) ? $wpdb->insert_id : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function saveTarget($data){
        global $wpdb;
        $courtyards = $data; 
        $retirar = !isset($data['cep']) ? 1 : 0;
        $levar = isset($data['cep']) ? 1 : 0;
        
        if(!isset($data['cep'])) {
            $courtyards = getPatios($data);
        }
       
        try {
            $saved = $wpdb->insert( 
                "{$wpdb->prefix}request_transport_target", 
                array( 
                    'cep' => $courtyards["cep"], 
                    'estado' => $courtyards["estado"], 
                    'cidade' => $courtyards["cidade"], 
                    'bairro' => $courtyards["bairro"], 
                    'endereco' => $courtyards["endereco"], 
                    'numero' => $courtyards["numero"],
                    'retirar' => $retirar, 
                    'levar' =>  $levar,
                )

            );
            return  ($saved) ? $wpdb->insert_id : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function getPatios($data){
        global $wpdb;
        
        $where = "WHERE  street like '{$data['endereco']}' and  state = '{$data['estado']}' and city = '{$data['cidade']}'"; 
        $results = $wpdb->get_results("SELECT 
            city as cidade,
            street as endereco, 
            state as estado,
            number as numero,
            neighborhood as bairro,
            cep
         FROM {$wpdb->prefix}patios {$where}", ARRAY_A);

        return $results[0];
    }

    function modeloPessoaFisica($data, $request) {
        $userData = $data['hasUser'] ? getInfo($request, true, $data['hasUser']) : getInfo($request, true);
        $aux = array();
        $model = array(
            'modelo_veiculo' => $data['modelo-veiculo'],
            'ano_veiculo' => $data['ano'],
            'fipe' => $data['valor-fipe'],
            'situacao_veiculo' => $data['situacao-veiculo'],
            'cor' => $data['cor'],
            'placa' => $data['placa'],
            'origem_id' => $data['origem_id'],
            'destino_id' => $data['destino_id'],
        );
        $modelVar = array();

        if(is_array($userData)){
            foreach ($userData as $key => $value) {
                if($value["meta_key"] === "user_contato_whatsapp") $aux['whatsapp'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_email") $aux['email'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_telefone_fixo") $aux['telefone_fixo'] = $value['meta_value'];
            }
            $modelVar = array(
                'cpf' => $data['cpf'], 
                'rg' => $data['rg'], 
                'nome' => $data['nome'], 
                'email' => $aux['email'], 
                'whatsapp' => $aux['whatsapp'], 
                'telefone_fixo' => $aux['telefone_fixo'],
            );
            return array_merge($model, $modelVar);
        }

        return array_merge($model, 
            array(
                'id_user' =>  $userData
            )
        );
    }

    function modeloPessoaJuridica($data) {
        $userData = $data['hasUser'] ? getInfo($request, true, $data['hasUser']) : getInfo($request, true);
        $aux = array();
        $model = array(
            'modelo_veiculo' => $data['modelo-veiculo'],
            'ano_veiculo' => $data['ano'],
            'fipe' => $data['valor-fipe'],
            'situacao_veiculo' => $data['situacao-veiculo'],
            'cor' => $data['cor'],
            'placa' => $data['placa'],
            'origem_id' => $data['origem_id'],
            'destino_id' => $data['destino_id'],
            'razao_social' => $data['razao_social'], 
            'nome_responsavel' => $data['nome_responsavel'], 
            'data_nasc_responsavel' => $data['data_nasc_responsavel'],
        );

        if(is_array($userData)){
            foreach ($userData as $key => $value) {
                if($value["meta_key"] === "user_dados_pessoais_ie") $aux['inscricao_estadual'] = $value['meta_value'];
            }
            $modelVar = array(
                'cnpj' => $data['cnpj'], 
                'rg' => $data['rg'],
                'email' => $aux['email'], 
                'whatsapp' => $aux['whatsapp'], 
                'telefone_fixo' => $aux['telefone_fixo'],
            );
            return array_merge($model, $modelVar);
        }

        return array_merge($model, 
            array(
                'id_user' =>  $userData
            )
        );
    }

    function saveImage($idRequest){
        $cnh_rg = $_FILES['cnh_rg'];
        $crlv = $_FILES['crlv'];
        $path = 'C:/xampp/htdocs/transpampas/wp-content/uploads/transporte/';

        
        if( !empty($crlv) ) {
            $extensionCrlv = pathinfo($crlv['name'], PATHINFO_EXTENSION);
            $imageNameCrlv = base64_encode($idRequest). "-crlv." . $extensionCrlv;
            $locationCrlv = $path . $imageNameCrlv;
            $saved = file_put_contents($locationCrlv, file_get_contents($crlv['tmp_name']));
            if($savedFile){
                uploadUrlFiles(
                    array('crlv' => $locationCrlv),
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
                uploadUrlFiles(
                    array('rg_cnh' => $locationCnh),
                    $idRequest
                );
            }
        }
    }

    function uploadUrlFiles($data, $id){
        try {
            $saved = $wpdb->update("{$wpdb->prefix}request_transport", $data, array( 'id' => $id) );
            return  $saved;
        } catch (\Throwable $th) {
            return false;
        }
    }