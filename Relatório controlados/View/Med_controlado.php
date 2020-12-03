<div id="page-content" class="p20 clearfix">
    <div class="container-fluid">
        <div class="row">
            <div class="container-fluid">
                <div class="row">
                    <div class="panel panel-default" style="border-radius: 5px">
                        <div class="page-title clearfix" style="min-height: 100px; box-shadow: 0px 0px 0px 1px #CDCDCD; border-radius: 5px">
                            <div class="row" style="text-align: center; margin-top: 18px;">
                                <div class="btn-group">
                                    <a href="<?php echo base_url('../index.php/relatorios');?>">
                                        <button class="btn btn-primary btn-lg" type="button" style="border-radius: 5px">
                                            Início
                                        </button>
                                    </a>
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Cadastro
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('index.php/relatorios/medicos/');?>" style="font-size: 13px"> Médicos </a></li>
                                    </ul>   
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Produtos
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('index.php/relatorios/produtos/');?>" style="font-size: 13px"> Produtos Vendas </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/produtosEstoque/');?>" style="font-size: 13px"> Produtos Estoque </a></li>
                                    </ul>   
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Vendas 
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas/');?>" style="font-size: 13px"> Vendas Farmácia Popular </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas_cupom/');?>" style="font-size: 13px"> Vendas por Cupom </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas_horario/');?>" style="font-size: 13px"> Vendas por Horário </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas_subgrupo/');?>" style="font-size: 13px"> Vendas por SubGrupos </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/colaborador/')?>" style="font-size: 13px"> Vendas por Colaborador </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/indicadores_colaborador/')?>" style="font-size: 13px"> Indicadores por Colaborador </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendasClientes/')?>" style="font-size: 13px"> Vendas por Clientes </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas_dia/')?>" style="font-size: 13px"> Vendas por Dia </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/vendas_delivery/')?>" style="font-size: 13px"> Vendas Delivery </a></li>
                                        <li><a href="<?php echo base_url('index.php/estimates/')?>" style="font-size: 13px"> Orçamentos </a></li>
                                    </ul>   
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Estoque
                                    </button>
                                    <ul class="dropdown-menu">                                        
                                        <li><a href="<?php echo base_url('index.php/relatorios/estoque_trocasedevolucoes/');?>" style="font-size: 13px"> Trocas e Devoluções </a></li>                                        
                                        <li><a href="<?php echo base_url('index.php/relatorios/ruptura/');?>" style="font-size: 13px"> Ruptura </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/excesso/');?>" style="font-size: 13px"> Excesso </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/rmnr/');?>" style="font-size: 13px"> Medicamentos Controlados (RMNR) </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/conferencia_notas/');?>" style="font-size: 13px"> Conferência de Notas </a></li>
                                        <li><a href="<?php echo base_url('index.php/relatorios/notas_transito/');?>" style="font-size: 13px"> Notas em Trânsito </a></li>
                                    </ul>   
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Financeiro
                                    </button>
                                    <ul class="dropdown-menu">                                        
                                        <li><a href="<?php echo base_url('index.php/relatorios/recargas/');?>" style="font-size: 13px"> Recargas </a></li>
                                    </ul>   
                                </div>
                                <div class="btn-group">
                                    <button class="btn btn-primary btn-lg" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border-radius: 5px; margin-left: -5px">
                                        Parâmetros
                                    </button>
                                    <ul class="dropdown-menu">                                        
                                        <li><a href="<?php echo base_url('index.php/relatorios/per_capita/')?>" style="font-size: 13px"> Nr. Funcionários </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>
</div>

<div class="container"  style="background-color: white; box-shadow: 0px 0px 0px 1px #CDCDCD; border-radius: 5px; padding: 2%">
	<div class="container" style="width: 70%">
		<form action="">
			<div class="row"> <!-- Primeiro bloco -->
				<div class="col-sm-1">
					<h5>Período:</h5>
				</div>
				<div class="col-sm-3">								
					<select class="form-control" name="" id="">
						<option value="">Anual</option>
						<option value="">Primeiro Trimestre</option>
						<option value="">Segundo Trimestre</option>
						<option value="">Terceiro Trimestre</option>
						<option value="">Quarto Trimestre</option>
						<option value="">Mensal</option>
					</select>	
				</div>			

				<div class="col-sm-1">
					<h5>Mês:</h5>
				</div>
				<div class="col-sm-3">					
					<select class="form-control" name="" id="">
						<option value="">Janeiro</option>
						<option value="">Fevereiro</option>
						<option value="">Março</option>
						<option value="">Abril</option>
						<option value="">Maio</option>
						<option value="">Junho</option>
						<option value="">Julho</option>
						<option value="">Agosto</option>
						<option value="">Setembro</option>
						<option value="">Outubro</option>
						<option value="">Novembro</option>
						<option value="">Dezembro</option>
					</select>						
				</div>

				<div class="col-sm-1">
					<h5>Ano:</h5>
				</div>
				<div class="col-sm-3">
					<input type="text" maxlength="4" class="form-control" placeholder="Ano" style="text-align: center;">	
				</div>
			</div>
			<div class="row" style="margin-top: 1%;"> <!-- Segundo bloco -->
				<div class="col-sm-1">
					<h5>Loja:</h5>
				</div>
				<div class="col-sm-3">
					<input type="text" class="form-control" placeholder="Código da filial">	
				</div>
				<div class="col-sm-8">
					<input type="text" class="form-control" placeholder="Nome da filial">	
				</div>				
			</div>
			<div class="row" style="margin-top: 1%;"> <!-- Terceiro bloco -->
				<div class="col-sm-1">
					<label for="listas" class=""><h5 class="">Listas:</h5></label>
				</div>
				<div class="col-sm-11">				
					<select name="" id="" class="form-control">
						<option value="">Todas</option>
						<option value="">03</option>
						<option value="">A1</option>
						<option value="">A2</option>
						<option value="">A3</option>
						<option value="">A5</option>
						<option value="">B1</option>
						<option value="">B2</option>
						<option value="">C1</option>
					</select>	
				</div>
			</div>
			<div class="row" style="margin-top: 1%;"> <!-- Quarto bloco -->	
				<div class="col-sm-1"></div>					
				<div class="col-sm-11">
					<div class="form-group">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
							<label class="form-check-label" for="defaultCheck1">
								Exibe Listagem das Aquisições de Medicamentos
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
							<label class="form-check-label" for="defaultCheck1">
								Exibe página de abertura do balanço
							</label>
						</div>
						<div class="form-check">
							<input class="form-check-input" type="checkbox" value="" id="defaultCheck1">
							<label class="form-check-label" for="defaultCheck1">
								Exibe termo de abertura e fechamento
							</label>
						</div>
					</div>
				</div>				
			</div>
			<div class="row"> <!-- Botão -->
				<div class="col-sm-1"></div>
				<div class="col-sm-9"></div>
				<div class="col-sm-1">
					<button class="btn btn-primary">
						imprimir
					</button>
				</div>
			</div>
		</form>
	</div>
</div>
