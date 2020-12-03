<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model("Custom_widgets_model");
    }

    public function index() {
        $widgets = $this->_check_widgets_permissions();

        $view_data["dashboards"] = array();

        $dashboards = $this->Dashboards_model->get_details(array("user_id" => $this->login_user->id));

        if ($dashboards) {
            $view_data["dashboards"] = $dashboards->result();
        }

        $view_data["dashboard_type"] = "default";

        if ($this->login_user->user_type === "staff") {
            $view_data["show_timeline"] = get_array_value($widgets, "new_posts");
            $view_data["show_timeline"] = get_array_value($widgets, "metas_diarias");
            $view_data["show_timeline"] = get_array_value($widgets, "metas_tkm");
            $view_data["show_timeline"] = get_array_value($widgets, "metas_vitaminas");
            $view_data["show_timeline"] = get_array_value($widgets, "metas_desconto");
            $view_data["show_attendance"] = get_array_value($widgets, "clock_in_out");
            $view_data["show_event"] = get_array_value($widgets, "events_today");
            $view_data["show_project_timesheet"] = get_array_value($widgets, "my_timesheet_statistics");
            $view_data["show_income_vs_expenses"] = get_array_value($widgets, "income_vs_expenses");
            $view_data["show_invoice_statistics"] = get_array_value($widgets, "invoice_statistics");
            $view_data["show_ticket_status"] = get_array_value($widgets, "ticket_status");
            $view_data["show_clock_status"] = get_array_value($widgets, "clock_status");
            $view_data["show_clock_status"] = get_array_value($widgets, "dashboardTI");
            
            $this->template->rander("dashboards/index", $view_data);

        } else {
            
            $view_data['show_invoice_info'] = get_array_value($widgets, "show_invoice_info");
            $view_data['hidden_menu'] = get_array_value($widgets, "hidden_menu");
            $view_data['client_info'] = get_array_value($widgets, "client_info");
            $view_data['client_id'] = get_array_value($widgets, "client_id");
            $view_data['page_type'] = get_array_value($widgets, "page_type");
            
            $view_data["custom_field_headers"] = $this->Custom_fields_model->get_custom_field_headers_for_table("projects", $this->login_user->is_admin, $this->login_user->user_type);
           
            $this->load->model('Lojasprocedimento_model');
            $this->load->model('Metas_model');
            $this->load->model('Lojas_model');
            $this->load->model('Funcionarios_model');                    
            $this->load->model('Lojasprocedimento_model');
            
            $view_data['lojaInformativo'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultSessao();
            $view_data['graficoven'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultGrafico();
            $view_data['graficovenOntem'] = $this->Lojasprocedimento_model->selecionaLojaConexaoProcedureResultGraficoOntem();
            $view_data['info_clientesOntem'] = $this->Lojasprocedimento_model->selecionaClientesCadastradosOntem();           
            $view_data['lojasConec'] = $this->Lojas_model->PegarLoja();
            $view_data['DesafioDiaDirLoja'] = $this->Metas_model->DesafioDiaDirLoja();                                           
            $view_data['clientes_cadastrados'] = $this->Lojasprocedimento_model->selecionaClientesCadastrados();
            $view_data['LojaDesafios'] = $this->Metas_model->LojaDesafios();
            $view_data['lojas'] = $this->Lojas_model->listarLojas();

            $dataInicio = date('d.m.Y');
            $dataFinal = date('d.m.Y');

            $view_data['dataInicio'] = $dataInicio;
            $view_data['dataFinal'] = $dataFinal;                        

            $this->template->rander("dashboards/client_dashboard", $view_data);
        }

        $this->Settings_model->save_setting("user_" . $this->login_user->id . "_dashboard", "", "user");
    }

    private function _check_widgets_permissions() {
        if ($this->login_user->user_type === "staff") {
            return $this->_check_widgets_for_staffs();
        } else {
            return $this->_check_widgets_for_clients();
        }
    }

    private function _check_widgets_for_staffs() {
        //check which widgets are viewable to current logged in user
        $widget = array();

        $show_attendance = get_setting("module_attendance");
        $show_invoice = get_setting("module_invoice");
        $show_expense = get_setting("module_expense");
        $show_ticket = get_setting("module_ticket");
        $show_events = get_setting("module_event");
        $show_message = get_setting("module_message");

        $access_expense = $this->get_access_info("expense");
        $access_invoice = $this->get_access_info("invoice");
        $access_ticket = $this->get_access_info("ticket");
        $access_timecards = $this->get_access_info("attendance");

        $widget["new_posts"] = get_setting("module_timeline");
        $widget["meta_diaria"] = get_setting("module_timeline");
        $widget["meta_tkm"] = get_setting("module_timeline");
        $widget["meta_vitaminas"] = get_setting("module_timeline");
        $widget["meta_desconto"] = get_setting("module_timeline");
        $widget["dashboardTI"] = get_setting("module_timeline");
        $widget["my_timesheet_statistics"] = get_setting("module_project_timesheet");

        if ($show_attendance) {
            $widget["clock_in_out"] = true;
            $widget["timecard_statistics"] = true;
        }

        if ($show_events) {
            $widget["events_today"] = true;
            $widget["events"] = true;
        }

        if (get_setting("module_todo")) {
            $widget["todo_list"] = true;
        }

        //check module availability and access permission to show any widget

        if ($show_invoice && $show_expense && $access_expense->access_type === "all" && $access_invoice->access_type === "all") {
            $widget["income_vs_expenses"] = true;
        }

        if ($show_invoice && $access_invoice->access_type === "all") {
            $widget["invoice_statistics"] = true;
        }

        if ($show_ticket && $access_ticket->access_type === "all") {
            $widget["ticket_status"] = true;
        }

        if ($show_attendance && $access_timecards->access_type === "all") {
            $widget["clock_status"] = true;
            $widget["members_clocked_in"] = true;
            $widget["members_clocked_out"] = true;
        }

        if ($show_ticket && ($this->login_user->is_admin || $access_ticket->access_type)) {
            $widget["new_tickets"] = true;
            $widget["open_tickets"] = true;
            $widget["closed_tickets"] = true;
        }

        if ($this->can_view_team_members_list()) {
            $widget["all_team_members"] = true;
        }

        if ($this->can_view_team_members_list() && $show_attendance && $access_timecards->access_type === "all") {
            $widget["clocked_in_team_members"] = true;
            $widget["clocked_out_team_members"] = true;
        }

        if ($this->can_view_team_members_list() && $show_message) {
            $widget["latest_online_team_members"] = true;
        }

        $this->init_permission_checker("client");
        if ($show_message) {
            if ($this->access_type === "all") {
                $widget["latest_online_client_contacts"] = true;
            } else if ($this->module_group === "ticket" && $this->access_type === "specific") {
                $widget["latest_online_client_contacts"] = true;
            }
        }

        if ($show_invoice && ($this->login_user->is_admin || $access_invoice->access_type)) {
            $widget["total_invoices"] = true;
            $widget["total_payments"] = true;
        }

        if ($show_expense && $show_invoice && $access_invoice->access_type) {
            $widget["total_due"] = true;
        }

        if ($this->login_user->is_admin) {
            $widget["all_timesheets_statistics"] = true;
        }

        //universal widgets
        $widget["my_open_tasks"] = true;
        $widget["open_projects"] = true;
        $widget["completed_projects"] = true;
        $widget["project_timeline"] = true;
        $widget["task_status"] = true;
        $widget["sticky_note"] = true;
        $widget["all_tasks_kanban"] = true;
        $widget["open_projects_list"] = true;
        $widget["starred_projects"] = true;
        $widget["my_tasks_list"] = true;

        return $widget;
    }

    private function _check_widgets_for_clients() {
        //check widgets permission for client users

        $widget = array();

        $options = array("id" => $this->login_user->client_id);
        $client_info = $this->Clients_model->get_details($options)->row();
        $hidden_menu = explode(",", get_setting("hidden_client_menus"));

        $show_invoice_info = get_setting("module_invoice");
        $show_events = get_setting("module_event");

        $widget['show_invoice_info'] = $show_invoice_info;
        $widget['hidden_menu'] = $hidden_menu;
        $widget['client_info'] = $client_info;
        $widget['client_id'] = $client_info->id;
        $widget['page_type'] = "dashboard";

        if ($show_invoice_info) {
            if (!in_array("projects", $hidden_menu)) {
                $widget["total_projects"] = true;
            }
            if (!in_array("invoices", $hidden_menu)) {
                $widget["total_invoices"] = true;
            }
            if (!in_array("payments", $hidden_menu)) {
                $widget["total_payments"] = true;
                $widget["total_due"] = true;
            }
        }

        if (!in_array("projects", $hidden_menu)) {
            $widget["open_projects_list"] = true;
        }

        if ($show_events && !in_array("events", $hidden_menu)) {
            $widget["events"] = true;
        }

        if ($show_invoice_info && !in_array("invoices", $hidden_menu)) {
            $widget["invoice_statistics"] = true;
        }

        if ($show_events && !in_array("events", $hidden_menu)) {
            $widget["events_today"] = true;
        }

        if (get_setting("module_todo")) {
            $widget["todo_list"] = true;
        }

        if (!in_array("tickets", $hidden_menu) && get_setting("module_ticket") && $this->access_only_allowed_members_or_client_contact($this->login_user->client_id)) {
            $widget["new_tickets"] = true;
            $widget["open_tickets"] = true;
            $widget["closed_tickets"] = true;
        }

        //universal widgets
        $widget["sticky_note"] = true;

        return $widget;
    }

    public function save_sticky_note() {
        $note_data = array("sticky_note" => $this->input->post("sticky_note"));
        $this->Users_model->save($note_data, $this->login_user->id);
    }

    function modal_form($id = 0) {
        $view_data['model_info'] = $this->Dashboards_model->get_one($id);
        $this->load->view("dashboards/custom_dashboards/modal_form", $view_data);
    }

    function custom_widget_modal_form($id = 0) {
        $view_data['model_info'] = $this->Custom_widgets_model->get_one($id);
        $this->load->view("dashboards/custom_widgets/modal_form", $view_data);
    }

    function save_custom_widget() {
        $id = $this->input->post("id");

        if ($id) {
            $custom_widget_info = $this->_get_my_custom_widget($id);
            if (!$custom_widget_info) {
                redirect("forbidden");
            }
        }

        $data = array(
            "user_id" => $this->login_user->id,
            "title" => $this->input->post("title"),
            "content" => $this->input->post("content"),
            "show_title" => is_null($this->input->post("show_title")) ? "" : $this->input->post("show_title"),
            "show_border" => is_null($this->input->post("show_border")) ? "" : $this->input->post("show_border")
        );

        $save_id = $this->Custom_widgets_model->save($data, $id);

        if ($save_id) {
            $custom_widgets_info = $this->Custom_widgets_model->get_one($save_id);

            $custom_widgets_data = array(
                $custom_widgets_info->id => $custom_widgets_info->title
            );

            echo json_encode(array("success" => true, "id" => $save_id, "custom_widgets_row" => $this->_make_widgets_row($custom_widgets_data), "custom_widgets_data" => $this->_widgets_row_data($custom_widgets_data), 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function show_my_dashboards() {
        $this->load->model('Dashboards_model');
        $view_data["selecionaCargo"] = $this->Dashboards_model->selecionaCargo();
        $view_data["dashboards"] = $this->Dashboards_model->get_details(array("user_id" => $this->login_user->id))->result();
        $this->load->view('dashboards/list/dashboards_list', $view_data);
    }

    function view($id = 0) {
        validate_numeric_value($id);

        $selected_dashboard_id = get_setting("user_" . $this->login_user->id . "_dashboard");
        if (!$id) {
            $id = $selected_dashboard_id;
        }

        $dashboard_info = $this->_get_my_dashboard($id);

        if ($dashboard_info) {
            $this->Settings_model->save_setting("user_" . $this->login_user->id . "_dashboard", $dashboard_info->id, "user");

            $view_data["dashboard_info"] = $dashboard_info;
            $view_data["widget_columns"] = $this->make_dashboard(unserialize($dashboard_info->data));

            $view_data["dashboards"] = $this->Dashboards_model->get_details(array("user_id" => $this->login_user->id))->result();
            $view_data["dashboard_type"] = "custom";

            $this->template->rander("dashboards/custom_dashboards/view", $view_data);
        } else {
            redirect("dashboard"); //no dashbord selected. go to default dashboard
        }
    }

    // Dashboard Comercial
    function dashboardComercial() {
        $dataInicio = date('d.m.Y');
        $dataFinal = date('d.m.Y');
        $view_data['dataInicio'] =  date('d.m.Y');
        $view_data['dataFinal'] =  date('d.m.Y');

        $this->load->model('Dashboards_model');
        $this->load->model('TotalvendaGeral_model');
        $view_data['totalDiario'] = $this->TotalvendaGeral_model->selecionaTotalDiario('alterardata', $dataInicio, $dataFinal);
        $view_data['selecionaCargo'] = $this->Dashboards_model->selecionaCargo();
        $view_data['estoqueFiliais'] = $this->Dashboards_model->estoqueFiliais();
        $view_data['cdMaxxi'] = $this->Dashboards_model->cdMaxxi();
        $view_data['cdGAM'] = $this->Dashboards_model->cdGAM();
        $view_data['estoqueFiliais2'] = $this->Dashboards_model->estoqueFiliais2();
        $view_data['cdMaxxi2'] = $this->Dashboards_model->cdMaxxi2();
        $view_data['cdGAM2'] = $this->Dashboards_model->cdGAM2();
        $view_data['cmv30d'] = $this->Dashboards_model->cmv30d();
        $view_data['cmv60d'] = $this->Dashboards_model->cmv60d();
        $view_data['cmv90d'] = $this->Dashboards_model->cmv90d();
        $view_data['top10Estoque'] = $this->Dashboards_model->top10Estoque();
        $view_data['top10Vendas'] = $this->Dashboards_model->top10Vendas();
        $view_data['top10diasEstoque'] = $this->Dashboards_model->top10diasEstoque();
        $view_data['top10diasEstoque2'] = $this->Dashboards_model->top10diasEstoque2();
        $view_data['resumoOperacao'] = $this->Dashboards_model->resumoOperacao();
        $view_data['vendaAcumudada'] = $this->Dashboards_model->vendaAcumulada();
        $view_data['estoqueDesatualizado'] = $this->Dashboards_model->filiaisDesatualizadasEstoque();
        $view_data['vendaXcompra'] = $this->Dashboards_model->vendaXcompra();

        foreach ($view_data['estoqueFiliais'] as $filiais){
            $estoqueFiliais = $filiais->estoquemedio;
        }
        foreach ($view_data['cdMaxxi'] as $maxxi){
            $cdMAXXI = $maxxi->estoquemedio;            
        }
        foreach ($view_data['cdGAM'] as $gam){
            $cdGAM = $gam->valorestoque;
        }
        foreach ($view_data['estoqueFiliais2'] as $filiais2){
            $estoqueFiliais2 = $filiais2->estoquemedio;
        }
        foreach ($view_data['cdMaxxi2'] as $maxxi2){
            $cdMAXXI2 = $maxxi2->estoquemedio;
        }
        foreach ($view_data['cdGAM2'] as $gam2){
            $cdGAM2 = $gam2->valorestoque;
        }
        foreach ($view_data['cmv30d'] as $dias){
            $dias30 = $dias->cmv30;
        }
        foreach ($view_data['cmv60d'] as $dias){
            $dias60 = $dias->cmv60;
        }
        foreach ($view_data['cmv90d'] as $dias){
            $dias90 = $dias->cmv90;
        }
        foreach ($view_data['vendaAcumudada'] as $acumulada){
            $vendaAcumulada = $acumulada->liquido;
        }
        foreach ($view_data['resumoOperacao'] as $resumo){
            if($resumo->operacao == 'Compra'){
                $Compras = $resumo->valores;
            }
        }
        foreach ($view_data['resumoOperacao'] as $resumo){
            if($resumo->operacao == 'Transferencia'){
                $Transferencias = $resumo->valores;
            }
        }
        foreach ($view_data['resumoOperacao'] as $resumo){
            if($resumo->operacao == 'Outras'){
                $Outras = $resumo->valores;
            }
        }
        foreach ($view_data['resumoOperacao'] as $resumo){
            if($resumo->operacao == 'Bonificacao'){
                $Bonificacoes = $resumo->valores;
            }
        }

        $view_data['valorTotal'] = $estoqueFiliais2+$cdMAXXI2+$cdGAM2;
        $total = $view_data['valorTotal'] = $estoqueFiliais2+$cdMAXXI2+$cdGAM2;
        $view_data['calculoOne'] = round($total/$dias30*30);
        $view_data['calculoTwo'] = round($total/$dias60*30);
        $view_data['calculoThree'] = round($total/$dias90*30);
        $view_data['calculoFour'] = $estoqueFiliais2*100/$estoqueFiliais-100;
        $view_data['calculoFive'] = $cdMAXXI2*100/$cdMAXXI-100; 
        $view_data['calculoSix'] = $cdGAM2*100/$cdGAM-100;
        $view_data['calculoSeven'] = $Compras/$vendaAcumulada*100;
        $view_data['calculoEight'] = $Transferencias/$vendaAcumulada*100;
        $view_data['calculoNine'] = $Outras/$vendaAcumulada*100;
        $view_data['calculoTen'] = $Bonificacoes/$vendaAcumulada*100;

        $this->template->rander("dashboards/dashboardComercial", $view_data);
    }
    
    // Modal top 10 Dashboard Comercial
    function modalTop10() {        
        $this->load->model('Dashboards_model');
        $view_data = $this->Dashboards_model->top10Itens_compra();
        echo json_encode($view_data);
    }

    // Modal top 10 Dashboard Comercial
    function modalTop10FabricantesCompra() {
        $this->load->model('Dashboards_model');
        $view_data = $this->Dashboards_model->top10Fabricantes_compra();
        echo json_encode($view_data);
    }

    // Modal top 10 Dashboard Comercial
    function modalTop10FornecedoresCompra() {
        $this->load->model('Dashboards_model');
        $view_data = $this->Dashboards_model->top10Fornecedores_compra();
        echo json_encode($view_data);
    }

    // Modal top 10 Dashboard Comercial
    function modalTop10CategoriasCompra() {
        $this->load->model('Dashboards_model');
        $view_data = $this->Dashboards_model->top10Categorias_compra();
        echo json_encode($view_data);
    }
    // Fim Comercial    

    /* Dashboard TI */
    function dashboardTi() {

        $this->load->model("Dashboards_model");
        $this->load->model('Tickets_model');
        
        $dataInicio = '';
        $dataFinal = '';

        if($this->input->post('dataInicial')) {
            //Carrega os valores passado por POST.
            $dataInicio = $this->input->post('dataInicial');
            $dataFinal = $this->input->post('dataFinal');    
            $view_data['dataInicio'] = $dataInicio;
            $view_data['dataFinal']  = $dataFinal;
        } else {
            $view_data['dataInicio'] = date('d.m.Y');
            $view_data['dataFinal']  = date('d.m.Y');   
        }    

        /* Cards */
        $view_data['quantidade'] = $this->Dashboards_model->countChamados($dataInicio, $dataFinal);
        $view_data['quantidadeFinalizados'] = $this->Dashboards_model->countFinalizados($dataInicio, $dataFinal);
        $view_data['quantidadeRespondidos'] = $this->Dashboards_model->countRespondidos($dataInicio, $dataFinal);   
        $view_data['quantidadeEmAberto'] = $this->Dashboards_model->countEmAberto($dataInicio, $dataFinal);

        /* Ranking */
        $view_data['ranking'] = $this->Tickets_model->selectRanking($dataInicio, $dataFinal);           
        
        /* Gráfico um*/   
        if($m = $this->input->POST('mesgrafico')) { 
            switch ($m) {
                case '01':
                    $dataIni = date('01.01.Y');
                    $dataFin = date('31.01.Y'); 
                    $view_data['mesGraf'] = 'Janeiro';
                    break;
                
                case '02':
                    $dataIni = date('01.02.Y');
                    $dataFin = date('30.02.Y'); 
                    $view_data['mesGraf'] = 'Fevereiro';
                    break;

                case '03':
                    $dataIni = date('01.03.Y');
                    $dataFin = date('31.03.Y'); 
                    $view_data['mesGraf'] = 'Março';
                    break;

                case '04':
                    $dataIni = date('01.04.Y');
                    $dataFin = date('30.04.Y'); 
                    $view_data['mesGraf'] = 'Abril';
                    break;

                case '05':
                    $dataIni = date('01.05.Y');
                    $dataFin = date('31.05.Y'); 
                    $view_data['mesGraf'] = 'Maio';
                    break;

                case '06':
                    $dataIni = date('01.06.Y');
                    $dataFin = date('30.06.Y'); 
                    $view_data['mesGraf'] = 'Junho';
                    break;

                case '07':
                    $dataIni = date('01.07.Y');
                    $dataFin = date('31.07.Y'); 
                    $view_data['mesGraf'] = 'Julho';
                    break;

                case '08':
                    $dataIni = date('01.08.Y');
                    $dataFin = date('31.08.Y'); 
                    $view_data['mesGraf'] = 'Agosto';
                    break;

                case '09':
                    $dataIni = date('01.09.Y');
                    $dataFin = date('30.09.Y'); 
                    $view_data['mesGraf'] = 'Setembro';
                    break;

                case '10':
                    $dataIni = date('01.10.Y');
                    $dataFin = date('31.10.Y'); 
                    $view_data['mesGraf'] = 'Outubro';
                    break;

                case '11':
                    $dataIni = date('01.11.Y');
                    $dataFin = date('30.11.Y'); 
                    $view_data['mesGraf'] = 'Novembro';
                    break;

                case '12':
                    $dataIni = date('01.12.Y');
                    $dataFin = date('31.12.Y'); 
                    $view_data['mesGraf'] = 'Dezembro';
                    break;
            }
        } else {
            $dataIni = date('01.m.Y');
            $dataFin = date('d.m.Y');
            $view_data['mesGraf'] = 'Atual';
        }       

        $view_data['graficoWillian'] = $this->Dashboards_model->graficoChamados($dataIni, $dataFin);
        $view_data['graficoAntonio'] = $this->Dashboards_model->graficoChamadosDois($dataIni, $dataFin); 
        $view_data['graficoJorge'] = $this->Dashboards_model->graficoChamadosTres($dataIni, $dataFin);
        $view_data['graficoLucas'] = $this->Dashboards_model->graficoChamadosQuatro($dataIni, $dataFin);   
        
        /* Gráfico dois */         
        if($this->input->POST('fechadosform')){
            $var = $this->input->POST('fechadosform');
            $view_data['nmFechados'] = $var;
        } else {
            $var = 'Todos';
            $view_data['nmFechados'] = 'Todos';
        }
        
        $view_data['line_chart'] = $this->Dashboards_model->lineChartTI($var);

        $this->template->rander("dashboards/dashboardTI", $view_data);
    }

    /* Fim dashboard TI */

    function view_custom_widget() {
        $id = $this->input->post("id");
        validate_numeric_value($id);

        $widget_info = $this->Custom_widgets_model->get_one($id);

        $view_data["model_info"] = $widget_info;

        $this->load->view("dashboards/custom_widgets/view", $view_data);
    }

    function view_default_widget() {
        $widget = $this->input->post("widget");

        $view_data["widget"] = $this->_make_dashboard_widgets($widget);

        $this->load->view("dashboards/custom_dashboards/edit/view_default_widget", $view_data);
    }

    private function _get_my_dashboard($id = 0) {
        if ($id) {
            return $this->Dashboards_model->get_details(array("user_id" => $this->login_user->id, "id" => $id))->row();
        }
    }

    private function _get_my_custom_widget($id = 0) {
        if ($id) {
            return $this->Custom_widgets_model->get_details(array("user_id" => $this->login_user->id, "id" => $id))->row();
        }
    }

    function edit_dashboard($id = 0) {
        validate_numeric_value($id);

        $dashboard_info = $this->_get_my_dashboard($id);

        if (!$dashboard_info) {
            redirect("forbidden");
        }

        $view_data["dashboard_info"] = $dashboard_info;
        $view_data["widget_sortable_rows"] = $this->_make_editable_rows(unserialize($dashboard_info->data));
        $view_data["widgets"] = $this->_make_widgets($dashboard_info->id);

        $this->template->rander("dashboards/custom_dashboards/edit/index", $view_data);
    }

    function save() {
        $id = $this->input->post("id");

        if ($id) {
            $dashboard_info = $this->_get_my_dashboard($id);
            if (!$dashboard_info) {
                redirect("forbidden");
            }
        }

        $dashboard_data = json_decode($this->input->post("data"));

        $data = array(
            "user_id" => $this->login_user->id,
            "title" => $this->input->post("title"),
            "data" => $dashboard_data ? serialize($dashboard_data) : serialize(array()),
            "color" => $this->input->post("color")
        );

        $save_id = $this->Dashboards_model->save($data, $id);

        if ($save_id) {
            echo json_encode(array("success" => true, "dashboard_id" => $save_id, 'message' => lang('record_saved')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('error_occurred')));
        }
    }

    function delete() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        if ($this->_get_my_dashboard($id) && $this->Dashboards_model->delete($id)) {
            echo json_encode(array("success" => true, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    function delete_custom_widgets() {
        $id = $this->input->post('id');

        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        if ($this->_get_my_custom_widget($id) && $this->Custom_widgets_model->delete($id)) {
            echo json_encode(array("success" => true, "id" => $id, 'message' => lang('record_deleted')));
        } else {
            echo json_encode(array("success" => false, 'message' => lang('record_cannot_be_deleted')));
        }
    }

    private function _remove_widgets($widgets = array()) {
        $widgets_permission = $this->_check_widgets_permissions();

        foreach ($widgets as $widget) {
            if (!get_array_value($widgets_permission, $widget) && !is_numeric($widget)) {
                unset($widgets[array_search($widget, $widgets)]);
            }
        }

        return $widgets;
    }

    private function _get_default_widgets() {
        //app widgets
        if ($this->login_user->user_type == "staff") {
            $default_widgets_array = array(
                "open_projects",
                "open_projects_list",
                "completed_projects",
                "starred_projects",
                "project_timeline",
                "my_open_tasks",
                "my_tasks_list",
                "all_tasks_kanban",
                "task_status",
                "clock_in_out",
                "members_clocked_in",
                "members_clocked_out",
                "all_team_members",
                "clocked_in_team_members",
                "clocked_out_team_members",
                "latest_online_team_members",
                "latest_online_client_contacts",
                "my_timesheet_statistics",
                "all_timesheets_statistics",
                "timecard_statistics",
                "total_invoices",
                "total_payments",
                "total_due",
                "invoice_statistics",
                "income_vs_expenses",
                "new_tickets",
                "open_tickets",
                "closed_tickets",
                "ticket_status",
                "events_today",
                "events",
                "sticky_note",
                "todo_list",
                "new_posts",
                "meta_diaria",
                "meta_tkm",
                "meta_vitaminas",
                "meta_desconto",
                "dashboardTi"
            );
        } else {
            $default_widgets_array = array(
                "total_projects",
                "open_projects_list",
                "total_invoices",
                "total_payments",
                "total_due",
                "invoice_statistics",
                "new_tickets",
                "open_tickets",
                "closed_tickets",
                "events_today",
                "events",
                "sticky_note",
                "todo_list",
            );
        }

        return $default_widgets_array;
    }

    private function _make_widgets($dashboard_id = 0) {

        $default_widgets_array = $this->_get_default_widgets();
        $checked_widgets_array = $this->_remove_widgets($default_widgets_array);

        $widgets_array = array_fill_keys($checked_widgets_array, "default_widgets");

        //custom widgets
        $custom_widgets = $this->Custom_widgets_model->get_details(array("user_id" => $this->login_user->id))->result();
        if ($custom_widgets) {
            foreach ($custom_widgets as $custom_widget) {
                $widgets_array[$custom_widget->id] = $custom_widget->title;
            }
        }

        //when its edit mode, we have to remove the widgets which have already in the dashboard
        $dashboard_info = $this->Dashboards_model->get_one($dashboard_id);

        if ($dashboard_info) {
            foreach (unserialize($dashboard_info->data) as $element) {
                $columns = get_array_value((array) $element, "columns");
                if ($columns) {
                    foreach ($columns as $contents) {
                        foreach ($contents as $content) {
                            $widget = get_array_value((array) $content, "widget");
                            if ($widget && array_key_exists($widget, $widgets_array)) {
                                unset($widgets_array[$widget]);
                            }
                        }
                    }
                }
            }
        }

        return $this->_make_widgets_row($widgets_array);
    }

    private function _make_widgets_row($widgets_array = array(), $permissions_array = array()) {
        $widgets = "";

        foreach ($widgets_array as $key => $value) {
            $error_class = "";
            if (count($permissions_array) && !is_numeric($key) && !get_array_value($permissions_array, $key)) {
                $error_class = "error";
            }
            $widgets .= "<div data-value=" . $key . " class='mb5 widget clearfix p10 bg-white $error_class'>" .
                $this->_widgets_row_data(array($key => $value))
                . "</div>";
        }

        if ($widgets) {
            return $widgets;
        } else {
            return "<span class='text-off empty-area-text'>" . lang('no_more_widgets_available') . "</span>";
        }
    }

    private function _widgets_row_data($widget_array) {
        $key = key($widget_array);
        $value = $widget_array[key($widget_array)];
        $details_button = "";
        if (is_numeric($key)) {

            $widgets_title = $value;
            $details_button = modal_anchor(get_uri("dashboard/view_custom_widget"), "<i class='fa fa-ellipsis-h'></i>", array("class" => "text-off pr10 pl10", "title" => lang('custom_widget_details'), "data-post-id" => $key));
        } else {
            $details_button = modal_anchor(get_uri("dashboard/view_default_widget"), "<i class='fa fa-ellipsis-h'></i>", array("class" => "text-off pr10 pl10", "title" => lang($key), "data-post-widget" => $key));
            $widgets_title = lang($key);
        }

        return "<span class='pull-left text-left'>" . $widgets_title . "</span>
                <span class='pull-right'>" . $details_button . "<i class='fa fa-arrows text-off'></i></span>";
    }

    private function _make_editable_rows($elements) {
        $view = "";
        $permissions_array = $this->_check_widgets_permissions();

        if ($elements) {
            foreach ($elements as $element) {

                $column_ratio = get_array_value((array) $element, "ratio");
                $column_ratio_explode = explode("-", $column_ratio);

                $view .= "<row class='widget-row clearfix block bg-white' data-column-ratio='" . $column_ratio . "'>
                            <div class='pull-left row-controller text-off font-16'>
                                <i class='fa fa-bars move'></i>
                                <i class='fa fa-times delete delete-widget-row'></i>
                            </div>
                            <div class = 'pull-left clearfix row-container'>";

                $columns = get_array_value((array) $element, "columns");

                if ($columns) {
                    foreach ($columns as $key => $value) {
                        $column_class_value = $this->_get_column_class_value($key, $columns, $column_ratio_explode);
                        $view .= "<div class = 'pr0 widget-column col-md-" . $column_class_value . " col-sm-" . $column_class_value . "'>
                                    <div id = 'add-column-panel-" . rand(500, 10000) . "' class = 'add-column-panel add-column-drop text-center p15'>";

                        foreach ($value as $content) {
                            $widget_value = get_array_value((array) $content, "widget");
                            $view .= $this->_make_widgets_row(array($widget_value => get_array_value((array) $content, "title")), $permissions_array);
                        }

                        $view .= "</div></div>";
                    }
                }
                $view .= "</div></row>";
            }
            return $view;
        }
    }

    private function make_dashboard($elements) {
        $view = "";
        if ($elements) {

            foreach ($elements as $element) {
                $view .= "<div class='dashboards-row clearfix row'>";

                $columns = get_array_value((array) $element, "columns");
                $column_ratio = explode("-", get_array_value((array) $element, "ratio"));

                if ($columns) {

                    foreach ($columns as $key => $value) {
                        $view .= "<div class='widget-container col-md-" . $this->_get_column_class_value($key, $columns, $column_ratio) . "'>";

                        foreach ($value as $content) {
                            $widget = get_array_value((array) $content, "widget");
                            if ($widget) {
                                $view .= $this->_make_dashboard_widgets($widget);
                            }
                        }
                        $view .= "</div>";
                    }
                }

                $view .= "</div>";
            }
            return $view;
        }
    }

    private function _make_dashboard_widgets($widget = "") {
        $widgets_array = $this->_check_widgets_permissions();

        //custom widgets
        if (is_numeric($widget)) {
            $view_data["widget_info"] = $this->Custom_widgets_model->get_one($widget);
            return $this->load->view("dashboards/custom_dashboards/extra_data/custom_widget", $view_data, true);
        }

        if ($this->login_user->user_type == "staff") {
            return $this->_get_widgets_for_staffs($widget, $widgets_array);
        } else {
            return $this->_get_widgets_for_client($widget, $widgets_array);
        }
    }

    private function _get_widgets_for_staffs($widget, $widgets_array) {
        if (get_array_value($widgets_array, $widget)) {
            if ($widget == "clock_in_out") {
                return clock_widget(true);
            } else if ($widget == "events_today") {
                return events_today_widget(true);
            } else if ($widget == "new_posts") {
                return new_posts_widget(true);
            } else if ($widget == "meta_vendas") {
                return meta_vendas_widget(true);            
            } else if ($widget == "meta_suplementos") {
                return meta_suplementos_widget(true);
            } else if ($widget == "dashboardTIAbertos") { /* Widget da dashboardTI */
                return dashboardTIAbertos(true);
            } else if ($widget == "dashboardTIFinalizados") { /* Widget da dashboardTI */
                return dashboardTIFinalizados(true);
            } else if ($widget == "dashboardTIRespondidos") { /* Widget da dashboardTI */
                return dashboardTIRespondidos(true);
            } else if ($widget == "dashboardTIEmAberto") { /* Widget da dashboardTI */
                return dashboardTIEmAberto(true);
            } else if ($widget == "ranking") { /* Widget da dashboardTI */
                return ranking(true);
            } else if ($widget == "invoice_statistics") {
                return invoice_statistics_widget(true);
            } else if ($widget == "my_timesheet_statistics") {
                return project_timesheet_statistics_widget("my_timesheet_statistics", true);
            } else if ($widget == "ticket_status") {
                return ticket_status_widget(true);
            } else if ($widget == "timecard_statistics") {
                return timecard_statistics_widget(true);
            } else if ($widget == "income_vs_expenses") {
                return income_vs_expenses_widget("h370", true);
            } else if ($widget == "events") {
                return events_widget(true);
            } else if ($widget == "my_open_tasks") {
                return my_open_tasks_widget(true);
            } else if ($widget == "project_timeline") {
                return $this->load->view("dashboards/custom_dashboards/extra_data/widget_with_heading", array("icon" => "fa-clock-o", "widget" => $widget), true);
            } else if ($widget == "task_status") {
                return my_task_stataus_widget("h370", true);
            } else if ($widget == "sticky_note") {
                return sticky_note_widget("h370", true);
            } else if ($widget == "all_tasks_kanban") {
                return all_tasks_kanban_widget(true);
            } else if ($widget == "todo_list") {
                return todo_list_widget(true);
            } else if ($widget == "open_projects") {
                return open_projects_widget("", true);
            } else if ($widget == "completed_projects") {
                return completed_projects_widget("", true);
            } else if ($widget == "members_clocked_in") {
                return count_clock_in_widget(true);
            } else if ($widget == "members_clocked_out") {
                return count_clock_out_widget(true);
            } else if ($widget == "open_projects_list") {
                return my_open_projects_widget("", true);
            } else if ($widget == "starred_projects") {
                return my_starred_projects_widget("", true);
            } else if ($widget == "new_tickets" || $widget == "open_tickets" || $widget == "closed_tickets") {
                $this->init_permission_checker("ticket");
                $explode_widget = explode("_", $widget);
                return ticket_status_widget_small(array("status" => $explode_widget[0], "allowed_ticket_types" => $this->allowed_ticket_types), true);
            } else if ($widget == "all_team_members") {
                return all_team_members_widget(true);
            } else if ($widget == "clocked_in_team_members") {
                $this->init_permission_checker("attendance");
                return clocked_in_team_members_widget(array("access_type" => $this->access_type, "allowed_members" => $this->allowed_members), true);
            } else if ($widget == "clocked_out_team_members") {
                $this->init_permission_checker("attendance");
                return clocked_out_team_members_widget(array("access_type" => $this->access_type, "allowed_members" => $this->allowed_members), true);
            } else if ($widget == "latest_online_team_members") {
                return active_members_and_clients_widget("staff", true);
            } else if ($widget == "latest_online_client_contacts") {
                return active_members_and_clients_widget("client", true);
            } else if ($widget == "total_invoices" || $widget == "total_payments" || $widget == "total_due") {
                $explode_widget = explode("_", $widget);
                return get_invoices_value_widget($explode_widget[1], true);
            } else if ($widget == "my_tasks_list") {
                return my_tasks_list_widget(true);
            } else if ($widget == "all_timesheets_statistics") {
                return project_timesheet_statistics_widget("all_timesheets_statistics", true);
            }
        } else {
            return invalid_access_widget(true);
        }
    }

    private function _get_widgets_for_client($widget, $widgets_array) {
        //client's widgets
        $client_info = get_array_value($widgets_array, "client_info");
        $client_id = get_array_value($widgets_array, "client_id");

        if (get_array_value($widgets_array, $widget)) {
            if ($widget == "total_projects") {
                return $this->load->view("clients/info_widgets/tab", array("tab" => "projects", "client_info" => $client_info), true);
            } else if ($widget == "total_invoices") {
                return $this->load->view("clients/info_widgets/tab", array("tab" => "invoice_value", "client_info" => $client_info), true);
            } else if ($widget == "total_payments") {
                return $this->load->view("clients/info_widgets/tab", array("tab" => "payments", "client_info" => $client_info), true);
            } else if ($widget == "total_due") {
                return $this->load->view("clients/info_widgets/tab", array("tab" => "due", "client_info" => $client_info), true);
            } else if ($widget == "open_projects_list") {
                return my_open_projects_widget($client_id, true);
            } else if ($widget == "events") {
                return events_widget(true);
            } else if ($widget == "sticky_note") {
                return sticky_note_widget("h370", true);
            } else if ($widget == "invoice_statistics") {
                return invoice_statistics_widget(true);
            } else if ($widget == "events_today") {
                return events_today_widget(true);
            } else if ($widget == "todo_list") {
                return todo_list_widget(true);
            } else if ($widget == "new_tickets" || $widget == "open_tickets" || $widget == "closed_tickets") {
                $explode_widget = explode("_", $widget);
                return ticket_status_widget_small(array("status" => $explode_widget[0]), true);
            }
        } else {
            return invalid_access_widget(true);
        }
    }

    private function _get_column_class_value($key, $columns, $column_ratio) {
        $columns_array = array(1 => 12, 2 => 6, 3 => 4, 4 => 3);

        $column_count = count($columns);
        $column_ratio_count = count($column_ratio);

        $class_value = $column_ratio[$key];

        if ($column_count < $column_ratio_count) {
            $class_value = $columns_array[$column_count];
        }

        return $class_value;
    }

    function save_dashboard_sort() {
        validate_submitted_data(array(
            "id" => "required|numeric"
        ));

        $id = $this->input->post('id');

        $data = array(
            "sort" => $this->input->post('sort')
        );

        if ($id) {
            $save_id = $this->Dashboards_model->save($data, $id);

            if ($save_id) {
                echo json_encode(array("success" => true, 'message' => lang('record_saved')));
            } else {
                echo json_encode(array("success" => false, lang('error_occurred')));
            }
        }
    }
}

/* End of file dashboard.php */
/* Location: ./application/controllers/dashboard.php */