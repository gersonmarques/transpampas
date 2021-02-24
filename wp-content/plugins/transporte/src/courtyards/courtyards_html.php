<?php 

class Courtyards_html{
    

    /**
     * Page of add events
     */
     public function html_add_courtyards(){
        require_once TRANSPORTE_PLUGIN_PATH . '/src/courtyards/courtyards.php';
        $title   = "Pátios";
        ?>
        <link rel="stylesheet" type="text/css" href="../wp-content/plugins/transporte/src/courtyards/css/courtyards.css">
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/courtyards/js/courtyards.js"></script>
        <script type="text/javascript" src="../wp-content/plugins/transporte/src/courtyards/js/courtyards_actions.js"></script>
        <div class="wrap">
            <h2><?php echo $title; ?></h2>
            <div class="notice notice-success is-dismissible">
                <p><strong>Settings saved.</strong></p>
            </div>
            <?php 
            if(isset($_GET['id']) && !empty($_GET['id'])){
                    echo $this->html_update_courtyards();
            }else{?>
                <div id="add-courtyards">
                    <form method="post">
                        <h3>Adicionar Pátios</h3>
                        <div class="group-add-courtyards">
                            <label for="titulo-courtyards">Título</label>
                            <input type="text" id="title-courtyards" name="title"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="cep-courtyards">CEP</label>
                            <input type="tel" id="cep-courtyards" name="cep"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="street-courtyards">Rua</label>
                            <input type="text" id="street-courtyards" name="street"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="number-courtyards">Número</label>
                            <input type="tel" id="number-courtyards" name="number"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="neighborhood-courtyards">Bairro</label>
                            <input type="text" id="neighborhood-courtyards" name="neighborhood"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="city-courtyards">Cidade</label>
                            <input type="text" id="city-courtyards" name="city"/>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="state-courtyards">Estado</label>
                            <select id="state-courtyards" name="state">
                                <?= getUfs();?>
                            </select>
                        </div>
                        <div class="group-add-courtyards">
                            <label for="reference-courtyards">Referência</label>
                            <textarea id="reference-courtyards" name="reference" rows='6'></textarea>
                        </div>
                        <input type="button" id="input_save" class="button button-primary" value="Salvar"/>
                        <div class="loader"></div>
                        <small></small>
                    </form>
                </div>
                <div class="table-courtyards">
                    <div class="actions-table">
                        <input type="button" id="input_change" class="button button-primary" value="Alterar" disabled/>
                        <input type="button" id="input_delete" class="button button-secondary" value="Delete" disabled/>
                        <div class='query-table'>
                            <div class="loader-search"></div>
                            <input type='text' id='query-courtyards'/>
                            <select id='param-courtyards'>
                                <option value="title">Title</option>
                                <option value="state">UF</option>
                                <option value="city">Cidade</option>
                                <option value="cep">CEP</option>
                                <option value="id">Código</option>
                            </select>
                            <input type='button' id='search-courtyards' class="button button-primary" value="Pesquisar"/>
                        </div>
                    </div>
                    <table class="list-courtyards wp-list-table widefat fixed striped">
                        <thead>
                        <tr>
                            <th style="width:5%;"></th>
                            <th style="width:30%;">Título</th>
                            <th>UF</th>
                            <th>Cidade</th>
                            <th>CEP</th>
                            <th style="width: 10%;text-align: center;">Código</th>
                        </tr>
                        </thead>
                        <tbody>
                            <?php
                            $courtyards = new Courtyards();
                            $result =  $courtyards->query_patios(true);
                            foreach($result as $key => $val):
                            ?>
                                </tr>
                                    <td><input type="checkbox" name="checkbox-actions" class="checkbox-actions" value="<?php echo $val->id?>"></td>
                                    <td><?php echo $val->title?></td>
                                    <td><?php echo $val->state?></td>
                                    <td><?php echo $val->city?></td>
                                    <td><?php echo $val->cep?></td>
                                    <td style="text-align: center;"><?php echo $val->id?></td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                    </table>
                </div>
            <?php }?>
        </div>
        <?php
    }
    
    function html_update_courtyards(){
        $id = $_GET['id'];
        require_once TRANSPORTE_PLUGIN_PATH . '/src/courtyards/courtyards.php';
        $courtyards = new Courtyards();
        $result = $courtyards->query_patios(true, $id);
    ?>
        <div id="update-courtyards">
            <form method="post">
                <h3>Atualizar Pátios</h3>
                <div class="group-add-courtyards">
                    <label for="id-courtyards">Código</label>
                    <input type="text" id="id-courtyards" name="id" value="<?php echo $result[0]->id;?>" disabled/>
                </div>
                <div class="group-add-courtyards">
                    <label for="titulo-courtyards">Título</label>
                    <input type="text" id="title-courtyards" name="title" value="<?php echo $result[0]->title;?>"/>
                </div>
                <div class="group-add-courtyards">
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