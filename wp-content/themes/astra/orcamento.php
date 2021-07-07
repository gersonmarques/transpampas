<?php 
// Template Name: Orçamento
get_header();
?>
    <link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/css/solicitar-orcamento.css" ?>" />
    <script src="<?= get_site_url()."/assets/js/solicitar-orcamento.js"?>"></script>
    <script src="<?= get_site_url()."/assets/js/utils.js"?>"></script>

    <div class="site-main margin-content" id="form-content-top">
        <h1><?= $post->post_title?></h1>
        <div class="container" id="form-solicitar-transporte"  style="display:nones">
            <div class='container-fields col-md-8'>
                <div class="menu">
                    <p data-step="1" class="active menu-label">Dados pessoais e do veículo</p>
                    <p data-step="2" class="menu-label">Origem</p>
                    <p data-step="3" class="menu-label">Destino</p>
                </div>
                <div class="step1 step step-active">
                    <div id='pessoa_fisica_wrapper'>
                        <?= pessoa_fisica();?>
                    </div>
                </div>
                <div class="step2 step">
                    <?= origem();?>
                </div>
                <div class="step3 step">
                    <?= destino();?>
                </div>
            </div>
            <div style="text-align: center">
                <button class='btn btn-prev'>Voltar</button>
                <button class='btn btn-next'>Próximo</button>
            </div>
        </div>
        <?php 
            htmlSuccess($post->ID);
        ?>
        <div class="loading">
            <div class="square">
                <div class="spinner-border m-5 spinner-load" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <div class="background-load"></div>
    </div>
    <input type="hidden" id="url_site" value="<?= get_site_url();?>" />
    <input type="hidden" id="id" value="<?= $post->ID;?>" />
<?php 
    get_footer();


function pessoa_fisica()
{ ?>
    <div class='dados-pessoais section-data'>
        <p class='title-section'>Dados Pessoais</p>
        <input type='text' class='input-field nome' name='nome' placeholder='Nome Completo' required/>
        <input type='email' class='input-field email' name='e-mail' placeholder='E-mail' required/>
        <div class='group-fields'>
            <input type='tel' class='input-field whatsapp' name='whatsapp' placeholder='Whatsapp' required/>
            <input type='tel' class='input-field telefone-fixo ' name='telefone-fixo' placeholder='Tel. Celular' />
        </div>
    </div>
<?php
    dadosVeiculos();
}

function dadosVeiculos() 
{ ?>
    <div class='dados-veiculos section-data'>
        <p class='title-section'>Dados do veículo</p>
        <div class='row-fields'>
            <div><input type='text' class='input-field' name='modelo-veiculo' placeholder='Modelo do veículo'required/></div>
            <div><input type='text' class='input-field ano' name='ano' placeholder='Ano' required/></div>
            <div>
                <input type='text' class='input-field valor-fipe' name='valor-fipe' placeholder='Valor Fipe' required/>
                <a href="https://veiculos.fipe.org.br/"  target="_blank" class="link-field">Consultar a FIPE</a>
            </div>
        </div>
        <div class='row-fields'>
            <select class='input-field' name='situacao-veiculo' placeholder='Situação do veículo'required >
                <option value="">Situação do veículo</option>
                <option value="Funcionando normalmente">Funcionando normalmente</option>
                <option value="Rebaixado">Rebaixado</option>
                <option value="Blindado">Blindado</option>
                <option value="Batido">Batido</option>
                <option value="Sem funcionamento">Sem funcionamento</option>
            </select>
            <input type='text' class='input-field' name='cor' placeholder='Cor' required/>
            <input type='text' class='input-field' name='placa' placeholder='Placa' required/>
        </div>
    </div>
<?php }

