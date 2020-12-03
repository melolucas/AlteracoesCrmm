<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Relatorios extends MY_Controller {

    function __construct()
    {
        parent::__construct();
        $this->init_permission_checker("relatorios");
    }

    // load ticket list view
    function index() {

        // Carrega a página e as operações dela
        // Acesso das Lojas

        if ($this->login_user->role_id == "2") {
            
            $this->load->model('Lojasprocedimento_model');
            $dados['lojaInformativo'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultSessao();

            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal'] = $dataFinal;

                $this->load->model('Lojas_model');
                $dados['lojasConec'] = $this->Lojas_model->PegarLoja();

                $dados['lojasConec'] = $this->session->set_userdata(array(
                       'endftp'  		=> $dados['lojasConec'][0]->ENDFTP,
                       'servidor'  		=> $dados['lojasConec'][0]->SERVIDOR,
                       'banco'  	    => $dados['lojasConec'][0]->BANCO,
                       'endereco'  		=> $dados['lojasConec'][0]->ENDERECO,
                       'cdloja'  		=> $dados['lojasConec'][0]->CDLOJA
                ));

                // Informações de clientes cadastrados
                $this->load->model('Lojasprocedimento_model');
                $dados['clientes_cadastrados'] = $this->Lojasprocedimento_model->selecionaClientesCadastrados();
                $dados['lojas'] = $this->Lojas_model->listarLojas();

                $this->template->rander('relatorios/index', $dados);

        } elseif ($this->login_user->role_id == "15" || $this->login_user->role_id == "23") {
                // Acesso do Regional e Direção

                $Job = $this->login_user->job_title;
                $roles =  $this->login_user->role_id;
               
                $dataInicio = date('d.m.Y');
                $dataFinal = date('d.m.Y');
                $dados['dataInicio'] =  date('d.m.Y');
                $dados['dataFinal'] =  date('d.m.Y');

                $this->load->model('Totalvenda_model');
                $this->load->model('TotalvendaMes_model');
                $this->load->model('TotalvendaGeral_model');
                $this->load->model('Lojas_model');

                $dados['lojasConec'] = $this->Lojas_model->listarLojasSuper();
                $dados['regiao'] = $this->Lojas_model->listarRegiao();
                $dados['loja'] = $this->Totalvenda_model->selecionaTotalVenda();
                $dados['mes'] = $this->TotalvendaMes_model->selecionaTotalVendaMes($dataInicio, $dataFinal);
                $dados['geral'] = $this->TotalvendaGeral_model->selecionaSupervisoresVendasGraf();
                $dados['totalDiario'] = $this->TotalvendaGeral_model->selecionaTotalDiario('alterardata', $dataInicio, $dataFinal);
                $dados['LojasVendas'] = $this->TotalvendaGeral_model->selecionaLojasVendasSupervisao('alterardata', $dataInicio, $dataFinal);
                $this->template->rander('relatorios/supervisao', $dados);

            } elseif ($this->login_user->role_id == "13" || $this->login_user->role_id == "27") {
                //Acesso da Supervisão
                
                $this->load->model('Totalvenda_model');
                $this->load->model('TotalvendaMes_model');
                $this->load->model('TotalvendaGeral_model');
                $this->load->model('Lojas_model');

                $dados['lojasConec'] = $this->Lojas_model->listarLojas();
                $dados['lojaRegiao'] = $this->Totalvenda_model->selecionaTotalVendaRegiao();
                $dados['mesReg'] = $this->TotalvendaMes_model->selecionaTotalVendaMesRegiao();
                $dados['geralReg'] = $this->TotalvendaGeral_model->selecionaTotalVendaGeralRegiao();

                $regiao = $this->input->post('id');
                $dataInicio = date('d.m.Y');
                $dataFinal = date('d.m.Y');
                $dados['dataInicio'] = date('d.m.Y');
                $dados['dataFinal'] = date('d.m.Y');
                $dados['regiao'] = $regiao;
                
                $dia = substr($dataFinal, 0,2);
                $mes = substr($dataFinal, 3,2);
                $ano = substr($dataFinal, 6,4);

                $dados['LojasVendas'] = $this->TotalvendaGeral_model->selecionaLojasVendasRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
                $dados['VendasDiaRegiao'] = $this->TotalvendaGeral_model->selecionaVendasDiaRegiao($regiao, 'alterardata', $dataInicio, $dataFinal, $dia, $mes, $ano);
                $dados['totalDiarioRegiao'] = $this->TotalvendaGeral_model->selecionaTotalDiarioRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
                
                $this->template->rander('relatorios/regiao', $dados);
            
            } elseif ($this->login_user->role_id == "9") {
               
                # Contabilidade

            } elseif ($this->login_user->role_id == "28") {
               
                # Convênios
            
            } elseif ($this->login_user->role_id == "6") {
                
                # Contas

            } elseif ($this->login_user->role_id == "3") {
                
                # Fiscal
            
            } elseif ($this->login_user->role_id == "10") {
                
                # Financeiro
            
            } else {

                echo('Acesso não autorizado.');

            }
        
    } //Fim do filtro

    public function inicio($cdloja){
        
        $this->load->model('Lojasprocedimento_model');
        $dados['lojaInformativo'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultLoja();
        $dados['graficoven'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultGrafico2($cdloja);
        $dados['graficovenOntem'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultGraficoOntem2($cdloja);
        $dados['info_clientes'] = $this->Lojasprocedimento_model->selecionaClientesCadastradosDir($cdloja);
       
        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $_SESSION['cdloja'] = $cdloja;
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal'] = $dataFinal;

        $this->load->model('Lojas_model');
        $dados['lojas'] = $this->Lojas_model->listarLojas();
        $dados['regiao'] = $this->Lojas_model->listarRegiao();

        $this->template->rander('relatorios/system_relatorio-ver', $dados);
    }

    public function regiao() {

        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal'] = $dataFinal;
        $regiao = $this->input->post('id');

        $this->load->model('TotalVenda_model');
        $this->load->model('TotalvendaMes_model');
        $this->load->model('TotalvendaGeral_model');

        $dados['regiaoDir'] = $this->TotalVenda_model->selecionaTotalVendaRegiaoDir($regiao);
        $dados['mesRegDir'] = $this->TotalvendaMes_model->selecionaTotalVendaMesRegiaoDir($regiao);
        $dados['geralRegDir'] = $this->TotalvendaGeral_model->selecionaTotalVendaGeralRegiaoDir($regiao);
        $dados['totalDiarioRegiaoDir'] = $this->TotalvendaGeral_model->selecionaTotalDiarioRegiaoDir($regiao, 'alterardata', $dataInicio, $dataFinal);
        
        $dados['dataInicio'] =  date('d.m.Y');
        $dados['dataFinal'] =  date('d.m.Y');
        $dados['regiao'] = $regiao;
        $this->template->rander('relatorios/regiaoDir', $dados);
    }

    // ---------------------------------- Colaboradores ---------------------------------- //

    public function colaborador() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal'] 	= $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela
        // Carrega o model e instância as difrentes classes.
        
        $this->load->model('Funcionarios_model', '', TRUE);
        $dados['funcionarios'] = $this->Funcionarios_model->selecionaLojaConexaoFuncionarios('alterardata', $dataInicio, $dataFinal,$_SESSION['cdloja']);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas Colaboradores / Período';
        $this->template->rander('relatorios/colaborador', $dados);
    }

    public function indicadores_colaborador() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal'] 	= $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }
      
        $this->load->model('Funcionarios_model', '', TRUE);
        $dados['indicadores_colaborador'] = $this->Funcionarios_model->selecionaIndicadoresColaborador('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' Indicadores por Colaborador ';
        $this->template->rander('relatorios/indicadores_colaborador', $dados);
    }

    public function per_capita() {

        $dados['per_capita'] = 'Per capita';
        $this->template->rander('relatorios/per_capita', $dados);
    }

    public function per_capitaInsert() {
        
        $dados = [
            'cdloja'=>$_SESSION['cdloja'],
            'ano'=>$this->input->get_post('valor1'),
            'mes'=>$this->input->get_post('valor2'),
            'nrfunc'=>$this->input->get_post('valor3')
        ];
        $mes = $this->input->get_post('valor2');
        $ano = $this->input->get_post('valor1');

        if (empty($mes) && empty($ano)) {
            $mes = date('m');
            $ano = date('Y');
        }
        
        $this->load->model('Funcionarios_model');
        $bool = $this->Funcionarios_model->insertPerCapita($dados,$_SESSION['cdloja'],$mes,$ano);
        echo json_encode($bool);
    }

    public function per_capitaSelect() {

        $mes = date('m');
        $ano = date('Y');

        $this->load->model('Funcionarios_model');
        $dados = $this->Funcionarios_model->selectPerCapita($_SESSION['cdloja'], $mes, $ano);
        echo json_encode($dados);
    }
    
    // ---------------------------------- Produtos ---------------------------------- //

    public function produtos() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela
        // Carrega o model e instância as difrentes classes.
        $this->load->model('Itemvenda_model');
        $dados['produtos'] = $this->Itemvenda_model->selecionaLojaConexaoProdutos('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' Produtos venda hoje';
        $this->template->rander('relatorios/produtos', $dados);

    }

    public function produtosEstoque() {

        $this->load->model('Estoquelojas_model');
        $dados['produtos_estoque'] = $this->Estoquelojas_model->selecionaLojaEstoqueProdutos();
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Produtos dispníveis na loja';
        $this->template->rander('relatorios/produtos_estoque', $dados);
    }

    // ---------------------------------- Vendas ---------------------------------- //

    public function vendas() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela

        $this->load->model('Totalvenda_model');
        $dados['vendas'] = $this->Totalvenda_model->selecionaVendasFP('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas Farmácia popular';
        $this->template->rander('relatorios/vendas_fp', $dados);
    }

    public function vendas_cupom() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y'); 

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela
        $this->load->model('Totalvenda_model');
        $dados['vendas'] = $this->Totalvenda_model->selecionaVendas_cupom('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - vendas por cupom';
        $this->template->rander('relatorios/vendas_cupom', $dados);
    }

    public function vendas_subgrupo() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela
        $this->load->model('Totalvenda_model');
        $dados['vendas'] = $this->Totalvenda_model->selecionaVendas_subgrupo('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas por SubGrupo';
        $this->template->rander('relatorios/vendas_subgrupo', $dados);
    }

    public function vendas_horario() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela
        $this->load->model('Totalvenda_model');
        $dados['vendas_horario'] = $this->Totalvenda_model->selecionaVendas_horario('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas por horário';
        $this->template->rander('relatorios/vendas_horario', $dados);
    }

    public function vendas_formasdepagamento() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $dados['vendas'] = $this->Totalvenda_model->selecionaVendasFormasdepagamento('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas por formas de pagamento';
        $this->template->rander('relatorios/vendas_formasdepagamento', $dados);

    }

    public function vendas_pbm() {

        //Carrega a página e as operações dela

        $this->load->model('Totalvenda_model');
        $dados['vendas_pbm'] = $this->Totalvenda_model->selecionaVendas_pbm();
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas PBM';
        $this->template->rander('relatorios/vendas_pbm', $dados);
    }

    public function vendasClientes() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $dados['vendas'] = $this->Totalvenda_model->selecionaVendasClientes('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas por Clientes';
        $this->template->rander('relatorios/vendasClientes', $dados);

    }

    // ---------------------------------- Estoque ---------------------------------- //

    public function ruptura() {

        $this->load->model('Estoquelojas_model');
        $dados['ruptura'] = $this->Estoquelojas_model->selecionaRuptura();
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Ruptura';
        $this->template->rander('relatorios/ruptura', $dados);
    }

    public function excesso() {

        $this->load->model('Estoquelojas_model');
        $dados['excesso'] = $this->Estoquelojas_model->selecionaExcesso();
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Excesso';
        $this->template->rander('relatorios/excesso', $dados);

    }

    public function estoque_trocasedevolucoes() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $STATUS = $this->input->get_post('status') ?? 'recebido';
        $dados['status'] = $STATUS;

        $this->load->model('Estoquelojas_model');
        $dados['trocasedevolucoes'] = $this->Estoquelojas_model->selecionaEstoque_trocasedevolucoes('alterardata', $dataInicio, $dataFinal, $CDLOJA = '', $STATUS);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Trocas e devoluções';
        $this->template->rander('relatorios/estoque_trocasedevolucoes', $dados);
    }

    public function notasfiscais_auditoria() {

        $CDLOJA = $this->session->userdata('cdlojaativa');
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Estoquelojas_model');
        $dados['notasfiscais_auditoria'] = $this->Estoquelojas_model->selecionaNotasfiscais_auditoria('alterardata', $dataInicio, $dataFinal, $CDLOJA = '');
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Notas fiscais x Auditoria';
        $this->template->rander('relatorios/notasfiscais_auditoria', $dados);
    }

    public function inventario_estoque() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Estoquelojas_model');
        $dados['inventario_estoque'] = $this->Estoquelojas_model->selecionaInventario_estoque('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Inventário de estoque';
        $this->template->rander('relatorios/inventario_estoque', $dados);
    }

    public function rmnr() {

        $this->load->model('Estoquelojas_model', '', TRUE);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Medicamentos Controlados (RMNR)';
        $this->template->rander('relatorios/rmnr', $dados);
    }

    public function rmnr_impressao() {

        $dataInicio = $this->input->POST('dataInicio');
        $dataIni = $dataInicio;

        $dados['dataIni'] = $dataIni;

        $dataFinal = $this->input->POST('dataFinal');
        $grupos = $_POST['grupos'];
        $lista_grupos = implode(",", $grupos);

        $cdloja = $this->session->userdata('cdloja');
        $this->load->model('Estoquelojas_model', '', TRUE);
        $this->load->model('Lojas_model', '', TRUE);

        $dados['LojasRel'] = $this->Lojas_model->selecionarLojaRel($cdloja);
        $dados['pagina'] = $this->session->userdata('endftp');
        $dados['pagina'] = $this->session->userdata('endereco');
        $dados['produtos'] = $this->Estoquelojas_model->selecionaRMNR($CDLOJA ='', $lista_grupos,$dataInicio,$dataFinal);

        $this->load->view('relatorios/rmnr_imprimir', $dados);
    }

    public function conferencia_notas() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dados['dataInicio'] = $dataInicio;            

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');            
            $dados['dataInicio'] = $dataInicio;            
        }
        
        $this->load->model('Estoquelojas_model', '', TRUE);
        $dados['conferencia'] = $this->Estoquelojas_model->selecionaConferencia_notas('alterardata', $dataInicio);
        $dados['conferencia 2'] = $this->Estoquelojas_model->selecionaConferencia_notas2('alterardata', $dataInicio);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Conferência de Notas';
        $this->template->rander('relatorios/conferencia_notas', $dados);
    }

    // ---------------------------------- Financeiro ---------------------------------- //

    public function recargas() {

        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        //Carrega a página e as operações dela

        $this->load->model('Totalvenda_model');
        $dados['recargas'] = $this->Totalvenda_model->selecionaRecargas('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Recargas';
        $this->template->rander('relatorios/recargas', $dados); 

    }

    function LojasVendas() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y'); 

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $dados['geralRegDir'] = $this->TotalvendaGeral_model->selecionaLojasVendas('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Lojas Vendas';
        $this->template->rander('relatorios/regiaoDir', $dados);
    }

    function LojasVendasSupervisao() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y'); 

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $dados['LojasVendas'] = $this->TotalvendaGeral_model->selecionaLojasVendasSupervisao('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Lojas Vendas';
        $this->template->rander('relatorios/supervisao', $dados);
    }

    function LojasVendasRegiao() {

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y'); 

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $dados['geralRegDir'] = $this->TotalvendaGeral_model->selecionaLojasVendasRegiao('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Lojas Vendas';
        $this->template->rander('relatorios/regiao', $dados);
    }

    public function TotalDiario() {
        
        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');

        $this->load->model('TotalvendaGeral_model');
        $dados['totalDiario'] = $this->TotalvendaGeral_model->selecionaTotalDiario('alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Totais diários';
        echo json_encode($dados['totalDiario']);
    } 

    function VendasDiaDir() {

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }

        $this->load->model('TotalvendaGeral_model');
        $dados['VendasDia'] = $this->TotalvendaGeral_model->selecionaVendasDiaDir($regiao);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas diárias Regiões';
        $this->template->rander('relatorios/regiaoDir', $dados);
    }

    function TotalDiarioDir() {

        $this->load->model('TotalvendaGeral_model');
        $dados['totalDiarioDir'] = $this->TotalvendaGeral_model->selecionaTotalDiarioDir();
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Totais diários';
        $this->template->rander('relatorios/regiaoDir', $dados);
    }

    function VendasDiaRegiao() {

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }

        if($this->input->POST('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->POST('dataInicio');
            $dataFinal = $this->input->POST('dataFinal');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y'); 

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $dados['VendasDiaRegiao'] = $this->TotalvendaGeral_model->selecionaVendasDiaRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Vendas diárias Regiões';
        $this->template->rander('relatorios/regiao', $dados);
    }

    function TotalDiarioRegiao() {

        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        $regiao = $this->input->get_post('regiao');

        $this->load->model('TotalvendaGeral_model');
        $dados['totalDiarioRegiao'] = $this->TotalvendaGeral_model->selecionaTotalDiarioRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Totais diários';
        echo json_encode($dados['totalDiarioRegiao']);
    }

    function TotalDiarioRegiaoDir() {

        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        $regiao = $this->input->get_post('regiao');

        $this->load->model('TotalvendaGeral_model');
        $dados['totalDiarioRegiaoDir'] = $this->TotalvendaGeral_model->selecionaTotalDiarioRegiaoDir($regiao, 'alterardata', $dataInicio, $dataFinal);
        $dados['pagina'] = $this->session->userdata('endftp') . ' - Totais diários';
        echo json_encode($dados['totalDiarioRegiaoDir']);
    }

    // ---------------------------------- list_data Região ---------------------------------- //

    function list_dataLojasVendas() { 

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }
       
        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaLojasVendas($regiao, 'alterardata',$dados['dataInicio'] , $dados['dataFinal']);
        
        $result = array();
        foreach ($list_data as $data) {
        $result[] = $this->_make_LojasVendas_row($data);
        }

            echo json_encode(array("data" => $result));
    }
    
    private function _make_LojasVendas_row($data) {

        return array(
            htmlentities($data->DESCRICAO), 
            htmlentities("R$ " .number_format($data->VLIQUIDO, 2,',','.')),
            htmlentities(number_format($data->PMETA, 2,',','.')." %"),
            htmlentities(number_format($data->FALTAATINGIR, 2,',','.')." %"),
            htmlentities(number_format($data->MDESCTOTAL, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCBALCAO, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCTOTAL, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->TICKETMEDIO, 2,',','.')),
            intval($data->NCLIENTES),
            htmlentities("R$ " .number_format($data->VGENERICOS, 2,',','.')),
            htmlentities(number_format($data->PGENERICOS, 2,',','.')." %"),
            htmlentities(number_format($data->LUCRATIVIDADE, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->VITAMINAS, 2,',','.')),
            htmlentities("R$ " .number_format($data->RCC, 2,',','.'))
        );
    }   

    function list_dataLojasVendasRegiao() { 

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }
       
        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje. 
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }
        
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaLojasVendasRegiao($regiao, 'alterardata',$dados['dataInicio'] , $dados['dataFinal']);
        $result = array();
        foreach ($list_data as $data) {
        $result[] = $this->_make_LojasVendasRegiao_row($data);
        }

            echo json_encode(array("data" => $result));
    }
    
    private function _make_LojasVendasRegiao_row($data) {

        return array(
            htmlentities($data->DESCRICAO), 
            htmlentities("R$ " .number_format($data->VLIQUIDO, 2,',','.')),
            htmlentities(number_format($data->PMETA, 2,',','.')." %"),
            htmlentities(number_format($data->FALTAATINGIR, 2,',','.')." %"),
            htmlentities(number_format($data->MDESCTOTAL, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCBALCAO, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCTOTAL, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->TICKETMEDIO, 2,',','.')),
            intval($data->NCLIENTES),
            htmlentities("R$ " .number_format($data->VGENERICOS, 2,',','.')),
            htmlentities(number_format($data->PGENERICOS, 2,',','.')." %"),
            htmlentities(number_format($data->LUCRATIVIDADE, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->VITAMINAS, 2,',','.')),
            htmlentities("R$ " .number_format($data->RCC, 2,',','.'))
        );
    }   

    function list_dataVendasDiaRegiao() {

        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        $regiao = $this->input->get_post('regiao');

        $dia = substr($dataFinal, 0,2);
        $mes = substr($dataFinal, 3,2);
        $ano = substr($dataFinal, 6,4);
        
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaVendasDiaRegiao($regiao, 'alterardata', $dataInicio, $dataFinal, $dia, $mes, $ano);
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasDiaRegiao_row($data);
            }
    
            echo json_encode(array("data" => $result));
        }
    
    private function _make_VendasDiaRegiao_row($data) {

        return array(

            htmlentities($data->descricao),
            htmlentities("R$ " .number_format($data->meta, 2,',','.')),
            htmlentities("R$ " .number_format($data->vliquido, 2,',','.')),
            htmlentities(number_format($data->pmeta, 2,',','.')." %"),
            htmlentities(number_format($data->mdesconto, 2,',','.')." %"),
            htmlentities(number_format($data->pdesconto, 2,',','.')." %"),
            intval($data->clientes),
            htmlentities("R$ " .number_format($data->ticketm, 2,',','.'))
        );
    }   

    function list_dataDadosGenericosRegiao() { 

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosGenericosRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
        $result[] = $this->_make_DadosGenericosRegiao_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_DadosGenericosRegiao_row($data) {

        return array(

            htmlentities($data->genericos),
            htmlentities(number_format($data->pgenericos, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->liquido, 2,',','.')),
        );
    }

    function list_dataDadosVitaminasRegiao() { 

        if($this->input->get_post('regiao')){
            $regiao = $this->input->get_post('regiao');
        }

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosVitaminasRegiao($regiao, 'alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
            $result[] = $this->_make_DadosVitaminasRegiao_row($data);
            }
            echo json_encode(array("data" => $result));
        }

    private function _make_DadosVitaminasRegiao_row($data) {

        return array(

            htmlentities($data->vitaminas),
            htmlentities($data->uni),
            htmlentities("R$" .number_format($data->total, 2,',','.')),
        );
    }

    // ---------------------------------- list_data Região Dir ---------------------------------- //

     function list_dataVendasDiaRegiaoDir() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');

            $dia = substr($dataFinal, 0,2);
            $mes = substr($dataFinal, 3,2);
            $ano = substr($dataFinal, 6,4);
            
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje. 
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;

            $dia = substr($dataFinal, 0,2);
            $mes = substr($dataFinal, 3,2);
            $ano = substr($dataFinal, 6,4);       
        }
        
        $regiao = $this->input->get_post('regiao');
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaVendasDiaRegiaoDir($regiao, 'alterardata', $dataInicio, $dataFinal ,$dia, $mes, $ano);
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasDiaRegiaoDir_row($data);
            }
            echo json_encode(array("data" => $result));
        }

    private function _make_VendasDiaRegiaoDir_row($data) {

        return array(

            htmlentities($data->descricao),
            htmlentities("R$ " .number_format($data->meta, 2,',','.')),
            htmlentities("R$ " .number_format($data->vliquido, 2,',','.')),
            htmlentities(number_format($data->pmeta, 2,',','.')." %"),
            htmlentities(number_format($data->mdesconto, 2,',','.')." %"),
            htmlentities(number_format($data->pdesconto, 2,',','.')." %"),
            intval($data->clientes),
            htmlentities("R$ " .number_format($data->ticketm, 2,',','.'))
        );
    }   

    function list_dataDadosGenericosRegiaoDir() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $regiao = $this->input->get_post('regiao');
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosGenericosRegiaoDir($regiao, 'alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
        $result[] = $this->_make_DadosGenericosRegiaoDir_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_DadosGenericosRegiaoDir_row($data) {

        return array(

            htmlentities($data->genericos),
            htmlentities(number_format($data->pgenericos, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->liquido, 2,',','.')),
        );
    }

    function list_dataDadosVitaminasRegiaoDir() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }
        
        $regiao = $this->input->get_post('regiao');
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosVitaminasRegiaoDir($regiao, 'alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
            $result[] = $this->_make_DadosVitaminasRegiaoDir_row($data);
            }
            echo json_encode(array("data" => $result));
        }

    private function _make_DadosVitaminasRegiaoDir_row($data) {

        return array(

            htmlentities($data->vitaminas),
            htmlentities($data->uni),
            htmlentities("R$ " .number_format($data->total, 2,',','.')),
        );
    }

    // ---------------------------------- list_data Supervisão ---------------------------------- //
    
    function list_dataLojasVendasSupervisao() { 
       
        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }
        
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaLojasVendasSupervisao('alterardata', $dataInicio, $dataFinal);
        $result = array();

        foreach ($list_data as $data) {
        $result[] = $this->_make_LojasVendasSupervisao_row($data);
        }

        echo json_encode(array("data" => $result));
    }
    
    private function _make_LojasVendasSupervisao_row($data) {

        return array(
            htmlentities($data->DESCRICAO), 
            htmlentities("R$ " .number_format($data->VLIQUIDO, 2,',','.')),
            htmlentities(number_format($data->PMETA, 2,',','.')." %"),
            htmlentities(number_format($data->FALTAATINGIR, 2,',','.')." %"),
            htmlentities(number_format($data->MDESCTOTAL, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCBALCAO, 2,',','.')." %"),
            htmlentities(number_format($data->PDESCTOTAL, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->TICKETMEDIO, 2,',','.')),
            intval($data->NCLIENTES),
            htmlentities("R$ " .number_format($data->VGENERICOS, 2,',','.')),
            htmlentities(number_format($data->PGENERICOS, 2,',','.')." %"),
            htmlentities(number_format($data->LUCRATIVIDADE, 2,',','.')." %"),
            htmlentities("R$ " .number_format($data->VITAMINAS, 2,',','.')),
            htmlentities("R$ " .number_format($data->RCC, 2,',','.'))
        );
    }   

    public function list_dataVendasDiarias() { 

        $dataInicio = $this->input->get('dataInicio');
        $dataFinal = $this->input->get('dataFinal');
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal;

        if(empty($dataInicio) && empty($dataFinal)){

            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }
        
        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaVendasDiarias('alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasDiarias_row($data);
            }
    
            echo json_encode(array("data" => $result));
        }

    private function _make_VendasDiarias_row($data) {

        return array(

            htmlentities($data->descricao),
            htmlentities("R$" .number_format($data->vliquido, 2,',','.')),
            htmlentities("R$" .number_format($data->vbruto, 2,',','.')),
            htmlentities(number_format($data->pdesconto, 2,',','.')."%"),
            intval($data->clientes),
            htmlentities("R$" .number_format($data->ticketm, 2,',','.'))
        );
    }

    function list_dataDadosGenericos() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosGenericos('alterardata', $dataInicio, $dataFinal);
        $totalDiario = $this->TotalvendaGeral_model->selecionaTotalDiario('alterardata', $dataInicio, $dataFinal);
        $result = array();            

        $soma1 = 0;
        $soma2 = 0;

        foreach ($totalDiario as $diario) {
            $total = $diario->liquido;
        }
        
        foreach ($list_data as $data) {            
                
            $soma1 = $soma1 + $data->liquido;
            $soma2 = number_format($soma1*100/$total, 2,',','.');                        
            $result[] = $this->_make_DadosGenericos_row($data);
        }
            echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2));
        
    }

    private function _make_DadosGenericos_row($data) {

        return array(

            htmlentities($data->genericos),
            htmlentities(number_format($data->pgenericos, 2,',','.')."%"),
            htmlentities("R$" .number_format($data->liquido, 2,',','.')),
        );
    }

    function list_dataDadosVitaminas() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('TotalvendaGeral_model');
        $list_data = $this->TotalvendaGeral_model->selecionaDadosVitaminas('alterardata', $dataInicio, $dataFinal);
        $result = array();

        $soma1 = 0;
        $soma2 = 0;

        foreach ($list_data as $data) {

            $soma1 = $soma1 + $data->uni;
            $soma2 = $soma2 + $data->total;    
            $result[] = $this->_make_DadosVitaminas_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2));
    }

    private function _make_DadosVitaminas_row($data) {

        return array(

            htmlentities($data->vitaminas),
            htmlentities($data->uni),
            htmlentities("R$" .number_format($data->total, 2,',','.')),
        );
    }

    // ---------------------------------- list_data Financeiro ---------------------------------- //

    function list_dataRecargas() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
           
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaRecargas('alterardata', $dataInicio, $dataFinal);
        $result = array();
 
        foreach ($list_data as $data) {
        $result[] = $this->_make_recargas_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_recargas_row($data) {

        return array(
            date('d/m/Y', strtotime($data->CAST)), 
            nl2br($data = "R$ " .number_format($data->ADD, 2,',','.')),
        );
    }

    // ---------------------------------- list_data Estoque ---------------------------------- //

    function list_dataExcessoSubGrupo() {

        $result = array();
        $subgrupo = $this->input->get_post('init');
        if($this->input->get_post('subgrupo')!=NULL){
            $subgrupo = $this->input->get_post('subgrupo');
        }
        if($subgrupo != NULL){

            $conjunto = ['MEDICAMENTOS'=>1,'PERFUMARIA'=>2,'ACESSORIOS'=>3,'PBM'=>4,'FRALDAS'=>5,'GENERICO'=>9,'NACIONAL'=>10,'OFICINAIS'=>12,'LEITES'=>16,
            'BEBIDAS'=>21,'GENERICO ONEROSO'=>26,'ANTICONCEPCIONAIS'=>27,'MEDICAMENTO OTC'=>30,'ALTA COMPLEXIDADE'=>34,'POMBAL'=>35,'MEDICAMENTOS ONEROSOS'=>38,
            'DERMO COSMETICOS'=>40,'MEDICAMENTO CONTROLADO'=>41,'GENERICO CONTROLADO'=>42,'GENERICOS EXCLUSIVOS'=>43,'VITAMINAS'=>46,'DIABETES MARKET'=>49,'CONVENIENCIA'=>51
            ];

            $this->load->model('Estoquelojas_model');
            $list_data = $this->Estoquelojas_model->selecionaExcessoSubGrupo($conjunto[$subgrupo]);
            foreach ($list_data as $data) {
                $result[] = $this->_make_excesso_subgrupo_row($data);
            }
        }
        
        echo json_encode(array("data" => $result));
    }

    private function _make_excesso_subgrupo_row($data) {

        return array(
            htmlentities($data->TPPEDIDO),
            htmlentities($data->TIPOMIX),
            htmlentities($data->CODIGO),
            htmlentities($data->PRODUTO),
            htmlentities($data->ESTOQUE),
            htmlentities($data->GIRO),
            htmlentities($data->GIRO90D),
            htmlentities($data->MEDVENDA),
            htmlentities($data->ESTSEG),
            htmlentities($data->EXCESSOPELOMIX),
            htmlentities($data->EXCESSOPELAMEDIAVENDA),
            htmlentities("R$ ".number_format($data->PMC, 2,',','.')),
            htmlentities("R$ ".number_format($data->TOTALPMCPELOMIX, 2,',','.')),
            htmlentities("R$ ".number_format($data->TOTALPMCPELAMEDIAVENDA, 2,',','.')),
        );
    }   
    
    function list_dataExcesso() {

    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaExcesso();
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_excesso_row($data);
    }

    echo json_encode(array("data" => $result));
    }

    private function _make_excesso_row($data) {

        return array(
            
            $data->CDSUBGRUPO,
            $data->DESCRICAO,
            nl2br($data = "R$ " .number_format($data->TOTALPMCEXCESSOMIX, 2,',','.')),
        );
    }   

    function list_dataRupturaSubGrupo() {

        $result = array();
        $subgrupo = $this->input->get_post('init');
        
        if($this->input->get_post('subgrupo')!=NULL) {
            $subgrupo = $this->input->get_post('subgrupo');
        }

        if($subgrupo != NULL){

            $conjunto = ['MEDICAMENTOS'=>1,'PERFUMARIA'=>2,'ACESSORIOS'=>3,'PBM'=>4,'FRALDAS'=>5,'GENERICO'=>9,'NACIONAL'=>10,'OFICINAIS'=>12,'LEITES'=>16,
            'BEBIDAS'=>21,'GENERICO ONEROSO'=>26,'ANTICONCEPCIONAIS'=>27,'MEDICAMENTO OTC'=>30,'ALTA COMPLEXIDADE'=>34,'POMBAL'=>35,'MEDICAMENTOS ONEROSOS'=>38,
            'DERMO COSMETICOS'=>40,'MEDICAMENTO CONTROLADO'=>41,'GENERICO CONTROLADO'=>42,'GENERICOS EXCLUSIVOS'=>43,'VITAMINAS'=>46,'DIABETES MARKET'=>49,'CONVENIENCIA'=>51
            ];

            $this->load->model('Estoquelojas_model');
            $list_data = $this->Estoquelojas_model->selecionaRupturaSubGrupo($conjunto[$subgrupo]);
        

        foreach ($list_data as $data) {
            $result[] = $this->_make_ruptura_subgrupo_row($data);
            }
            echo json_encode(array("data" => $result));
        }
    }


    private function _make_ruptura_subgrupo_row($data) {

        return array(
            htmlentities($data->TPPEDIDO),
            htmlentities($data->TIPOMIX),
            htmlentities($data->CODIGO),
            htmlentities($data->PRODUTO),
            htmlentities($data->ESTOQUE),
            htmlentities($data->GIRO),
            htmlentities($data->GIRO90D),
            htmlentities($data->MEDVENDA),
            htmlentities($data->ESTSEG),
            htmlentities($data->RUPTURA),
            nl2br("R$ ".number_format($data->PMC, 2,',','.')),
            htmlentities("R$ ".number_format($data->TOTALPMC, 2,',','.')),
        );
    }   

    function list_dataRuptura() {
    
    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaRuptura();
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_ruptura_row($data);
    }
    echo json_encode(array("data" => $result));
    }

    private function _make_ruptura_row($data) {

        return array(

            $data->CDSUBGRUPO,
            htmlentities($data->DESCRICAO),
            nl2br($data = "R$ " .number_format($data->TOTALPMC, 2,',','.')),
        );
    }

    function list_dataTrocasDevolucoes() {
            
    //Carrega a data de hoje.
    //Se enviado o valor da data pela URL, remove o POST.
    if($this->input->get_post('dataInicio')){

        //Carrega os valores passado por GET.
        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal; 

    } else {

        //Carrega a data de hoje.
        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal;
    }

    $STATUS = $this->input->get_post('status') ?? 'recebido';

    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaEstoque_trocasedevolucoes('alterardata', $dataInicio, $dataFinal, '', $STATUS);
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_TrocasDevolucoes_row($data);
    }
    echo json_encode(array("data" => $result));
    }
    private function _make_TrocasDevolucoes_row($data) {

    return array(

        date('d/m/Y', strtotime($data->CAST)),
        htmlentities($data->HORA),
        htmlentities($data->NRTROCA),
        htmlentities($data->NOME),
        htmlentities($data->CPF),
        htmlentities($data->FONE),
        htmlentities($data->FUNCTROCA),
        htmlentities($data->FUNCVENDA),
        htmlentities($data->CUPOMORIGEM),
        nl2br("R$ ".number_format($data->VLRTOTALITEM, 2,',','.')),
        htmlentities($data->STATUS),
    );
    }  
    
    function list_dataConferencia_notas() {
        
    //Carrega a data de hoje.
    //Se enviado o valor da data pela URL, remove o POST.
    if($this->input->get_post('dataInicio')){

        //Carrega os valores passado por GET.
        $dataInicio = $this->input->get_post('dataInicio');                
        $dados['dataInicio'] = $dataInicio;        

    } else {

        //Carrega a data de hoje.
        $dataInicio = date('d.m.Y');        
        $dados['dataInicio'] = $dataInicio;        
    }

    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaConferencia_notas('alterardata', $dataInicio);
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_conferencia_notas_row($data);
    }
    echo json_encode(array("data" => $result));
    }

    private function _make_conferencia_notas_row($data) {

        return array(
                
            intval($data->CODIGO),
            htmlentities($data->PRODUTO),
            intval($data->AUDITADOS),
            intval($data->NFS),
            intval($data->DIFERENCA)
        );
    }

    function list_dataConferencia_notas2() {
        
    //Carrega a data de hoje.
    //Se enviado o valor da data pela URL, remove o POST.
    if($this->input->get_post('dataInicio')){

        //Carrega os valores passado por GET.
        $dataInicio = $this->input->get_post('dataInicio');            
        $dados['dataInicio'] = $dataInicio;         

    } else {

        //Carrega a data de hoje.
        $dataInicio = date('d.m.Y');
        $dados['dataInicio'] = $dataInicio;

    }

        $this->load->model('Estoquelojas_model');
        $list_data = $this->Estoquelojas_model->selecionaConferencia_notas2('alterardata', $dataInicio);
        $result = array();
    
        foreach ($list_data as $data) {
        $result[] = $this->_make_conferencia_notas2_row($data);
        }
        echo json_encode(array("data" => $result));
        }
    
        private function _make_conferencia_notas2_row($data) {
    
            return array(
                    
                intval($data->CODIGO),
                htmlentities($data->PRODUTO),
                intval($data->AUDITADOS),
                intval($data->NFS),
                intval($data->NRNOTA),
                htmlentities($data->FORNECEDOR),
                intval($data->DIFERENCA)
            );
        }
    
    function list_dataNotasfiscais_auditoria() {
            
    //Carrega a data de hoje.
    //Se enviado o valor da data pela URL, remove o POST.
    if($this->input->get_post('dataInicio')){

        //Carrega os valores passado por GET.
        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal; 

    } else {

        //Carrega a data de hoje.
        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal;
    }

    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaNotasfiscais_auditoria('alterardata', $dataInicio, $dataFinal, $CDLOJA = '');
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_NotasfiscaisAuditoria_row($data);
    }
    echo json_encode(array("data" => $result));
    }

    private function _make_NotasfiscaisAuditoria_row($data) {

    return array(

    //    date('d/m/Y', strtotime($data->CAST)),
    //    $data->HORA,
    //    $data->NRTROCA,
    //    $data->NOME,
    //    $data->CPF,
    //    $data->FONE,
    //    $data->FUNCTROCA,
    //    $data->FUNCVENDA,
    //    $data->CUPOMORIGEM,
    //    nl2br("R$ ".number_format($data->VLRTOTALITEM, 2,',','.')),
    //    $data->STATUS,
    );
}   

