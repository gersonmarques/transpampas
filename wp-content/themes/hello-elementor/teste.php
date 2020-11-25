<?php 
//Template Name: Solicitar

get_header();
?>


<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/vendor/bootstrap/css/bootstrap.min.css" ?>" />
<link rel="stylesheet" type="text/css" href="<?= get_site_url()."/assets/css/solicitar-transporte.css" ?>" />
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery-3.5.1.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/jquery/jquery.mask.js"?>"></script>
<script src="<?= get_site_url()."/assets/vendor/bootstrap/js/bootstrap.min.js"?>"></script>
<script src="<?= get_site_url()."/assets/js/solicitar-transporte.js"?>"></script>


<div class="site-main">
    <div class="post-content">
        <?php the_content(); ?>
    </div>    
    <div class="container">
        <div class="form-check form-check-inline">
            <input class="form-check-input tipo_dados_pessoais" type="radio" name="inlineRadioOptions" id="pessoa_fisica" value="pessoa_fisica" checked>
            <label class="form-check-label" for="pessoa_fisica">Pessoa física</label>
        </div>
        <div class="form-check form-check-inline">
            <input class="form-check-input tipo_dados_pessoais" type="radio" name="inlineRadioOptions" id="pessoa_juridica" value="pessoa_juridica">
            <label class="form-check-label" for="pessoa_juridica">Pessoa jurídica</label>
        </div>
        <div class='container-fields col-md-8'>
            <div id='pessoa_fisica_wrapper'>
                <?= pessoa_fisica();?>
            </div>
            <div id='pessoa_juridica_wrapper'>
                <?= pessoa_juridica(); ?>
            </div>

            <?= documentos(); ?>    
        </div>
        

        <div>
            <button class='btn'>Próximo</button>
        </div>
    </div>
</div>

<?php 
get_footer();

/**
 * 
 *          <?= origem(); ?>
 *          <?= destino(); ?>
 */

function pessoa_fisica()
{ 
    return "
        <div class='dados-pessoais section-data'>
            <p class='title-section'>Dados Pessoais</p>
            <input type='text' class='input-field cpf' maxLength='11' name='cpf' placeholder='CPF' />
            <input type='text' class='input-field' name='nome' placeholder='Nome' />
            <input type='email' class='input-field' name='e-mail' placeholder='E-mail' />
            <div class='group-fields'>
                <input type='tel' class='input-field whatsapp' name='whatsapp' placeholder='Whatsapp' />
                <input type='tel' class='input-field telefone-fixo ' name='telefone-fixo' placeholder='Telefone Fixo' />
            </div>
        </div>
        <div class='dados-veiculos section-data'>
            <p class='title-section'>Dados do veículo</p>
            <div class='row-fields'>
                <input type='text' class='input-field' name='modelo-veiculo' placeholder='Modelo do veículo' />
                <input type='text' class='input-field' name='ano' placeholder='Ano' />
                <input type='text' class='input-field' name='valor-fipe' placeholder='Valor Fipe' />
            </div>
            <div class='row-fields'>
                <input type='text' class='input-field' name='situacao-veiculo' placeholder='Situação do veículo' />
                <input type='text' class='input-field' name='cor' placeholder='Cor' />
                <input type='text' class='input-field' name='placa' placeholder='Placa' />
            </div>
        </div>
    ";
 }

 function pessoa_juridica()
{ 
    return "
        <div class='dados-empresa section-data'>
            <p class='title-section'>Dados da Empresa</p>
            <input type='text' class='input-field' maxLength='11' name='cnpj' placeholder='CNPJ' />
            <input type='text' class='input-field' name='inscricao-estadual' placeholder='Inscrição Estadual' />
            <input type='text' class='input-field' name='razao-social' placeholder='Razão Social' />
            <div class='group-fields'>
                <input type='text' class='input-field' name='nome-responsavel' placeholder='Nome do responsável' />
                <input type='text' class='input-field dtnasc-responsavel' name='dtnasc-responsavel' placeholder='Data Nasc. responsável' />
            </div>
        </div>
        <div class='dados-veiculos section-data'>
            <p class='title-section'>Dados do veículo</p>
            <div class='row-fields'>
                <input type='text' class='input-field' name='modelo-veiculo' placeholder='Modelo do veículo' />
                <input type='text' class='input-field' name='ano' placeholder='Ano' />
                <input type='text' class='input-field' name='valor-fipe' placeholder='Valor Fipe' />
            </div>
            <div class='row-fields'>
                <input type='text' class='input-field' name='situacao-veiculo' placeholder='Situação do veículo' />
                <input type='text' class='input-field' name='cor' placeholder='Cor' />
                <input type='text' class='input-field' name='placa' placeholder='Placa' />
            </div>
        </div>
    ";  
}

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
                    <input type='text' class='input-field' name='estado' placeholder='Estado' />
                    <input type='text' class='input-field' name='cidade' placeholder='Cidade' />
                </div>
                <input type='text' class='input-field' name='endereco' placeholder='Endereço' />
            </div>

            <div id="origem-buscar">
                <div class='group-fields'>
                    <input type='tel' class='input-field' name='cep' placeholder='CEP' />
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
                    <input type='tel' class='input-field' name='cep' placeholder='CEP' />
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
?>