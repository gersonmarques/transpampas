<?php
// Template name: Listagem das solicitações do usuário
require_once TRANSPORTE_PLUGIN_PATH . '/src/request_transport/request_transport.php';

$id = get_current_user_id();

if (empty($id)) {
    header("Location: " . get_site_url());
    return;
}

get_header();

$user_data = get_user_by('id', $id);
$first_name = get_user_meta($id, 'first_name', true);

$request_transport = new RequestTransport();
$result = $request_transport->getRequestUser($id);

if(empty($_GET['id'])):
    echo ""?>

    <table class="table table-striped">
    <thead>
        <tr>
        <th scope="col">Código da Solicitação</th>
        <th scope="col">Nome</th>
        <th scope="col">E-mail</th>
        <th scope="col">CPF/CNP</th>
        <th scope="col">Status</th>
        <th scope="col">Data da solicitação</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($result as $key => $requests): 
            $colCPFOrCNPJ = empty($requests->cpf) ? $requests->cnpj : $requests->cpf
            ?>
            
            <tr>
                <th scope="row"><?php echo $requests->id;?></th>
                <td><?php echo $first_name;?></td>
                <td><?php echo $user_data->user_email;?></td>
                <td><?php echo $colCPFOrCNPJ?></td>
                <td><?php echo $request_transport->status[$requests->status];?></td>
                <td><?php echo $first_name;?></td>
            </tr>
            <?php endforeach; ?>
    </tbody>
    </table>
<?php
endif;

if(!empty($_GET['id'])) {
    update();
}