function list_dataInventario_estoque() {
            
    //Carrega a data de hoje.
    //Se enviado o valor da data pela URL, remove o POST.
    if($this->input->get_post('dataInicio')){

        //Carrega os valores passado por GET.
        $dataInicio = $this->input->get_post('dataInicio');
        $dataFinal = $this->input->get_post('dataFinal');
        
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal; 

    } else {

        //Carrega a data de hoje.
        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $dados['dataInicio'] = $dataInicio;
        $dados['dataFinal']  = $dataFinal;
    }

    $this->load->model('Estoquelojas_model');
    $list_data = $this->Estoquelojas_model->selecionaInventario_estoque('alterardata', $dataInicio, $dataFinal, $CDLOJA = '');
    $result = array();

    foreach ($list_data as $data) {
    $result[] = $this->_make_Inventario_estoque_row($data);
    }
    echo json_encode(array("data" => $result));
    }
    private function _make_Inventario_estoque_row($data) {

    return array(

    //    date('d/m/Y', strtotime($data->CAST)),
    //    $data->HORA,
    //    $data->NRTROCA,
    //    $data->NOME,
    //    $data->CPF,
    //    $data->FONE,
    //    $data->FUNCTROCA,
    //    $data->FUNCVENDA,
    //    $data->CUPOMORIGEM,
    //    nl2br("R$ ".number_format($data->VLRTOTALITEM, 2,',','.')),
    //    $data->STATUS,
    );
}   

    // ---------------------------------- list_data Vendas ---------------------------------- //

    function list_dataVendasFP() {

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendasFP('alterardata', $dataInicio, $dataFinal);
        $result = array();

        $soma1 = 0;
        $soma2 = 0;
        $soma3 = 0;
        $soma4 = 0;

        foreach ($list_data as $data) {

            $soma1 = $soma1 + $data->QTDE;
            $soma2 = $soma2 + $data->TOTAL;
            $soma3 = $soma3 + $data->PAGTOMS;
            $soma4 = $soma4 + $data->PAGTOCLI;
            $result[] = $this->_make_VendasFP_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2,  "soma3" => $soma3, "soma4" => $soma4));
    }

    private function _make_VendasFP_row($data) {

        return array(

            date('d/m/Y', strtotime($data->CAST)),
            htmlentities($data->NFCE),
            htmlentities($data->S),
            htmlentities($data->VENDEDOR),
            htmlentities($data->PRODUTO),
            intval($data->QTDE), 
            htmlentities("R$ " .number_format($data->TOTAL, 2,',','.')),
            htmlentities("R$ " .number_format($data->PAGTOMS, 2,',','.')),
            htmlentities("R$ " .number_format($data->PAGTOCLI, 2,',','.')),
            htmlentities($data->AUTORIZACAO)
        );
    }   

    function list_dataVendasCupom() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendas_cupom('alterardata', $dataInicio, $dataFinal);
        $result = array();

        $soma1 = 0;
        $soma2 = 0;
        $soma3 = 0;
        $soma4 = 0;

        foreach ($list_data as $data) {

            $soma1 = $soma1 + $data->QUANTIDADE;
            $soma2 = $soma2 + $data->VLRUNITARIO;
            $soma3 = $soma3 + $data->VLRDESCONTO;
            $soma4 = $soma4 + $data->VLRTOTALITEM;
            $result[] = $this->_make_VendasCupom_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2,  "soma3" => $soma3, "soma4" => $soma4));
    }

    private function _make_VendasCupom_row($data) {

        return array(
            $data->NRCUPOM, 
            htmlentities($data->DESCRICAO), 
            intval($data->QUANTIDADE), 
            nl2br("R$ ".number_format($data->VLRUNITARIO, 2,',','.')),
            nl2br("R$ ".number_format($data->VLRDESCONTO, 2,',','.')),
            nl2br("R$ ".number_format($data->VLRTOTALITEM, 2,',','.')),
        );
    }   

    function list_dataVendasHorario() { 

        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendas_horario('alterardata', $dataInicio, $dataFinal);
        $result = array();

        $soma1 = 0;
        $soma2 = 0;
        $soma3 = 0;
        $soma4 = 0;

        foreach ($list_data as $data) {

            $soma1 = $soma1 + $data->QTDEVENDAS;
            $soma2 = $soma2 + $data->VLRSUBTOTAL;
            $soma3 = $soma3 + $data->VLRDESCONTO;
            $soma4 = $soma4 + $data->TOTAL;
            $result[] = $this->_make_VendasHorario_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2,  "soma3" => $soma3, "soma4" => $soma4));
    }

    private function _make_VendasHorario_row($data) {

        return array(
            $data->PERIODO, 
            nl2br($data->QTDEVENDAS), 
            nl2br("R$ ".number_format($data->VLRSUBTOTAL, 2,',','.')),
            nl2br("R$ ".number_format($data->VLRDESCONTO, 2,',','.')),
            nl2br("R$ ".number_format($data->TOTAL, 2,',','.')),
            nl2br(number_format($data->PARTIC, 2,',','.')." %"),
        );
    }   

    function list_dataVendasSubGrupo() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendas_subgrupo('alterardata', $dataInicio, $dataFinal);
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasSubGrupo_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_VendasSubGrupo_row($data) {

        return array(
            $data->CATEGORIA, 
            nl2br(number_format($data->PARTIC, 2,',','.')." %"),
            nl2br("R$ ".number_format($data->VENDALIQ, 2,',','.')),
            nl2br(number_format($data->DESCPERCENTUAL, 2,',','.')." %"),
            nl2br("R$ ".number_format($data->VENDABRUTA, 2,',','.')),
        );
    }   

    function list_dataVendasFormasdePagamento() { 

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendasFormasdepagamento();
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasFormasdePagamento_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_VendasFormasdePagamento_row($data) {

        return array(
            $data->DESCRICAO, 
            nl2br("R$ ".number_format($data->TOTAL, 2,',','.')), 
        );
    }   

    function list_dataVendasFormasdePagamento2() { 

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendasFormasdepagamento2('alterardata');
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasFormasdePagamento2_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_VendasFormasdePagamento2_row($data) {

        return array(
            $data->DESCRICAO, 
            nl2br("R$ ".number_format($data->TOTAL, 2,',','.')), 
        );
    }   

    function list_dataVendasPBM() { 

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendas_pbm();
       
        $result = array();
        
        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasPBM_row($data);
        }
        echo json_encode(array("data" => $result));
    }
 
    private function _make_VendasPBM_row($data) {

        return array(
            htmlentities($data->PROGRAMA), 
            number_format($data->PARTIC, 2,',','.')." %",
            intval(number_format($data->QTDE, 2)), 
            "R$ ".number_format($data->VENDA, 2,',','.'), 
            "R$ ".number_format($data->SUBSIDIO, 2,',','.'), 
        );
    } 
    
    function list_dataVendasPBM2() { 

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendas_pbm2();
       
        $result = array();
        
        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasPBM2_row($data);
        }
            echo json_encode(array("data" => $result));
    }

    private function _make_VendasPBM2_row($data) {

        return array(
            htmlentities($data->PROGRAMA),
            number_format($data->PARTIC, 2,',','.')." %",
            intval(number_format($data->QTDE, 2)),
            "R$ ".number_format($data->VENDA, 2,',','.'),
            "R$ ".number_format($data->SUBSIDIO, 2,',','.'), 
        );
    }

    function list_dataVendasClientes() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Totalvenda_model');
        $list_data = $this->Totalvenda_model->selecionaVendasClientes('alterardata', $dataInicio, $dataFinal);
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_VendasClientes_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_VendasClientes_row($data) {

        return array(            
            date('d/m/Y', strtotime($data->data)), 
            htmlentities($data->nome), 
            $data->fone, 
            intval($data->cdproduto), 
            $data->descricao,             
            htmlentities("R$ ".number_format($data->vlrunitario, 2,',','.')),
            intval($data->quantidade), 
            htmlentities("R$ ".number_format($data->vlrtotal, 2,',','.')),
            htmlentities(number_format($data->percdesc, 2,',','.')."%"),              
        );
    }   

    // ---------------------------------- list_data Produtos ---------------------------------- //

    function list_dataProdutos() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Itemvenda_model');
        $list_data = $this->Itemvenda_model->selecionaLojaConexaoProdutos('alterardata', $dataInicio, $dataFinal);
        $result = array();

        $soma1 = 0;
        $soma2 = 0;

        foreach ($list_data as $data) {
            $soma1 = $soma1 + $data->QUANTIDADE;
            $soma2 = $soma2 + $data->TOTAL;
            
            $result[] = $this->_make_produtos_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2));
    }

    private function _make_produtos_row($data) {

        return array(
            htmlentities($data->DESCRICAO), 
            intval(number_format($data->QUANTIDADE, 2,',','.')), 
            htmlentities("R$ ".number_format($data->TOTAL, 2,',','.')),
        );
    }   

    function list_dataProdutosEstoque() { 
    
        $this->load->model('Estoquelojas_model');
        $list_data = $this->Estoquelojas_model->selecionaLojaEstoqueProdutos();
        $result = array();

        $soma1 = 0;
        $soma2 = 0;
        $soma3 = 0;

        foreach ($list_data as $data) {
            $soma1 = $soma1 + $data->VLRSUGERIDO;
            $soma2 = $soma2 + $data->VLRCUSTOCIMP;
            $soma3 = $soma3 + $data->TOTALESTOQUE;
            $result[] = $this->_make_produtosEstoque_row($data);
        }
        echo json_encode(array("data" => $result, "soma1" => $soma1, "soma2" => $soma2, "soma3" => $soma3 ));
    }

    private function _make_produtosEstoque_row($data) {

        return array(
            nl2br($data->CDPRODUTO),
            htmlentities($data->PRODUTO),
            intval(number_format($data->QUANTIDADE, 2,',','.')),
            $data->GIRO, 
            $data->GIRO90D,
            $data->ESTSEG,
            nl2br("R$ ".number_format($data->VLRSUGERIDO, 2,',','.')), 
            nl2br("R$ ".number_format($data->VLRCUSTOCIMP, 2,',','.')),
            nl2br("R$ ".number_format($data->TOTALESTOQUE, 2,',','.')), 
        );
    }   

    function list_dataProdutosFalta() { 

        $this->load->model('Estoquelojas_model');
        $list_data = $this->Estoquelojas_model->selecionaLojaProdutosFalta();
       
        $result = array();

        foreach ($list_data as $data) {
            $result[] = $this->_make_falta_row($data);
        }
            echo json_encode(array("data" => $result));
         
    }

    private function _make_falta_row($data) {

        return array(
            $data->CDPRODUTO, 
            htmlentities($data->DESCRICAO),
            $data->QUANTIDADE, 
            $data->ESTMINIMO,
            $data->ESTMAXIMO 
        );
    }   

    function list_dataProdutosFalta2() { 

        $this->load->model('Estoquelojas_model');
        $list_data = $this->Estoquelojas_model->selecionaLojaProdutosMaximo();
        $result = array();

        foreach ($list_data as $data) {
        $result[] = $this->_make_maximo_row($data);
        }
       
        echo json_encode(array("data" => $result));
    }

    private function _make_maximo_row($data) {

        return array(
            $data->CDPRODUTO, 
            $data->DESCRICAO,
            $data->QUANTIDADE, 
            $data->ESTMINIMO,
            $data->ESTMAXIMO 
        );
    } 

    // ---------------------------------- list_data Colaboradores ---------------------------------- // 

    function list_dataColaborador() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Funcionarios_model');
        $list_data = $this->Funcionarios_model->selecionaLojaConexaoFuncionarios('alterardata', $dataInicio, $dataFinal);
        $result = array();

        foreach ($list_data as $data) {
        $result[] = $this->_make_colaborador2_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_colaborador2_row($data) {

        return array(
            $data->COD, 
            $data->NOME,
            htmlentities(number_format($data->PART, 2,',','.')."%"), 
            nl2br("R$ ".number_format($data->TICKETMED, 2,',','.')), 
            intval($data->NRCLIENTES),
            nl2br("R$ ".number_format($data->BRUTO, 2,',','.')), 
            nl2br("R$ ".number_format($data->LIQUIDO, 2,',','.')), 
            htmlentities(number_format($data->DESCTO, 2,',','.')."%"), 
            htmlentities(number_format($data->CONCED, 2,',','.')."%"), 
            htmlentities(number_format($data->PROMO, 2,',','.')."%"), 
            htmlentities(number_format($data->PBM, 2,',','.')."%"),
            htmlentities(number_format($data->CONV, 2,',','.')."%"),
        );
    }   

    function list_dataIndicadoresColaborador() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
        
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {

            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Funcionarios_model');
        $list_data = $this->Funcionarios_model->selecionaIndicadoresColaborador('alterardata', $dataInicio, $dataFinal);
        $result = array();

        foreach ($list_data as $data) {
        $result[] = $this->_make_IndicadoresColaborador_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_IndicadoresColaborador_row($data) {

        return array(
            $data->COD, 
            $data->NOME, 
            nl2br("R$".number_format($data->MVENDA, 2,',','.')), 
            nl2br("R$".number_format($data->VENDA, 2,',','.')), 
            nl2br("R$".number_format($data->DVEN, 2,',','.')), 
            nl2br("R$".number_format($data->MGEN, 2,',','.')), 
            nl2br("R$".number_format($data->VGEN, 2,',','.')),
            nl2br("R$".number_format($data->DGEN, 2,',','.')), 
            htmlentities(number_format($data->MDESC, 2,',','.')."%"),
            htmlentities(number_format($data->DESC, 2,',','.')."%"),
            htmlentities(number_format($data->DDESC, 2,',','.')."%"),
            intval($data->MVITAS),
            intval($data->VVITAS),
            intval($data->DVITAS),
            nl2br("R$".number_format($data->VENDAVITAS, 2,',','.')),
            nl2br("R$".number_format($data->TKMVITAS, 2,',','.')),
            nl2br("R$".number_format($data->MTKM, 2,',','.')),
            nl2br("R$".number_format($data->TKM, 2,',','.')), 
            nl2br("R$".number_format($data->DTKM, 2,',','.')), 
        );
    }   

    function list_dataCatagoriasColaborador() { 

        //Carrega a data de hoje.
        //Se enviado o valor da data pela URL, remove o POST.
        if($this->input->get_post('dataInicio')){

            //Carrega os valores passado por GET.
            $dataInicio = $this->input->get_post('dataInicio');
            $dataFinal = $this->input->get_post('dataFinal');
            $nome = $this->input->get_post('nome');

            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal; 

        } else {
            
            //Carrega a data de hoje.
            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');
            $dados['dataInicio'] = $dataInicio;
            $dados['dataFinal']  = $dataFinal;
        }

        $this->load->model('Funcionarios_model');
        $list_data = $this->Funcionarios_model->selecionaCategoriasColaborador('alterardata', $dataInicio, $dataFinal, $nome);
        $result = array();

        foreach ($list_data as $data) {
        $result[] = $this->_make_CategoriasColaborador_row($data);
        }
        echo json_encode(array("data" => $result));
    }

    private function _make_CategoriasColaborador_row($data) {

        return array(
            $data->SUBSTRING, 
            $data->CATEGORIA, 
            htmlentities(number_format($data->PPARTVENDA, 2,',','.')."%"),
            nl2br("R$".number_format($data->VLRVENDALIQ, 2,',','.')), 
            nl2br("R$".number_format($data->VLRVENDABRUTA, 2,',','.')), 
            htmlentities(number_format($data->PDESCONTOT, 2,',','.')."%"),
            htmlentities(number_format($data->PDESCBALCAO, 2,',','.')."%"),
            htmlentities(number_format($data->PDESCPROMO, 2,',','.')."%"),
            htmlentities(number_format($data->PDESCCONV, 2,',','.')."%"),
            intval($data->NRITENS),
        );
    }
}