<?php
    function getInfo(WP_REST_Request $request) 
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
            
            
            $query = "SELECT  wu.display_name, wum.meta_key, wum.meta_value FROM {$wpdb->prefix}usermeta wum
                INNER JOIN  {$wpdb->prefix}users wu on  wu.ID = '{$id}'
                WHERE user_id = '{$id}' AND wum.meta_key like 'user_%'";
            $data = $wpdb->get_results($query, ARRAY_A);

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
        $data = $_POST;
        $files = $_FILES;
        $source = [];
        $target = [];


        foreach ($data as $key => $value) {
            if(empty($value)) return false;

            if(is_object($value) || is_array($value)){
                foreach ($value as $k => $v) {
                    if(empty($value)) return false;
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

        var_dump('<pre>', $data, $source, '</pre>');die;
    }

    function saveSource($data){
        global $wpdb;
        try {
            $wpdb->insert( 
                'wpuv_request_transport_source', 
                array( 
                    'cep' => 'value1', 
                    'estado' => 123, 
                    'cidade' => 123, 
                    'bairro' => 123, 
                    'endereco' => 123, 
                    'numero' => 123, 
                )
            );
            return $wpdb->insert_id;
        } catch (\Throwable $th) {
            return false;
        }
    }
    function saveTarget($data){
        global $wpdb;
        try {
            $wpdb->insert( 
                'wpuv_request_transport_target', 
                array( 
                    'cep' => 'value1', 
                    'estado' => 123, 
                    'cidade' => 123, 
                    'bairro' => 123, 
                    'endereco' => 123, 
                    'numero' => 123, 
                )
            );
            return $wpdb->insert_id;
        } catch (\Throwable $th) {
            return false;
        }
    }