function origem() {
    ?>
        <div class='origem section-data'>
            <p class='title-section'>Origem</p>
            <div class="form-check ">
                <input class="form-check-input position-static tipo_origem" type="radio" name="origem" id="levar_veiculo" value="levar_veiculo" checked>
                <label class="form-check-label" for="levar_veiculo">Vou <strong>levar</strong> o automóvel até o pátio da transportadora</label>
            </div>
            <div class="form-check">
                <input class="form-check-input position-static tipo_origem" type="radio" name="origem" id="buscar_veiculo" value="buscar_veiculo">
                <label class="form-check-label" for="buscar_veiculo">Quero <strong>coleta</strong> do automóvel no meu endereço</label>
            </div>

            <div id="origem-levar">
                <div class='group-fields'>
                    <select type='text' class='input-field estado' name='origem-estado' placeholder='Estado' required>
                        <?= getUfs();?>
                    </select>
                    <select type='text' class='input-field cidade' name='origem-cidade' placeholder='Cidade' required disabled>
                        <option value="">Cidade</option>
                    </select>
                </div>
                <select type='text' class='input-field endereco' name='origem-endereco' placeholder='Endereço' required disabled>
                    <option value="">Endereço</option>
                </select>
            </div>

            <div id="origem-buscar">
                <div class='group-fields'>
                    <input type='tel' class='input-field cep' name='origem-cep' placeholder='CEP' required />
                    <select type='text' class='input-field estado' name='origem-estado' placeholder='Estado' required>
                        <?= getUfs();?>
                    </select>
                    <input type='text' class='input-field cidade' name='origem-cidade' placeholder='Cidade' required />
                    <input type='text' class='input-field bairro' name='origem-bairro' placeholder='Bairro' required/>
                </div>
                <div class='group-fields-1-3'>
                    <input type='text' class='input-field field-major endereco' name='origem-endereco' placeholder='Digite o endereço' required/>
                    <input type='tel' class='input-field numero' name='origem-numero' placeholder='Número' required/>
                </div>
            </div>
        </div>
<?php           
}

function destino() {
    ?>
    <div class='destino section-data'>
        <p class='title-section'>Destino</p>
        <div class="form-check ">
            <input class="form-check-input position-static tipo_destino" type="radio" name="destino" id="retirar_veiculo" value="retirar_veiculo" checked>
            <label class="form-check-label" for="retirar_veiculo">Vou <strong>retirar</strong> o automóvel no pátio da transportadora</label>
        </div>
        <div class="form-check">
            <input class="form-check-input position-static tipo_destino" type="radio" name="destino" id="levem_veiculo" value="levem_veiculo">
            <label class="form-check-label" for="levem_veiculo">Quero <strong>entrega</strong> do automóvel no meu endereço</label>
        </div>

        <div  id="destino-retirar">
            <div class='group-fields'>
                <select type='text' class='input-field estado' name='destino-estado' placeholder='Estado' required>
                    <?= getUfs();?>
                </select>
                <select type='text' class='input-field cidade' name='destino-cidade' placeholder='Cidade' required disabled>
                    <option value="">Cidade</option>
                </select>
            </div>
            <select type='text' class='input-field endereco' name='destino-endereco' placeholder='Endereço' required disabled>
                <option value="">Endereço</option>
            </select>
        </div>

        <div id="destino-levar">
            <div class='group-fields'>
                <input type='tel' class='input-field cep' name='destino-cep' placeholder='CEP' required/>
                <select type='text' class='input-field estado' name='destino-estado' placeholder='Estado' required>
                    <?= getUfs();?>
                </select>
                <input type='text' class='input-field cidade' name='destino-cidade' placeholder='Cidade' required />
                <input type='text' class='input-field bairro' name='destino-bairro' placeholder='Bairro' required />
            </div>
            <div class='group-fields-1-3'>
                <input type='text' class='input-field field-major endereco' name='destino-endereco' placeholder='Digite o endereço' required/>
                <input type='tel' class='input-field numero' name='destino-numero' placeholder='Número' required />
            </div>
        </div>
    </div>
<?php           
}

function getUfs() 
{ ?>
    <option value="">Estado</option>
    <option value="AC">Acre</option>
    <option value="AL">Alagoas</option>
    <option value="AP">Amapá</option>
    <option value="AM">Amazonas</option>
    <option value="BA">Bahia</option>
    <option value="CE">Ceará</option>
    <option value="DF">Distrito Federal</option>
    <option value="ES">Espirito Santo</option>
    <option value="GO">Goiás</option>
    <option value="MA">Maranhão</option>
    <option value="MS">Mato Grosso do Sul</option>
    <option value="MT">Mato Grosso</option>
    <option value="MG">Minas Gerais</option>
    <option value="PA">Pará</option>
    <option value="PB">Paraíba</option>
    <option value="PR">Paraná</option>
    <option value="PE">Pernambuco</option>
    <option value="PI">Piauí</option>
    <option value="RJ">Rio de Janeiro</option>
    <option value="RN">Rio Grande do Norte</option>
    <option value="RS">Rio Grande do Sul</option>
    <option value="RO">Rondônia</option>
    <option value="RR">Roraima</option>
    <option value="SC">Santa Catarina</option>
    <option value="SP">São Paulo</option>
    <option value="SE">Sergipe</option>
    <option value="TO">Tocantins</option>
<?php }

function htmlSuccess($id) { 
    $message = get_post_meta($id, 'mensagem_sucesso_solicitacoes', true);
    ?>
    <div id="html-success"class="content-success" style="display:none">
        <h2>Sua solicitação do orçamento foi enviado com sucesso!</h2>
        <div class="content-success">
            <?= $message; ?>
        </div>
    </div>
<?php }?>