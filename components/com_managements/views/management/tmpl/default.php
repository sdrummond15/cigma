<jdoc:include type="message"/>
<?php JHtml::_('behavior.keepalive'); ?>
<?php 

if ($this->check === false) :

    echo '<h1 class="nopertence">Este imóvel não existe ou não pertence a esse usuário.</h1>';
    echo '<input type="button" class="btn btn-return" value="voltar" onclick="history.back();" />';
    echo "<script>setTimeout('history.go(-1)', 5000)</script>";

else:

    $id_management = '';
    $owner_id_management = '';
    $type_management = '';
    $business_management = '';
    $sale_price_management = 0;
    $rent_price_management = 0;
    $sharing_price_management = 0;
    $iptu_management = '';
    $safe_bail_management = '';
    $safe_fire_management  = '';
    $market_value_management = '';
    $announced_management = 0;
    $rented_management = '';
    $date_rented_management = '';
    $condominium_management = '';
    $animal_management = 0;
    $street_management = '';
    $number_management = '';
    $complement_management = '';
    $district_management = '';
    $region_management = '';
    $city_management = 0;
    $state_management = 0;
    $cep_management = '';
    $suites_management = '';
    $bedrooms_management = '';
    $bathrooms_management = '';
    $rooms_management = '';
    $garage_management = '';
    $garage_desc_management = '';
    $total_area_management = '';
    $building_area_management = '';
    $description_management = '';
    $video_management = '';
    $cover_image_management = '';
    $registry_management = '';
    $registry_office_management = '';
    $iptu_register_management = '';
    $energy_management = '';
    $number_energy_management = '';
    $clock_energy_management = '';
    $water_management = '';
    $number_water_management = '';
    $clock_water_management = '';
    $gate_keys_management = '';
    $gate_controls_management = '';
    $security_alarm_management = '';

    if (!empty($this->management)):

        foreach ($this->management as $management) {
            $id_management = $management->id;
            $owner_id_management = $management->owner_id;
            $type_management = $management->type;
            $business_management = $management->business;
            $sale_price_management = $management->sale_price;
            $rent_price_management = $management->rent_price;
            $sharing_price_management = $management->sharing_price;
            $iptu_management = $management->iptu;
            $safe_bail_management = $management->safe_bail;
            $safe_fire_management = $management->safe_fire;
            $market_value_management = $management->market_value;
            $announced_management = $management->announced;
            $rented_management = $management->rented;
            $date_rented_management = $management->date_rented;
            $condominium_management = $management->condominium;
            $animal_management = $management->animal;
            $street_management = $management->street;
            $number_management = $management->number;
            $complement_management = $management->complement;
            $district_management = $management->district;
            $region_management = $management->region;
            $city_management = $management->city;
            $state_management = $management->state;
            $cep_management = $management->cep;
            $suites_management = $management->suites;
            $bedrooms_management = $management->bedrooms;
            $bathrooms_management = $management->bathrooms;
            $rooms_management = $management->rooms;
            $garage_management = $management->garage;
            $garage_desc_management = $management->garage_desc;
            $total_area_management = $management->total_area;
            $building_area_management = $management->building_area;
            $description_management = $management->description;
            $video_management = $management->video;
            $cover_image_management = $management->cover_image;
            $registry_management = $management->registry;
            $registry_office_management = $management->registry_office;
            $iptu_register_management = $management->iptu_register;
            $energy_management = $management->energy;
            $number_energy_management = $management->number_energy;
            $clock_energy_management = $management->clock_energy;
            $water_management = $management->water;
            $number_water_management = $management->number_water;
            $clock_water_management = $management->clock_water;
            $gate_keys_management = $management->gate_keys;
            $gate_controls_management = $management->gate_controls;
            $security_alarm_management = $management->security_alarm;
        }

    endif;

//Converte em array para encontrar ocorrencia
    $business_management = explode(', ', $business_management);