function update(){
    $id = $_GET['id'];
    $request_transport = new RequestTransport();
    $result = $request_transport->query_request_transport(true, $id);
    $result = (array) $result[0];
    if( !empty($result['id_user']) ) {
        $dataUsers = $request_transport->getUserMeta($result['id_user']);
        $result = array_merge($result, $dataUsers);
    }
    $cnh = !empty($result['rg_cnh']) ? explode("/", $result['rg_cnh']) : [];
    $crlv = !empty($result['crlv']) ? explode("/", $result['crlv']) : [];
    $cnh = end($cnh);
    $crlv = end($crlv);

    echo "";
?>
    <link rel="stylesheet" type="text/css" href="../wp-content/plugins/transporte/src/request_transport/css/request_transport.css">
    <script type="text/javascript" src="../assets/js/list-solicitacao.js"></script>
    <div id="update-request-transport" style="margin-bottom: 50px;">
        <form method="post">
            <h3>Atualizar Solicitação de Transporte </h3>
            <div class="group-add-request-transport">
                <label for="id-request-transport">Código</label>
                <input type="text" id="id-request-transport" name="id" value="<?php echo $result['id'];?>" disabled/>
            </div>
            <div class="group-add-request-transport">
                <label for="status-request-transport">Status</label>
                <select id="status" name="status">
                   <option value="0" <?php echo $result['status'] === "0" ? 'selected' : '' ;?>>Aguardando</option>
                   <option value="1" <?php echo $result['status'] === "1" ? 'selected' : '' ;?>>Em andamento</option>
                   <option value="2" <?php echo $result['status'] === "2" ? 'selected' : '' ;?>>Concluído</option>
                   <option value="3" <?php echo $result['status'] === "3" ? 'selected' : '' ;?>>Fechado</option>
                </select>
            </div>
            <div class="section-data">
                <h3 style="background-color: #e94442">Dados pessoais</h3>
                <div class="group-add-request-transport">
                    <label for="nome-request-transport">Nome</label>
                    <input type="text" id="nome-request-transport" name="nome" value="<?php echo $result['nome'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="cpf-request-transport">CPF</label>
                    <input type="text" id="cpf-request-transport" name="cpf" value="<?php echo $result['cpf'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="rg-request-transport">RG</label>
                    <input type="text" id="rg-request-transport" name="rg" value="<?php echo $result['rg'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="email-request-transport">E-mail</label>
                    <input type="text" id="email-request-transport" name="email" value="<?php echo $result['email'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="whatsapp-request-transport">Whatsapp</label>
                    <input type="text" id="whatsapp-request-transport" name="whatsapp" value="<?php echo $result['whatsapp'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="telefone-fixo-request-transport">Telefone Fixo</label>
                    <input type="text" id="telefone-fixo-request-transport" name="telefone-fixo" value="<?php echo $result['telefone_fixo'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="cnpj-request-transport">CNPJ</label>
                    <input type="text" id="cnpj-request-transport" name="cnpj" value="<?php echo $result['cnpj'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="inscricao-estadual-request-transport">Inscrição Estadual</label>
                    <input type="text" id="inscricao-estadual-request-transport" name="inscricao-estadual" value="<?php echo $result['inscricao_estadual'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="razao-social-request-transport">Razão Social</label>
                    <input type="text" id="razao-social-request-transport" name="razao-social" value="<?php echo $result['razao_social'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="nome-responsavel-request-transport">Nome Responsável</label>
                    <input type="text" id="nome-responsavel-request-transport" name="nome-responsavel" value="<?php echo $result['nome_responsavel'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="data-nasc-responsavel-request-transport">Data de Nascimento Responsável</label>
                    <input type="text" id="data-nasc-responsavel-request-transport" name="data-nasc-responsavel" value="<?php echo $result['data_nasc_responsavel'];?>" disabled/>
                </div>
            </div>
            <div class="section-data">
                <h3 style="background-color: #e94442">Dados do veículo</h3>
                <div class="group-add-request-transport">
                    <label for="modelo-veiculo-request-transport">Modelo veículo</label>
                    <input type="text" id="modelo-veiculo-request-transport" name="modelo-veiculo" value="<?php echo $result['modelo_veiculo'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="ano-veiculo-request-transport">Ano veículo</label>
                    <input type="text" id="ano-veiculo-request-transport" name="ano-veiculo" value="<?php echo $result['ano_veiculo'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="fipe-request-transport">Fipe</label>
                    <input type="text" id="fipe-request-transport" name="fipe" value="<?php echo $result['fipe'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="situacao-veiculo-request-transport">Situação veículo</label>
                    <input type="text" id="situacao-veiculo-request-transport" name="situacao-veiculo" value="<?php echo $result['situacao_veiculo'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="cor-request-transport">Cor</label>
                    <input type="text" id="cor-request-transport" name="cor" value="<?php echo $result['cor'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="placa-request-transport">Placa</label>
                    <input type="text" id="placa-request-transport" name="placa" value="<?php echo $result['placa'];?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="rg_cnh-request-transport">RG/CNH</label>
                    <input class="rg_cnh" accept=".pdf, .jpg, .png" type="file" id="rg_cnh" name="rg_cnh" style="display:none">
                    <label class="rg_cnh_label label_input" disabled  style="opacity: 0.8"><?php echo !empty($result['rg_cnh']) ?  $cnh :'Cópia/Foto legível de RG OU CNH (.pdf .jpg .png)'?></label>
                    <a href="<?php echo home_url() . '/' . $result['rg_cnh']; ?>" target="_blank"><span class="dashicons dashicons-visibility"></span></a>
                </div>
                <div class="group-add-request-transport">
                    <label for="crlv-request-transport">CRLV</label>
                    <input class="crlv" accept=".pdf, .jpg, .png" type="file" id="crlv" name="crlv" style="display:none">
                    <label class="crlv_label label_input" disabled style="opacity: 0.8"><?php echo !empty($result['crlv']) ?  $crlv :'Cópia/Foto legível de CRLV (.pdf .jpg .png)'?></label>
                    <a href="<?php echo home_url() . '/' . $result['crlv']; ?>" target="_blank"><span class="dashicons dashicons-visibility"></span></a>
                </div>
            </div>
            <div class="section-data">
                <h3 style="background-color: #e94442">Origem</h3>
                <?php 
                    $source = $request_transport->getSource($result['origem_id']);
                ?>
                <div class="group-add-request-transport">
                    <label for="origem-cep">CEP</label>
                    <input type="tel" id="origem-cep" name="cep" value="<?php echo $source[0]->cep;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-endereco">Rua</label>
                    <input type="text" id="origem-endereco" name="endereco" value="<?php echo $source[0]->endereco;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-numero">Número</label>
                    <input type="tel" id="origem-numero" name="numero" value="<?php echo $source[0]->numero;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-bairro">Bairro</label>
                    <input type="text" id="origem-bairro" name="bairro" value="<?php echo $source[0]->bairro;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-cidade">Cidade</label>
                    <input type="text" id="origem-cidade" name="cidade" value="<?php echo $source[0]->cidade;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-estado">Estado</label>
                    <select id="origem-estado" name="estado" disabled>
                        <?= getUfs($source[0]->estado);?>
                    </select>
                </div>
                <div class="group-add-request-transport">
                    <label for="origem-levar-buscar">Origem</label>
                    <select id="origem-levar-buscar" name="origem-levar-buscar" disabled>
                        <option value="levar" <?php echo $source[0]->levar ? 'selected' : '';?>>Vou levar</option>
                        <option value="buscar" <?php echo $source[0]->buscar ? 'selected' : '';?> >Quero que busquem</option>
                    </select>
                </div>
            </div>
            <div class="section-data">
                <h3 style="background-color: #e94442">Destino</h3>
                <?php 
                    $target = $request_transport->getTarget($result['destino_id']);
                ?>
                <div class="group-add-request-transport">
                    <label for="destino-cep">CEP</label>
                    <input type="tel" id="destino-cep" name="cep" value="<?php echo $target[0]->cep;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-endereco">Rua</label>
                    <input type="text" id="destino-endereco" name="endereco" value="<?php echo $target[0]->endereco;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-numero">Número</label>
                    <input type="tel" id="destino-numero" name="numero" value="<?php echo $target[0]->numero;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-bairro">Bairro</label>
                    <input type="text" id="destino-bairro" name="bairro" value="<?php echo $target[0]->bairro;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-cidade">Cidade</label>
                    <input type="text" id="destino-cidade" name="cidade" value="<?php echo $target[0]->cidade;?>" disabled/>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-estado">Estado</label>
                    <select id="destino-estado" name="estado" disabled>
                        <?= getUfs($target[0]->estado);?>
                    </select>
                </div>
                <div class="group-add-request-transport">
                    <label for="destino-levar-buscar">Destino</label>
                    <select id="destino-levar-buscar" name="destino-levar-buscar" disabled>
                        <option value="levar" <?php echo $target[0]->retirar ? 'selected' : '';?>>Vou retirar</option>
                        <option value="buscar" <?php echo $target[0]->levar ? 'selected' : '';?> >Quero que levem</option>
                    </select>
                </div>
            </div>
            <div class="section-data">
                <h3 style="background-color: #e94442">Observação</h3>
                <div class="group-add-request-transport" style="grid-column: 1 / 3">
                    <label for="observacao">Obeservacão</label>
                    <textarea id="observacao" name="observacao" rows='6'><?php echo $result['observacao'];?></textarea>
                </div>
            </div>
            <input type="hidden" id="url_site" value="<?= get_site_url();?>" />
            <input type="button" id="input_update" class="button button-primary" value="Atualizar"/>
            <div class="loader"></div>
        </form>
        <div class="loading">
            <div class="square">
                <div class="spinner-border m-5 spinner-load" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="background-load"></div>
        <div class="modal fade bd-example-modal-md" tabindex="-1" role="dialog" aria-labelledby="mediumModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-md">
                <div class="modal-content">
                    <div class="modal-header" style="padding: 0;margin-bottom: 20px;">
                        <h4 class="modal-title" id="mediumModalLabel"></h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <p id="message"></p>
                   
                </div>
            </div>
        </div>
    </div>        
<?php
}

