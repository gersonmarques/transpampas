<?php 

class Request_transport_html{
    

    /**
     * Page of list transport
     */
     public function html_list_requests_transport(){
        require_once TRANSPORTE_PLUGIN_PATH . '/src/request_transport/request_transport.php';
        $title   = "Solicitações";
        ?>
        <link rel="stylesheet" type="text/css" href="../wp-content/plugins/transporte/src/request_transport/css/request_transport.css">
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/request_transport/js/request_transport.js"></script>
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/request_transport/js/request_transport_actions.js"></script>
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
                            <div class="loader-search"></div>
                            <input type='text' id='query-request-transport'/>
                            <select id='param-request-transport'>
                                <option value="id">ID</option>
                                <option value="nome">Nome</option>
                                <option value="cpf">CPF</option>
                                <option value="criado">Criado em</option>
                                <option value="modificado">Modificado em</option>
                            </select>
                            <input type='button' id='search-request-transport' class="button button-primary" value="Pesquisar"/>
                        </div>
                    </div>
                    <table class="list-request-transport wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <th style="width: 2%;"></th>
                            <th style="width: 5%; text-align: center;">ID</th>
                            <th style="width:30%;">Nome</th>
                            <th>E-mail</th>
                            <th>CPF/CNPJ</th>
                            <th>Criado em </th>
                            <th>Modificado em </th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $request_transport = new RequestTransport();
                            $result =  $request_transport->query_request_transport(true);
                            foreach($result as $key => $val):
                            ?>
                            </tr>
                                <td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="<?php echo $val->id?>"></td>
                                <td style="text-align: center;"><?php echo $val->id?></td>
                                <td><?php echo $val->nome?></td>
                                <td><?php echo $val->email?></td>
                                <td><?php echo empty($val->cpf) ? $val->cnpj : $val->cpf ?></td>
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
        $request_transport = new RequestTransport();
        $result = $request_transport->query_request_transport(true, $id);
        $result = (array) $result[0];
        if( !empty($result['id_user']) ) {
            $dataUsers = $request_transport->getUserMeta($result['id_user']);
            $result = array_merge($result, $dataUsers);
           
        }
    ?>
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/request_transport/js/request_transport.js"></script>
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/request_transport/js/request_transport_actions.js"></script>
        <div id="update-request-transport">
            <form method="post">
                <h3>Atualizar Solicitação de Transporte </h3>
                <div class="group-add-request-transport">
                    <label for="id-request-transport">Código</label>
                    <input type="text" id="id-request-transport" name="id" value="<?php echo $result['id'];?>" disabled/>
                </div>

                <div class="section-data">
                    <h3>Dados pessoais</h3>
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
                    <div class="group-add-request-transport">
                        <label for="rg_cnh-request-transport">RG/CNH</label>
                        <input type="text" id="rg_cnh-request-transport" name="rg_cnh" value="<?php echo $result['rg_cnh'];?>"/>
                    </div>
                    <div class="group-add-request-transport">
                        <label for="crlv-request-transport">CRLV</label>
                        <input type="text" id="crlv-request-transport" name="crlv" value="<?php echo $result['crlv'];?>"/>
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



/****
 * 
 * 
 * 
 *  <div class="group-add-courtyards">
                    <label for="cep-courtyards">CEP</label>
                    <input type="tel" id="cep-courtyards" name="cep" value="<?php echo $result[0]->cep;?>"/>
                </div>
                <div class="group-add-courtyards">
                    <label for="street-courtyards">Rua</label>
                    <input type="text" id="street-courtyards" name="street" value="<?php echo $result[0]->street;?>"/>
                </div>
                <div class="group-add-courtyards">
                    <label for="number-courtyards">Número</label>
                    <input type="tel" id="number-courtyards" name="number" value="<?php echo $result[0]->number;?>"/>
                </div>
                <div class="group-add-courtyards">
                    <label for="neighborhood-courtyards">Bairro</label>
                    <input type="text" id="neighborhood-courtyards" name="neighborhood" value="<?php echo $result[0]->neighborhood;?>"/>
                </div>
                <div class="group-add-courtyards">
                    <label for="city-courtyards">Cidade</label>
                    <input type="text" id="city-courtyards" name="city" value="<?php echo $result[0]->city;?>"/>
                </div>
                <div class="group-add-courtyards">
                    <label for="state-courtyards">Estado</label>
                    <select id="state-courtyards" name="state">
                        <?= getUfs($result[0]->state);?>
                    </select>
                </div>
                <div class="group-add-courtyards">
                    <label for="reference-courtyards">Referência</label>
                    <textarea id="reference-courtyards" name="reference" rows='6'><?php echo $result[0]->reference;?></textarea>
                </div>
 */