//Converte em data de locação
    if (!empty($date_rented_management) && $date_rented_management != '0000-00-00') {
        $date_rented_management = explode('-', $date_rented_management);
        $date_rented_management = $date_rented_management[2] . '/' . $date_rented_management[1] . '/' . $date_rented_management[0] . '/';
    } else {
        $date_rented_management = '';
    }

//Mensagens
    $seguro_fianca = "Caso o imóvel possua seguro fiança favor informar o valor";
    $seguro_incendio = "Caso o imóvel possua seguro incêndio favor informar o valor";
    $condominio = "Caso o imóvel possua condomínio favor informar o valor";
    $logradouro = "Informa a rua, avenida, praça, beco, br, etc., onde se encontra seu imóvel";
    $completmento = "Caso possua algum complemento como sala, andar, bloco, etc.";
    $numero = "Informe o número do imóvel, caso não tenha informe S/N";
    $bairro = "Informe o bairro que se encontra seu imóvel";
    $regiao = "Informe a região que se encontra seu imóvel";
    $estado = "Informe o estado que se encontra seu imóvel";
    $cidade = "Informe a cidade que se encontra seu imóvel";
    $cep = "Informe o CEP do imóvel";
    $suites = "Informe quantas suítes seu imóvel possui";
    $quartos = "Informe quantos quartos seu imóvel possui";
    $banheiros = "Informe quantos banheiros seu imóvel possui";
    $salas = "Informe quantas salas seu imóvel possui";
    $vagasGaragem = "Informe quantas vagas de garagem seu imóvel possui";
    $descVagasGaragem = "Informe a descrição da vaga caso seja demarcada. Ex.: 202.";
    $areaTotal = "Informe a área total do seu imóvel possui";
    $areaConstruida = "Informe a área construída do seu imóvel possui";
    $descricao = "Faça um descrição detalhada do seu imóvel, informando seus diferenciais, destaques e informações adicionais";
    $matricula = "Informe o número de matrícula registrada para o imóvel";
    $video = "Informe a url completa do video do imóve no youtube";
    $imagem_capa = "Faça o upload da imagem de capa do seu imóvel";
    $fotos = "Faça o upload das imagens de seu imóvel e torne-o mais atrativo";
    $cartorio = "Informe o cartório que foi realizado o registro do imóvel";
    $iptu = "Informe o número de cadastro que se encontra no IPTU do imóvel";
    $energia = "Informe se o imóvel possiu instalação de energia elétrica";
    $numeroEnergia = "Informe o número de cadastro da conta de energia elétrica do imóvel";
    $qtdChaves = "Informe a quantidade de chaves que serão entregues";
    $qtdControles = "Informe a quantidade de controles que serão entregues";
    $alarme = "Informe a senha dos alarmes do imóvel";
    $anunciar = "Informe SIM para anunciar este imóvel e NÃO para que ele apenas fique gravado para seu controle e não seja anunciado na plataforma";
    $valor_venal = "Informe o valor que este imóvel alcançaria para compra e venda à vista, segundo as condições do mercado";
    ?>

    <?php