function getUfs($state = '') 
{?>
    <option value="" >Selecione o estado</option>
    <option value="AC" <?= ($state == "AC") ? "selected" : ''?>>Acre</option>
    <option value="AL" <?= ($state == "AL") ? "selected" : ''?>>Alagoas</option>
    <option value="AP" <?= ($state == "AP") ? "selected" : ''?>>Amapá</option>
    <option value="AM" <?= ($state == "AM") ? "selected" : ''?>>Amazonas</option>
    <option value="BA" <?= ($state == "BA") ? "selected" : ''?>>Bahia</option>
    <option value="CE" <?= ($state == "CE") ? "selected" : ''?>>Ceará</option>
    <option value="DF" <?= ($state == "DF") ? "selected" : ''?>>Distrito Federal</option>
    <option value="ES" <?= ($state == "ES") ? "selected" : ''?>>Espirito Santo</option>
    <option value="GO" <?= ($state == "GO") ? "selected" : ''?>>Goiás</option>
    <option value="MA" <?= ($state == "MA") ? "selected" : ''?>>Maranhão</option>
    <option value="MS" <?= ($state == "MS") ? "selected" : ''?>>Mato Grosso do Sul</option>
    <option value="MT" <?= ($state == "MT") ? "selected" : ''?>>Mato Grosso</option>
    <option value="MG" <?= ($state == "MG") ? "selected" : ''?>>Minas Gerais</option>
    <option value="PA" <?= ($state == "PA") ? "selected" : ''?>>Pará</option>
    <option value="PB" <?= ($state == "PB") ? "selected" : ''?>>Paraíba</option>
    <option value="PR" <?= ($state == "PR") ? "selected" : ''?>>Paraná</option>
    <option value="PE" <?= ($state == "PE") ? "selected" : ''?>>Pernambuco</option>
    <option value="PI" <?= ($state == "PI") ? "selected" : ''?>>Piauí</option>
    <option value="RJ" <?= ($state == "RJ") ? "selected" : ''?>>Rio de Janeiro</option>
    <option value="RN" <?= ($state == "RN") ? "selected" : ''?>>Rio Grande do Norte</option>
    <option value="RS" <?= ($state == "RS") ? "selected" : ''?>>Rio Grande do Sul</option>
    <option value="RO" <?= ($state == "RO") ? "selected" : ''?>>Rondônia</option>
    <option value="RR" <?= ($state == "RR") ? "selected" : ''?>>Roraima</option>
    <option value="SC" <?= ($state == "SC") ? "selected" : ''?>>Santa Catarina</option>
    <option value="SP" <?= ($state == "SP") ? "selected" : ''?>>São Paulo</option>
    <option value="SE" <?= ($state == "SE") ? "selected" : ''?>>Sergipe</option>
    <option value="TO" <?= ($state == "TO") ? "selected" : ''?>>Tocantins</option>
<?php }