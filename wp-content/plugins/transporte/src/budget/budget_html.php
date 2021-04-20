<?php 

class Budget_html{
    protected $status = [
        'Aguardando',
        'Em andamento',
        'Concluído',
        'Fechado'
    ];

    /**
     * Page of list transport
     */
     public function html_list_requests_transport(){
        require_once TRANSPORTE_PLUGIN_PATH . '/src/budget/budget.php';
        $title   = "Solicitações de Orçamento";
        ?>
        <link rel="stylesheet" type="text/css" href="../wp-content/plugins/transporte/src/budget/css/budget.css">
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/budget/js/budget.js"></script>
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/budget/js/budget_actions.js"></script>
        <div class="wrap">
            <h2><?php echo $title; ?></h2>
            <div class="notice notice-success is-dismissible">
                <p><strong>Settings saved.</strong></p>
            </div>
            <?php 
            if(isset($_GET['id']) && !empty($_GET['id'])){
                    echo $this->html_update_area();
            }else{?>
                <div class="table-request-transport">
                    <div class="actions-table">
                        <input type="button" id="input_change" class="button button-primary" value="Alterar" disabled/>
                        <input type="button" id="input_delete" class="button button-secondary" value="Delete" disabled/>
                        <div class='query-table'>
                            <input type='text' id='query-request-transport'/>
                            <select id='query-request-transport-status' style="vertical-align: baseline; display:  none">
                                <option value="0">Aguardando</option>
                                <option value="1">Em Andamento</option>
                                <option value="2">Concluído</option>
                                <option value="3">Fechado</option>
                            </select>
                            <select id='param-request-transport'>
                                <option value="id">ID</option>
                                <option value="nome">Nome</option>
                                <option value="status">Status</option>
                                <option value="criado">Criado em</option>
                                <option value="modificado">Modificado em</option>
                            </select>
                            <input type='button' id='search-request-transport' class="button button-primary" value="Pesquisar"/>
                            <div class="loader-search"></div>
                        </div>
                    </div>
                    <table class="list-request-transport wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <th style="width: 2%;"></th>
                            <th style="width: 5%; text-align: center;">ID</th>
                            <th style="width:30%;">Nome</th>
                            <th>E-mail</th>
                            <th>Status</th>
                            <th>Criado em </th>
                            <th>Modificado em </th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request_transport = new Budget();
                            $result = $request_transport->query_request_transport(true);
                            foreach($result as $key => $val):
                            ?>
                            </tr>
                                <td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="<?php echo $val->id?>"></td>
                                <td style="text-align: center;"><?php echo $val->id?></td>
                                <td><?php echo $val->nome ? $val->nome : ""?></td>
                                <td><?php echo $val->email ? $val->email : ""?></td>
                                <td><?php echo $this->status[$val->status]?></td>
                                <td><?php echo date("d/m/Y", strtotime($val->criado))?></td>
                                <td><?php echo date("d/m/Y", strtotime($val->modificado))?></td>                                
                            </tr>
                         <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            <?php }?>
        </div>
        <?php
    }
    
    function html_update_area(){
        $id = $_GET['id'];
        require_once TRANSPORTE_PLUGIN_PATH . '/src/request_transport/request_transport.php';
        $request_transport = new Budget();
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

    ?>
        <div id="update-request-transport">
            <form method="post">
                <h3>Atualizar Solicitação de Orçamento</h3>
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
                    <h3>Dados pessoais</h3>
                    <div class="group-add-request-transport">
                        <label for="nome-request-transport">Nome</label>
                        <input type="text" id="nome-request-transport" name="nome" value="<?php echo $result['nome'];?>" disabled/>
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
                </div>

                <div class="section-data">
                    <h3>Dados do veículo</h3>
                    <div class="group-add-request-transport">
                        <label for="modelo-veiculo-request-transport">Modelo veículo</label>
                        <input type="text" id="modelo-veiculo-request-transport" name="modelo-veiculo" value="<?php echo $result['modelo_veiculo'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="ano-veiculo-request-transport">Ano veículo</label>
                        <input type="text" id="ano-veiculo-request-transport" name="ano-veiculo" value="<?php echo $result['ano_veiculo'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="fipe-request-transport">Fipe</label>
                        <input type="text" id="fipe-request-transport" name="fipe" value="<?php echo $result['fipe'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="situacao-veiculo-request-transport">Situação veículo</label>
                        <input type="text" id="situacao-veiculo-request-transport" name="situacao-veiculo" value="<?php echo $result['situacao_veiculo'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="cor-request-transport">Cor</label>
                        <input type="text" id="cor-request-transport" name="cor" value="<?php echo $result['cor'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="placa-request-transport">Placa</label>
                        <input type="text" id="placa-request-transport" name="placa" value="<?php echo $result['placa'];?>"/>
                    </div>
                </div>

                <div class="section-data">
                    <h3>Origem</h3>
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
                    <h3>Destino</h3>
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
                    <h3>Observação</h3>
                    <div class="group-add-request-transport" style="grid-column: 1 / 3">
                        <label for="observacao">Obeservacão</label>
                        <textarea id="observacao" name="observacao" rows='6'><?php echo $result['observacao'];?></textarea>
                    </div>
                </div>
                <input type="button" id="input_update" class="button button-primary" value="Atualizar"/>
                <div class="loader"></div>
            </form>
        </div>        
    <?php
    }

    
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