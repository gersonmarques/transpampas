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
            $where = "WHERE user_login = '{$cnpj}'"; 
        }else{
            $where = "WHERE user_login = '{$cpf}'";
        }
        
        global $wpdb;
        try {
            $results = $wpdb->get_results( "SELECT ID FROM {$wpdb->prefix}users {$where}", ARRAY_A);
            $id = $results[0]['ID'];
            
            if($onlyId) return $id;
            
            $query = "SELECT  wu.user_email as email, wu.display_name, wum.meta_key, wum.meta_value FROM {$wpdb->prefix}usermeta wum
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
                $mail = $value['email'];
                if(empty($value['email'])) continue;
                
                $data[$key]['email'] = substr_replace($mail, '*****', 1, strpos($mail, '@') - 2);
                $data[$key]['id'] = $id;
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
        $address = [];

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

            $posAddress = strpos($key, "endereco-user");
            if($posAddress !== false){
                $name = str_replace("endereco-user-","",$key);
                $address[$name] = $value;
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
        
        if($data['orcamento']){
            $request_transport = modeloOrcamento($data,  $request);
        }else{
            $request_transport = $data['type_account'] === "pessoa_fisica" ?  modeloPessoaFisica($data,  $request) : modeloPessoaJuridica($data);
        }

        $hasIdUser = !empty($request_transport['id_user']) ? $request_transport['id_user'] : false;
        
        if(!$data['orcamento']) {
            $addressId = saveAddress($address, $hasIdUser);
            if(!$addressId) {
                return array(
                    'status' => false,
                    'code' => 422,
                    'message' => 'Não foi possível salvar os dados de endereço'
                );
            }
            $request_transport['endereco_id'] = $addressId;
        }

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

            if(!$data['orcamento'] && !empty($_FILES)){
                saveImage($id);
            }
            
            $layoutEmail = layoutEmail($request_transport, $request);
            sendMail($layoutEmail);

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

    function saveAddress($data, $idUser = false){
        global $wpdb;
        $address = $data; 
       
        if($idUser) {
            $userMeta = get_user_meta($idUser);
            $address['cep'] = $userMeta['user_endereco_cep'][0];
            $address['estado'] = $userMeta['user_endereco_estado'][0];
            $address['cidade'] = $userMeta['user_endereco_cidade'][0];
            $address['bairro'] = $userMeta['user_endereco_bairro'][0] ? $userMeta['user_endereco_bairro'][0] : 'asdas';
            $address['endereco'] = $userMeta['user_endereco_rua'][0];
            $address['numero'] = $userMeta['user_endereco_numero'][0];
        }
        try {
            $saved = $wpdb->insert(
                "{$wpdb->prefix}request_transport_address",
                array( 
                    'cep' => $address['cep'],
                    'estado' => $address['estado'],
                    'cidade' => $address['cidade'],
                    'bairro' => $address['bairro'],
                    'endereco' => $address['endereco'],
                    'numero' => $address['numero'],
                )
            );
            return  ($saved) ? $wpdb->insert_id : false;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function getSource($id) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}request_transport_source WHERE id = {$id}", ARRAY_A);
        return (count($results) > 0) ? $results : array();
    }

    function getTarget($id) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}request_transport_target WHERE id = {$id}", ARRAY_A);
        return (count($results) > 0) ? $results : array();
    }
    function getAddress($id) {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}request_transport_address WHERE id = {$id}", ARRAY_A);
        return (count($results) > 0) ? $results : array();
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
            'cpf' => $data['cpf'], 
            'rg' => $data['rg'], 
            'nome' => $data['nome'], 
            'email' => $data['e-mail'], 
            'whatsapp' => $data['whatsapp'], 
            'telefone_fixo' => $data['telefone_fixo'],
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
                'email' => $aux['email'] ? $aux['email'] : $data['e-mail'], 
                'whatsapp' => $aux['whatsapp'] ? $aux['whatsapp'] : $data['whatsapp'], 
                'telefone_fixo' => $aux['telefone_fixo'] ? $aux['telefone_fixo'] : $data['telefone_fixo'],
            );
            return array_merge($model, $modelVar);
        }

        if(empty($userData)) {
            return $model;
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
            'cnpj' => $data['cnpj'],
            'rg' => $data['rg'],
            'email' => $data['e-mail'], 
            'whatsapp' => $data['whatsapp'], 
            'telefone_fixo' => $data['telefone_fixo'],
        );
        
        if(is_array($userData)){
            foreach ($userData as $key => $value) {
                if($value["meta_key"] === "user_dados_pessoais_ie") $aux['inscricao_estadual'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_whatsapp") $aux['whatsapp'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_email") $aux['email'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_telefone_fixo") $aux['telefone_fixo'] = $value['meta_value'];
            }
            $modelVar = array(
                'cnpj' => $data['cnpj'],
                'rg' => $data['rg'],
                'email' => $aux['email'] ? $aux['email'] : $data['e-mail'],
                'whatsapp' => $aux['whatsapp'] ? $aux['whatsapp'] : $data['whatsapp'],
                'telefone_fixo' => $aux['telefone_fixo'] ? $aux['telefone_fixo'] : $data['telefone_fixo'],
            );
            return array_merge($model, $modelVar);
        }

        if(empty($userData)) {
            return $model;
        }

        return array_merge($model, 
            array(
                'id_user' =>  $userData
            )
        );
    }

    function modeloOrcamento($data, $request) {
        return array(
            'nome' => $data['nome'], 
            'email' => $data['e-mail'],
            'whatsapp' => $data['whatsapp'], 
            'telefone_fixo' => $data['telefone_fixo'],
            'modelo_veiculo' => $data['modelo-veiculo'],
            'ano_veiculo' => $data['ano'],
            'fipe' => $data['valor-fipe'],
            'situacao_veiculo' => $data['situacao-veiculo'],
            'cor' => $data['cor'],
            'placa' => $data['placa'],
            'origem_id' => $data['origem_id'],
            'destino_id' => $data['destino_id'],
            'orcamento' => 1,
        );
    }

    function saveImage($idRequest){
        $cnh_rg = $_FILES['cnh_rg'];
        $crlv = $_FILES['crlv'];
        $dir = wp_upload_dir();
        $path = str_replace('\\','/', $dir["basedir"].'/transporte/');

        
        if( !empty($crlv) ) {
            $extensionCrlv = pathinfo($crlv['name'], PATHINFO_EXTENSION);
            $imageNameCrlv = base64_encode($idRequest). "-crlv." . $extensionCrlv;
            $locationCrlv = $path . $imageNameCrlv;
            $saved = file_put_contents($locationCrlv, file_get_contents($crlv['tmp_name']));
            if($saved){
                $location = substr(
                    $locationCrlv,
                    strpos($locationCrlv, 'wp-content')
                );

                uploadUrlFiles(
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
                
                uploadUrlFiles(
                    array('rg_cnh' => $location),
                    $idRequest
                );
            }
        }
    }

    function uploadUrlFiles($data, $id){
        global $wpdb;
        try {
            $saved = $wpdb->update("{$wpdb->prefix}request_transport", $data, array( 'id' => $id) );
            return  $saved;
        } catch (\Throwable $th) {
            return false;
        }
    }

    function layoutEmail($data, $request) { 
        $source = getSource( $data['origem_id']);
        $source = $source[0];
        $target = getTarget( $data['destino_id']);
        $target = $target[0];
        $address = getAddress( $data['endereco_id']);
        $address = $address[0];
        $levarSource =  $source['levar'] ? 'Vou levar' : 'Quero que busquem';
        $retirarTarget =  $target['retirar'] ? 'Vou retirar' : 'quero que levem';
        $isOrcamento = empty($_POST['orcamento']) ? false : true;
        if(!$isOrcamento){
            $userData = getInfo($request, true);
        }
        
        if(!$isOrcamento && $_POST['hasUser']) {
            $aux = array();
            foreach ($userData as $key => $value) {
                if($value["meta_key"] === "user_dados_pessoais_ie") $aux['inscricao_estadual'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_whatsapp") $aux['whatsapp'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_email") $aux['email'] = $value['meta_value'];
                if($value["meta_key"] === "user_contato_telefone_fixo") $aux['telefone_fixo'] = $value['meta_value'];
                if($value["meta_key"] === "user_dados_pessoais_rg") $aux['rg'] = $value['meta_value'];
                $aux['nome'] = $value['display_name'];
            }
            $modelVar = array(
                'cpf' => $data['cpf'] ? $data['cpf'] : $_POST['cpf'],
                'cnpj' => $data['cnpj'] ? $data['cnpj'] : $_POST['cnpj'],
                'rg' => $aux['rg'] ? $aux['rg'] : $data['rg'],
                'nome' => $aux['nome'] ? $aux['nome'] : $data['nome'],
                'email' => $aux['email'] ? $aux['email'] : $data['email'], 
                'whatsapp' => $aux['whatsapp'] ? $aux['whatsapp'] : $data['whatsapp'], 
                'telefone_fixo' => $aux['telefone_fixo'] ? $aux['telefone_fixo'] : $data['telefone_fixo'],
            ); 
            $data = array_merge($data, $modelVar);
        }

        $html = "<div id='email-data'class='content-email-data'>";
        $html .= $isOrcamento ? "<h2>Dados da solicitação de orçamento</h2>" : "<h2>Dados da solicitação de transporte</h2>";
        $html .= "<div class='content-email'>
                <div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Dados Pessoais</H3>
                    <p><b>Nome:</b> {$data['nome']} </p>";
                $html .= !$isOrcamento ? "<p><b>CPF:</b> {$data['cpf']} </p>
                    <p><b>RG:</b> {$data['rg']} </p>" : ""; 
                $html .= "<p><b>E-Mail:</b> {$data['email']} </p>
                    <p><b>Whatsapp:</b> {$data['whatsapp']} </p>
                    <p><b>Telefone Fixo:</b> {$data['telefone_fixo']} </p>";
                $html .= !$isOrcamento ? "<p><b>CNPJ:</b> {$data['cnpj']} </p>
                    <p><b>Inscrição Estadual:</b> {$data['inscricao_estadual']} </p>
                    <p><b>Razão Social:</b> {$data['razao_social']} </p>
                    <p><b>Nome Responsável:</b> {$data['nome_responsavel']} </p>
                    <p><b>Data de Nascimento Responsável:</b> {$data['data_nasc_resposavel']} </p>" : "";
                $html .= "</div>";
                $html .= !$isOrcamento ? "<div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Endereço</H3>
                    <p><b>CEP:</b> {$address['cep']} </p>
                    <p><b>Rua:</b> {$address['endereco']} </p>
                    <p><b>Número:</b> {$address['numero']} </p>
                    <p><b>Bairro:</b> {$address['bairro']} </p>
                    <p><b>Cidade:</b> {$address['cidade']} </p>
                    <p><b>Estado:</b> {$address['estado']} </p>
                </div>" : "";
                $html .= "<div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Dados do veículo</H3>
                    <p><b>Modelo veículo:</b> {$data['modelo_veiculo']} </p>
                    <p><b>Ano veículo:</b> {$data['ano_veiculo']} </p>
                    <p><b>Fipe:</b> {$data['fipe']} </p>
                    <p><b>Situação veículo:</b> {$data['situacao_veiculo']} </p>
                    <p><b>Cor:</b> {$data['cor']} </p>
                    <p><b>Placa:</b> {$data['placa']} </p>
                </div>
                <div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Origem</H3>
                    <p><b>CEP:</b> {$source['cep']} </p>
                    <p><b>Rua:</b> {$source['endereco']} </p>
                    <p><b>Número:</b> {$source['numero']} </p>
                    <p><b>Bairro:</b> {$source['bairro']} </p>
                    <p><b>Cidade:</b> {$source['cidade']} </p>
                    <p><b>Estado:</b> {$source['estado']} </p>
                    <p><b>Vou levar ou quer que busquem:</b> {$levarSource} </p>
                </div>
                <div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Destino</H3>
                    <p><b>CEP:</b> {$target['cep']} </p>
                    <p><b>Rua:</b> {$target['endereco']} </p>
                    <p><b>Número:</b> {$target['numero']} </p>
                    <p><b>Bairro:</b> {$target['bairro']} </p>
                    <p><b>Cidade:</b> {$target['cidade']} </p>
                    <p><b>Estado:</b> {$target['estado']} </p>
                    <p><b>Vou retirar ou quer que levem:</b> {$retirarTarget} </p>
                </div>";
                $html .= !$isOrcamento ? "<div>
                    <H3 style='background: #007cba; padding: 15px; color: #FFF;border-radius: 2px;text-align: center;'>Observação</H3>
                    <p>{$data['observacao']}</p>
                </div>" : "";
            $html .= "</div>
        </div>";
    return $html;    
}

    function sendMail($body) {
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
        $isOrcamento = empty($_POST['orcamento']) ? false : true;
        $emails_to = get_post_meta($_POST['id'], 'email_solicitacoes', true);
        $to = explode(";", $emails_to);
        $subject = !$isOrcamento ? "Solicitação Transporte de: {$_POST['nome']}" : "Solicitação Orçamento de: {$_POST['nome']}";
        $cnh_rg = $_FILES['cnh_rg'];
        $crlv = $_FILES['crlv'];
     
        $mailer = new PHPMailer\PHPMailer\PHPMailer( true );
        $mailer->IsSMTP();
        $mailer->Host = get_option( 'mailserver_url' ); // your SMTP server
        $mailer->Port = (int) get_option('mailserver_port', 465);
        $mailer->SMTPDebug = 0; // write 0 if you don't want to see client/server communication in page
        $mailer->CharSet  = "utf-8";
        $mailer->SMTPAuth = true;
        $mailer->SMTPSecure = 'ssl';
        $mailer->Username   = htmlspecialchars_decode(get_option('mailserver_login'));                //SMTP username
        $mailer->Password   = htmlspecialchars_decode(get_option('mailserver_pass'));
        
        $mailer->setFrom(  htmlspecialchars_decode(get_option('mailserver_login')), 'Contato Transpampas');
        $mailer->isHTML(true); 
        $mailer->Subject = $subject;
        $mailer->Body    = $body;       
        foreach ($to as $key => $email) {
            $mailer->addAddress( $email );
        }
       

        if(!empty($cnh_rg)) {
            $mailer->addAttachment($cnh_rg['tmp_name'], $cnh_rg['name']);
        }

        if(!empty($crlv)) {
            $mailer->addAttachment($crlv['tmp_name'], $crlv['name']);
        }

      return $mailer->send();
    }

    function saveNote(WP_REST_Request $request) {
        global $wpdb;
        try {
            $data = ["observacao" => $request['observacao']];
            $id = $request['id'];
            $saved = $wpdb->update("{$wpdb->prefix}request_transport", $data, array( 'id' => $id) );
            return array(
                'status' => true,
                'code' => 200,
                'message' => ''
            );
        } catch (\Throwable $th) {
            return array(
                'status' => false,
                'code' => 200,
                'message' => ''
            );
        }
    }

    function getUserMeta(WP_REST_Request $request) {
        $id = $request['id'];
        return get_user_meta($id);
    }