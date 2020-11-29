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

