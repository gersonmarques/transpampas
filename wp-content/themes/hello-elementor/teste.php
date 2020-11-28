<?php 
//Template Name: Solicitar
?>


<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/vendor/bootstrap/css/bootstrap.min.css" ?>" />
<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/css/solicitar-transporte.css" ?>" />
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery-3.5.1.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery.mask.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/bootstrap/js/bootstrap.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/js/solicitar-transporte.js"?>"></script>
<script src="<?= get_site_url()."/assets/js/utils.js"?>"></script>

<?= get_header(); ?>

<div class="site-main">
    <div class="post-content">
        <?php the_content(); ?>
    </div>    
    <div class="container" id="form-solicitar-transporte">
        <div class="form-check form-check-inline">
            <input class="form-check-input tipo_dados_pessoais" type="radio" name="inlineRadioOptions" id="pessoa_fisica" value="pessoa_fisica" checked>
            <label class="form-check-label" for="pessoa_fisica">Pessoa física</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input tipo_dados_pessoais" type="radio" name="inlineRadioOptions" id="pessoa_juridica" value="pessoa_juridica">
            <label class="form-check-label" for="pessoa_juridica">Pessoa jurídica</label>
        </div>
        <div class='container-fields col-md-8'>
            <div class="menu">
                <p data-step="1" class="active menu-label">Dados pessoais e do veículo</p>
                <p data-step="2" class="menu-label">Origem e Destino</p>
                <p data-step="3" class="menu-label">Documentos</p>
            </div>
            <div class="step1 step step-active">
                <div id='pessoa_fisica_wrapper'>
                    <?= pessoa_fisica();?>
                </div>
                <div id='pessoa_juridica_wrapper'>
                    <?= pessoa_juridica(); ?>
                </div>
            </div>
            <div class="step2 step">
                <?= origem();?>
                <?= destino();?>
            </div>
            <div class="step3 step">
                <?= documentos();?>
            </div>
        </div>
        <div>
            <button class='btn btn-prev'>Voltar</button>
            <button class='btn btn-next'>Próximo</button>
        </div>
    </div>
    <div class="loading">
        <div class="square">
            <div class="spinner-border m-5 spinner-load" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </div>
    <div class="background-load"></div>
</div>

<?php 
get_footer();

function pessoa_fisica()
{ ?>
    <div class='dados-pessoais section-data'>
        <p class='title-section'>Dados Pessoais</p>
        <input type='tel' class='input-field cpf' name='cpf' placeholder='CPF' required />
        <input type='text' class='input-field rg' maxLength='14' name='rg' placeholder='RG' required />
        <input type='text' class='input-field' name='nome' placeholder='Nome Completo' required/>
        <input type='email' class='input-field' name='e-mail' placeholder='E-mail' required/>
        <div class='group-fields'>
            <input type='tel' class='input-field whatsapp' name='whatsapp' placeholder='Whatsapp' required/>
            <input type='tel' class='input-field telefone-fixo ' name='telefone-fixo' placeholder='Telefone Fixo' />
        </div>
    </div>
<?php
    dadosVeiculos();
 }

 function pessoa_juridica()
{ ?>
    <div class='dados-empresa section-data'>
        <p class='title-section'>Dados da Empresa</p>
        <input type='text' class='input-field' maxLength='11' name='cnpj' placeholder='CNPJ' required/>
        <input type='text' class='input-field' name='inscricao-estadual' placeholder='Inscrição Estadual'required />
        <input type='text' class='input-field' name='razao-social' placeholder='Razão Social' required/>
        <div class='group-fields'>
            <input type='text' class='input-field' name='nome-responsavel' placeholder='Nome do responsável' required/>
            <input type='text' class='input-field dtnasc-responsavel' name='dtnasc-responsavel' placeholder='Data Nasc. responsável' />
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
            <input type='text' class='input-field' name='modelo-veiculo' placeholder='Modelo do veículo'required/>
            <input type='text' class='input-field ano' name='ano' placeholder='Ano' required/>
            <input type='text' class='input-field valor-fipe' name='valor-fipe' placeholder='Valor Fipe' required/>
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
                <label class="form-check-label" for="buscar_veiculo">Quero que <strong>busquem</strong> o automóvel no meu endereço</label>
            </div>

            <div id="origem-levar">
                <div class='group-fields'>
                    <select type='text' class='input-field' name='estado' placeholder='Estado'>
                        <?= getUfs();?>
                    </select>
                    <input type='text' class='input-field' name='cidade' placeholder='Cidade' />
                </div>
                <input type='text' class='input-field' name='endereco' placeholder='Endereço' />
            </div>

            <div id="origem-buscar">
                <div class='group-fields'>
                    <input type='tel' class='input-field cep' name='cep' placeholder='CEP' />
                    <select type='text' class='input-field' name='estado' placeholder='Estado'>
                        <?= getUfs();?>
                    </select>
                    <input type='text' class='input-field' name='cidade' placeholder='Cidade' />
                    <input type='text' class='input-field' name='bairro' placeholder='Bairro' />
                </div>
                <div class='group-fields-1-3'>
                    <input type='text' class='input-field field-major' name='endereco' placeholder='Digite o endereço' />
                    <input type='tel' class='input-field' name='numero' placeholder='Número' />
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
            <label class="form-check-label" for="levem_veiculo">Quero que <strong>levem</strong> o automóvel no meu endereço</label>
        </div>

        <div  id="destino-retirar">
            <div class='group-fields'>
                <input type='text' class='input-field' name='estado' placeholder='Estado' />
                <input type='text' class='input-field' name='cidade' placeholder='Cidade' />
            </div>
            <input type='text' class='input-field' name='endereco' placeholder='Endereço' />
        </div>

        <div id="destino-levar">
            <div class='group-fields'>
                <input type='tel' class='input-field cep' name='cep' placeholder='CEP' />
                <input type='text' class='input-field' name='estado' placeholder='Estado' />
                <input type='text' class='input-field' name='cidade' placeholder='Cidade' />
                <input type='text' class='input-field' name='bairro' placeholder='Bairro' />
            </div>
            <div class='group-fields-1-3'>
                <input type='text' class='input-field field-major' name='endereco' placeholder='Digite o endereço' />
                <input type='tel' class='input-field' name='numero' placeholder='Número' />
            </div>
        </div>
    </div>
<?php           
}

function documentos()
{ ?>
  
    <div class='documentos section-data'>
        <p class='title-section'>Documentação</p>
        <div class='group-fields'>
            <input class="rg_cnh" type="file" id="rg_cnh" name="rg_cnh" style="display:none">
            <label class="rg_cnh_label label_input">Cópia/Foto legível de RG OU CNH (.pdf .jpg .png)</label>
            <input class="crlv" type="file" id="crlv" name="crlv" style="display:none">
            <label class="crlv_label label_input">Cópia/Foto legível de CRLV (.pdf .jpg .png)</label>
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
?>