//ACTION
//    $action = 'insert';
//    if (!empty($id_management))
//        $action = 'update';

    ?>
    <div id="management" class="row-fluid">
        <h1><?php echo (!empty($id_management)) ? 'Alterar' : 'Adicionar'; ?> Imóvel</h1>
        <div class="msgs">
            <span><i class="fa fa-times-circle" aria-hidden="true"></i></span>
            <p></p>
        </div>
        <form id="insert-management" action="<?php echo JRoute::_('index.php?option=com_managements&task=managements.save'); ?>" method="post" class="form-validate form-horizontal" enctype="multipart/form-data">

            <!-- progressbar -->
            <ul id="progressbar">
                <li class="active"></li>
                <li></li>
                <li></li>
                <li></li>
                <li></li>
            </ul>

            <!-- fieldsets -->
            <fieldset id="dados-principais" class="page-slide">

                <h2><i class="fa fa-home" aria-hidden="true"></i> Dados do imóvel</h2>

                <div id="types">
                    <div class="control-group span3">
                        <label class="label-management">Escolha o Tipo de Imóvel: <span class="required">*</span></label>
                    </div>
                    <div class="control-group span9">
                        <div id="botoes">
                            <ul class="options_type">
                                <li class="button_type">
                                    <label for="type1">
                                        <input type="radio" name="type" id="type1" value="1" <?php if (empty($type_management) || $type_management == 1) echo 'checked'; ?>>
                                        <span>Residencial</span>
                                    </label>
                                </li>
                                <li class="button_type">
                                    <label for="type2">
                                        <input type="radio" name="type" id="type2" value="2" <?php if ($type_management == 2) echo 'checked'; ?>>
                                        <span>Comercial</span>
                                    </label>
                                </li>
                                <li class="button_type">
                                    <label for="type3">
                                        <input type="radio" name="type" id="type3" value="3" <?php if ($type_management == 3) echo 'checked'; ?>>
                                        <span>Residencial e Comercial</span>
                                    </label>
                                </li>
                                <li class="button_type">
                                    <label for="type4">
                                        <input type="radio" name="type" id="type4" value="4" <?php if ($type_management == 4) echo 'checked'; ?>>
                                        <span>Terreno</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="business">
                    <div class="span3">
                        <label class="label-management">Como deseja anunciar seu imóvel:</label>
                    </div>
                    <div class="span9">
                        <div>
                            <div class="control-group span3 control-business">
                                <input type="checkbox" name="business[]" id="vender" value="1" <?php if (in_array(1, $business_management)) echo 'checked'; ?>/>
                                <label for="vender"><span></span>Vender</label>
                            </div>
                            <div class="control-group span9 control-business value-sale">
                                <label>Informe o valor de venda: <span class="required">*</span></label>
                                <input type="text" id="valor-venda" name="sale_price" size="15" value="<?php echo 'R$ ' . number_format($sale_price_management, 2, ',', '.'); ?>" required/>
                            </div>
                        </div>
                        <div>
                            <div class="control-group span3 control-business">
                                <input type="checkbox" name="business[]" id="alugar" value="2" <?php if (in_array(2, $business_management)) echo 'checked'; ?>/>
                                <label for="alugar"><span></span>Alugar</label>
                            </div>
                            <div class="control-group span9 control-business value-rent">
                                <label>Informe o valor do aluguel: <span class="required">*</span></label>
                                <input type="text" id="valor-aluguel" name="rent_price" size="15" value="<?php echo 'R$ ' . number_format($rent_price_management, 2, ',', '.'); ?>" required/>
                            </div>
                        </div>
                        <div>
                            <div class="control-group span3 control-business last-business">
                                <input type="checkbox" name="business[]" id="partilhar" value="3" <?php if (in_array(3, $business_management)) echo 'checked'; ?>/>
                                <label for="partilhar"><span></span>Partilhar</label>
                            </div>
                            <div class="control-group span9 control-business last-business value-sharing">
                                <label>Informe o valor de partilhamento: <span class="required">*</span></label>
                                <input type="text" id="valor-partilha" name="sharing_price" size="15" value="<?php echo 'R$ ' . number_format($sharing_price_management, 2, ',', '.'); ?>" required/>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="detalhes-princiais">
                    <div class="span6">
                        <div id="iptu">
                            <div class="span6">
                                <label class="label-management">Valor do IPTU p/mês:</label>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <input type="text" id="valor-iptu" name="iptu" value="<?php if ($iptu_management != 0.00) echo 'R$ ' . number_format($iptu_management, 2, ',', '.'); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div id="condominium">
                            <div class="span6">
                                <label class="label-management hasPopover" data-content="<?php echo $condominio; ?>">Valor do Condomínio p/mês:</label>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <input type="text" id="condominio" name="condominium" value="<?php if ($condominium_management != 0.00) echo 'R$ ' . number_format($condominium_management, 2, ',', '.'); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div id="safe_bail">
                            <div class="span6">
                                <label class="label-management hasPopover" data-content="<?php echo $seguro_fianca; ?>">Valor do Seguro Fiança p/mês:</label>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <input type="text" id="seguro-fianca" name="safe_bail" value="<?php if ($safe_bail_management != 0.00) echo 'R$ ' . number_format($safe_bail_management, 2, ',', '.'); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div id="safe_fire">
                            <div class="span6">
                                <label class="label-management hasPopover" data-content="<?php echo $seguro_incendio; ?>">Valor do Seguro Incêndio p/mês:</label>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <input type="text" id="seguro-incendio" name="safe_fire" value="<?php if ($safe_fire_management != 0.00) echo 'R$ ' . number_format($safe_fire_management, 2, ',', '.'); ?>"/>
                                </div>
                            </div>
                        </div>

                        <div id="market_value">
                            <div class="span6">
                                <label class="label-management hasPopover" data-content="<?php echo $valor_venal; ?>">Valor Venal: <span class="required">*</span></label>
                            </div>
                            <div class="span6">
                                <div class="control-group">
                                    <input type="text" id="valor-venal" name="market_value" value="<?php if ($market_value_management != 0.00) echo 'R$ ' . number_format($market_value_management, 2, ',', '.'); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="span6">
                        <div id="announced">
                            <div class="control-group span3">
                                <label class="label-management hasPopover right" data-content="<?php echo $anunciar; ?>">Anunciar imóvel: <span class="required">*</span></label>
                            </div>
                            <div class="control-group span9">
                                <div id="botoes">
                                    <ul class="options_yes_no">
                                        <li class="button_yes_no">
                                            <label for="announced-no">
                                                <input type="radio" name="announced" id="announced-no" value="1" <?php if ($announced_management == 1) echo 'checked'; ?>>
                                                <span class="no">Não</span>
                                            </label>
                                        </li>
                                        <li class="button_yes_no">
                                            <label for="announced-yes">
                                                <input type="radio" name="announced" id="announced-yes" value="2" <?php if ($announced_management == 0 || $announced_management == 2) echo 'checked'; ?>>
                                                <span class="yes">Sim</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="rented">
                            <div class="control-group span3">
                                <label class="label-management right">Alugado: <span class="required">*</span></label>
                            </div>
                            <div class="control-group span9">
                                <div id="botoes">
                                    <ul class="options_yes_no">
                                        <li class="button_yes_no">
                                            <label for="rented-no">
                                                <input type="radio" name="rented" id="rented-no" value="0" <?php if ($rented_management == 0) echo 'checked'; ?>>
                                                <span class="no">Não</span>
                                            </label>
                                        </li>
                                        <li class="button_yes_no">
                                            <label for="rented-yes">
                                                <input type="radio" name="rented" id="rented-yes" value="1" <?php if ($rented_management == 1) echo 'checked'; ?>>
                                                <span class="yes">Sim</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div id="locacao">
                            <div class="span3">
                                <label class="label-management">Data da locação: <span class="required">*</span></label>
                            </div>
                            <div class="span9">
                                <div class="control-group">
                                    <input type="text" id="data-locacao" name="date_rented" value="<?php echo $date_rented_management; ?>"/>
                                </div>
                            </div>
                        </div>

                        <div id="type">
                            <div class="control-group span3">
                                <label class="label-management right">Aceita animais: <span class="required">*</span></label>
                            </div>
                            <div class="control-group span9">
                                <div id="botoes">
                                    <ul class="options_yes_no">
                                        <li class="button_yes_no">
                                            <label for="animal-no">
                                                <input type="radio" name="animal" id="animal-no" value="0" <?php if ($animal_management == 0) echo 'checked'; ?>>
                                                <span class="no">Não</span>
                                            </label>
                                        </li>
                                        <li class="button_yes_no">
                                            <label for="animal-yes">
                                                <input type="radio" name="animal" id="animal-yes" value="1" <?php if ($animal_management == 1) echo 'checked'; ?>>
                                                <span class="yes">Sim</span>
                                            </label>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <?php if (!empty($this->owners)): ?>
                    <div id="proprietarios-usufrutarios" class="box">
                        <div class="span3">
                            <label class="label-management">Informe o(s) Cliente(s):</label>
                        </div>
                        <div class="span9">
                            <div id="box-prop-usu">
                                <?php foreach ($this->owners as $owner):
                                    if (!empty($owner->cpf)) {
                                        $doc = $owner->cpf;
                                    } else {
                                        $doc = $owner->cnpj;
                                    }

                                    $array_owner_id = explode(', ', $owner_id_management);

                                    $checked_owner = '';
                                    if (in_array($owner->id, $array_owner_id)) {
                                        $checked_owner = 'checked';
                                    }
                                    ?>
                                    <div class="control-group control-owner">
                                        <input type="checkbox" name="owner_id[]" id="<?php echo $owner->alias; ?>" value="<?php echo $owner->id; ?>" <?php echo $checked_owner; ?>/>
                                        <label for="<?php echo $owner->alias; ?>"><span></span><?php echo $owner->name . ' (' . $doc . ')'; ?></label>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <input type="button" name="next" class="next btn right" value="Avançar"/>

                <div class="cancel">
                    <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn white btn-delete right">Cancelar</a>
                </div>

            </fieldset>

            <fieldset id="dados-endereco" class="page-slide">
                <h2><i class="fa fa-map-marker" aria-hidden="true"></i> Localização do imóvel</h2>

                <div id="state-city-cep">

                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $cep; ?>">CEP: <span class="required">*</span></label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <input type="text" id="cep" name="cep" placeholder="00.000-000" class="width100" value="<?php echo $cep_management; ?>" required/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $estado; ?>">Estado: <span class="required">*</span></label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <select class="select_state" id="select_state" name="state" required>
                                <option value="">Selecione o estado</option>
                                <?php
                                foreach ($this->states as $state) {
                                    $selected = '';
                                    if ($state->id == $state_management) {
                                        $selected = 'selected';
                                    }
                                    echo '<option value="' . $state->uf . '" ' . $selected . '>' . $state->state . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $cidade; ?>">Cidade: <span class="required">*</span></label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <select class="select_city" id="select_city" name="city" required>
                                <option value="">Selecione a cidade</option>
                                <?php
                                foreach ($this->cities as $city) {
                                    $selected = '';
                                    if ($city->id == $city_management) {
                                        $selected = 'selected';
                                    }

                                    echo '<option value="' . $city->city . '" ' . $selected . '>' . $city->city . '</option>';
                                } ?>
                            </select>
                        </div>
                    </div>

                </div>

                <div id="district-region">
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $bairro; ?>">Bairro: <span class="required">*</span></label>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                            <input type="text" id="bairro" name="district" class="width100" value="<?php echo $district_management; ?>" required/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $regiao; ?>">Região:</label>
                    </div>
                    <div class="span5">
                        <div class="control-group">
                            <input type="text" id="regiao" name="region" class="width100" value="<?php echo $region_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="street">
                    <div class="span2">
                        <label class="label-management hasPopover" data-content="<?php echo $logradouro; ?>">Logradouro: <span class="required">*</span></label>
                    </div>
                    <div class="span10">
                        <div class="control-group">
                            <input type="text" id="logradouro" name="street" class="width100" value="<?php echo $street_management; ?>" required/>
                        </div>
                    </div>
                </div>

                <div id="number-complement">
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $numero; ?>">Número: <span class="required">*</span></label>
                    </div>
                    <div class="span2">
                        <div class="control-group">
                            <input type="text" id="numero" name="number" class="width100" value="<?php echo $number_management; ?>" required/>
                        </div>
                    </div>

                    <div class="span2">
                        <label class="label-management hasPopover right" data-content="<?php echo $completmento; ?>">Complemento:</label>
                    </div>
                    <div class="span7">
                        <div class="control-group">
                            <input type="text" id="complemento" name="complement" class="width100" value="<?php echo $complement_management; ?>"/>
                        </div>
                    </div>
                </div>

                <input type="button" name="next" class="next btn right" value="Avançar"/>
                <input type="button" name="previous" class="previous btn right" value="Voltar"/>

                <div class="cancel">
                    <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn white btn-delete right">Cancelar</a>
                </div>

            </fieldset>

            <fieldset id="caracteristicas" class="page-slide">
                <h2><i class="fa fa-info-circle" aria-hidden="true"></i> Características do imóvel</h2>

                <div id="suites-bedrooms-bathrooms-rooms">
                    <div class="span2">
                        <label class="label-management hasPopover" data-content="<?php echo $suites; ?>">Quarto(s) c/ Banheiro:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="suites" name="suites" class="width100" value="<?php echo $suites_management; ?>"/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $quartos; ?>">Quarto(s):</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="quartos" name="bedrooms" class="width100" value="<?php echo $bedrooms_management; ?>"/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $banheiros; ?>">Banheiro(s):</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="banheiros" name="bathrooms" class="width100" value="<?php echo $bathrooms_management; ?>"/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $salas; ?>">Sala(s):</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="salas" name="rooms" class="width100" value="<?php echo $rooms_management; ?>"/>
                        </div>
                    </div>
                    <div class="span2">
                        <label class="label-management hasPopover" data-content="<?php echo $vagasGaragem; ?>">Vaga(s) de Garagem:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="vagas-garagem" name="garage" class="width100" value="<?php echo $garage_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="garage-total_area-building_area">
                    <div class="span3">
                        <label class="label-management hasPopover" data-content="<?php echo $descVagasGaragem; ?>">Descrição da(s) Vaga(s):</label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <input type="text" id="desc-vagas-garagem" name="garage_desc" class="width100" value="<?php echo $garage_desc_management; ?>"/>
                        </div>
                    </div>

                    <div class="span2">
                        <label class="label-management hasPopover right" data-content="<?php echo $areaTotal; ?>">Área do Terreno:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="area-total" name="total_area" class="width100" value="<?php if (!empty($total_area_management)) echo number_format($total_area_management, 2, ',', '.'); ?>"/>
                        </div>
                    </div>

                    <div class="span2">
                        <label class="label-management hasPopover right" data-content="<?php echo $areaConstruida; ?>">Área Construida em m²:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="area-construida" name="building_area" class="width100" value="<?php if (!empty($building_area_management)) echo number_format($building_area_management, 2, ',', '.'); ?>"/>
                        </div>
                    </div>
                </div>

                <div id="description">
                    <div class="span2">
                        <label class="label-management hasPopover" data-content="<?php echo $descricao; ?>">Descreva o imóvel:</label>
                    </div>
                    <div class="span10">
                        <div class="control-group">
                            <textarea id="descricao" name="description" rows="10" cols="50" style="resize: none" class="width100"><?php echo $description_management; ?></textarea>
                        </div>
                    </div>
                </div>

                <input type="button" name="next" class="next btn right" value="Avançar"/>
                <input type="button" name="previous" class="previous btn right" value="Voltar"/>

                <div class="cancel">
                    <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn white btn-delete right">Cancelar</a>
                </div>

            </fieldset>

            <fieldset id="dados-midias" class="page-slide">
                <h2><i class="fa fa-picture-o" aria-hidden="true"></i> Videos e fotos do imóvel</h2>

                <div id="video-youtube">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $video; ?>">Informe a url do video do imóvel no youtube:</label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <input type="text" id="video" name="video" class="width100" value="<?php echo $video_management; ?>" placeholder="https://www.youtube.com/watch?v=videoImovel"/>
                        </div>
                    </div>
                </div>

                <div id="imagem-capa">
                    <div class="span3">
                        <div class="control-group">
                            <label class="label-management hasPopover" data-content="<?php echo $imagem_capa; ?>">Insira a foto de capa: <span class="required">*</span></label>
                        </div>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <?php
                            $requiredImage = '';
                            $cover_image = '';
                            if (empty($cover_image_management)) {
                                $requiredImage = 'required="true"';
                            } else {
                                $cover_image = explode('/', $cover_image_management);
                                $cover_image = end($cover_image);
                            } ?>
                            <input type="file" name="cover_image" id="cover-image" accept="image/*" class="photo" <?php echo $requiredImage; ?>/>
                            <label for="photo" class="desc-photo" id="capa"><?php echo $cover_image; ?></label>
                            <input type="button" class="btn btn-add" value="Selecionar foto"/>
                        </div>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <?php if (!empty($cover_image_management)): ?>
                                <div class="capa">
                                    <img id="thumb_cover_image" src="<?php echo $cover_image_management; ?>"/>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div id="fotos">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $fotos; ?>">Insira as fotos do seu imóvel:</label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <div class="control-photo">
                                <input type="file" name="photo[]" accept="image/*" class="photo" multiple/>
                                <label for="photo" class="desc-photo"></label>
                                <input type="button" class="btn btn-add" value="Selecionar foto"/>
                            </div>
                        </div>
                        <div class="control-group">
                            <a id="add" class="btn btn-white right"><i class="fa fa-plus" aria-hidden="true"></i> Fotos</a>
                        </div>
                    </div>
                </div>

                <div id="lista-fotos">
                    <?php if (!empty($this->photos)): ?>
                        <h3>Fotos do seu imóvel</h3>
                        <?php foreach ($this->photos as $photo):
                            $imgname = basename($photo->photo);

                            $image = getimagesize($photo->photo);
                            $width = $image[0];
                            $height = $image[1];
                            if ($width > $height) {
                                $tamanho = 'style="height:100%; width:auto; left: 50%; transform: translateX(-50%);"';
                            } else if ($width < $height) {
                                $tamanho = 'style="width:100%; height:auto; top: 50%; transform: translateY(-50%);"';
                            } else {
                                $tamanho = 'style="width:100%; height:100%;"';
                            }

                            ?>
                            <div class="thumbimg">
                                <img src="<?php echo $photo->photo; ?>" <?php echo $tamanho; ?> />
                                <div class="sombra"></div>
                                <div class="delete">
                                    <input type="button" class="deleteImg" value="&#xf1f8;" name="<?php echo $photo->id; ?>"/>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <input type="button" name="next" class="next btn right" value="Avançar"/>
                <input type="button" name="previous" class="previous btn right" value="Voltar"/>

                <div class="cancel">
                    <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn white btn-delete right">Cancelar</a>
                </div>

            </fieldset>

            <fieldset id="dados-adicionais" class="page-slide">
                <h2><i class="fa fa-file-text" aria-hidden="true"></i> Documentos do imóvel</h2>

                <div id="registry-registry_office-iptu_register">
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $matricula; ?>">Matrícula:</label>
                    </div>
                    <div class="span2">
                        <div class="control-group">
                            <input type="text" id="matricula" name="registry" class="width100" value="<?php echo $registry_management; ?>"/>
                        </div>
                    </div>

                    <div class="span1">
                        <label class="label-management hasPopover right" data-content="<?php echo $cartorio; ?>">Cartório:</label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <input type="text" id="cartorio" name="registry_office" size="1" value="<?php echo $registry_office_management; ?>"/><span> º Ofício de Registro de Imóveis</span>
                        </div>
                    </div>

                    <div class="span2">
                        <label class="label-management hasPopover right" data-content="<?php echo $iptu; ?>">Índice Cadastral IPTU:</label>
                    </div>
                    <div class="span3">
                        <div class="control-group">
                            <input type="text" id="numero-iptu" name="iptu_register" class="width100" value="<?php echo $iptu_register_management; ?>" maxlength="20"/>
                        </div>
                    </div>
                </div>

                <div id="energy">
                    <div class="control-group span2">
                        <label class="label-management hasPopover" data-content="<?php echo $energia; ?>">Energia Elétrica: </label>
                    </div>
                    <div class="control-group span10">
                        <div id="botoes">
                            <ul class="options_three">
                                <li class="button_three">
                                    <label for="energy1">
                                        <input type="radio" name="energy" id="energy1" value="1" <?php if (empty($energy_management) || $energy_management == 1) echo 'checked'; ?>>
                                        <span>Sem Instalação</span>
                                    </label>
                                </li>
                                <li class="button_three">
                                    <label for="energy2">
                                        <input type="radio" name="energy" id="energy2" value="2" <?php if ($energy_management == 2) echo 'checked'; ?>>
                                        <span>Coletivo</span>
                                    </label>
                                </li>
                                <li class="button_three">
                                    <label for="energy3">
                                        <input type="radio" name="energy" id="energy3" value="3" <?php if ($energy_management == 3) echo 'checked'; ?>>
                                        <span>Individual Ativa</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="number_energy">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $numeroEnergia; ?>">Número Instalação da Conta de Energia: <span class="required">*</span></label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <input type="text" id="numero-energia" name="number_energy" class="width100" value="<?php echo $number_energy_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="clock_energy">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $relogioEnergia; ?>">Número do Relógio de Energia: <span class="required">*</span></label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <input type="text" id="relogio-energia" name="clock_energy" class="width100" value="<?php echo $clock_energy_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="water">
                    <div class="control-group span1">
                        <label class="label-management hasPopover" data-content="<?php echo $agua; ?>">Água: </label>
                    </div>
                    <div class="control-group span11">
                        <div id="botoes">
                            <ul class="options_three">
                                <li class="button_three">
                                    <label for="water1">
                                        <input type="radio" name="water" id="water1" value="1" <?php if (empty($water_management) || $water_management == 1) echo 'checked'; ?>>
                                        <span>Sem Instalação</span>
                                    </label>
                                </li>
                                <li class="button_three">
                                    <label for="water2">
                                        <input type="radio" name="water" id="water2" value="2"<?php if ($water_management == 2) echo 'checked'; ?>>
                                        <span>Coletivo</span>
                                    </label>
                                </li>
                                <li class="button_three">
                                    <label for="water3">
                                        <input type="radio" name="water" id="water3" value="3"<?php if ($water_management == 3) echo 'checked'; ?>>
                                        <span>Individual Ativa</span>
                                    </label>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="number_water">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $numeroAgua; ?>">Número Instalação da Conta de Água: <span class="required">*</span></label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <input type="text" id="numero-agua" name="number_water" class="width100" value="<?php echo $number_water_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="clock_water">
                    <div class="span4">
                        <label class="label-management hasPopover" data-content="<?php echo $hidrometroAgua; ?>">Número Hidrômetro de Água: <span class="required">*</span></label>
                    </div>
                    <div class="span8">
                        <div class="control-group">
                            <input type="text" id="hidrometro-agua" name="clock_water" class="width100" value="<?php echo $clock_water_management; ?>"/>
                        </div>
                    </div>
                </div>

                <div id="keys_controls_alarm">
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $qtdChaves; ?>">Chaves:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="qtd-chaves" name="gate_keys" class="width100" value="<?php echo $gate_keys_management; ?>"/>
                        </div>
                    </div>
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $qtdControles; ?>">Controles:</label>
                    </div>
                    <div class="span1">
                        <div class="control-group">
                            <input type="text" id="qtd-controles" name="gate_controls" class="width100" value="<?php echo $gate_controls_management; ?>"/>
                        </div>
                    </div>
                    <div class="span1">
                        <label class="label-management hasPopover" data-content="<?php echo $alarme; ?>">Alarme:</label>
                    </div>
                    <div class="span7">
                        <div class="control-group">
                            <input type="text" id="alarme" name="security_alarm" class="width100" value="<?php echo $security_alarm_management; ?>"/>
                        </div>
                    </div>
                </div>

                <input type="button" name="previous" class="previous btn right" value="Voltar"/>

                <div class="cancel">
                    <button class="btn white right saveComp" type="submit" id="submit">Salvar</button>
                    <span class="right">&nbsp;</span>
                    <a href="<?php echo JRoute::_('index.php?option=com_managements'); ?>" class="btn white btn-delete right">Cancelar</a>
                </div>

            </fieldset>
        </form>
    </div>
<?php endif; ?>