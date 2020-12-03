<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Filtro extends MY_Controller {
    
    function __construct()
    {
        parent::__construct();
    }

    function index(){
        $this->template->rander('filtro');
    }

    /* Autocompletar produtos */
    function autoCompletarNmProdutos() {        
        $this->load->model('Filtro_model');
        $nmProduto = $this->input->POST('nmProduto');        

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->selecionaNomeProdutos($nmProduto);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }/*  else {
            $this->template->rander('relatorios/produtos', $data);
        } */
    }

    /* Autocompletar fornecedores */
    function autoCompletarnmfornecedor() {      
        $this->load->model('Filtro_model');
        $nmFornecedor = $this->input->POST('nmfornecedor');        

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->selecionaNomeFornecedor($nmFornecedor);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }/*  else {
            $this->template->rander('relatorios/produtos', $data);
        } */
    }

    /* Autocompletar fabricante */
    function autoCompletarnmfabricante(){    
        $this->load->model('Filtro_model');
        $nmfabricante = $this->input->POST('nmfabricante');        

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->selecionaNomeFabricante($nmfabricante);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }/*  else {
            $this->template->rander('relatorios/produtos', $data);
        } */
    }

    /* Autocompletar linha */
    function autoCompletanmlinha(){
        $this->load->model('Filtro_model');
        $nmlinha = $this->input->POST('nmlinha');        

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->selecionaNomeLinha($nmlinha);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }/*  else {
            $this->template->rander('relatorios/produtos', $data);
        } */   
    }

    /* Autocompletar categoria */
    function autoCompletanmcategoria() {
        $this->load->model('Filtro_model');
        $nmcategoria = $this->input->POST('nmcategoria');        

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->selecionaNomeCategoria($nmcategoria);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }/*  else {
            $this->template->rander('relatorios/produtos', $data);
        } */     
    }

    /* Preencher o código do produto */
    function preencheCdProduto() {
        $this->load->model('Filtro_model');
        $nmProduto = $this->input->POST('prProdutos'); // Não ta chegando aqui
        // $nmProduto = 'TESTE RAPIDO PANBIO COVID-19 1UN IGG/IGM'; // Somente para testes       

        //Autocomplete
        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheCdProduto($nmProduto);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->cdproduto
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o nome do produto */
    function preencheNomeProduto() {
        $this->load->model('Filtro_model');
        $cdProduto = $this->input->POST('cdProduto');

        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheNomeProduto($cdProduto);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o código do fornecedor */
    function preencheCdFornecedor() {
        $this->load->model('Filtro_model');
        $nmFornecedor = $this->input->POST('nmFornecedor');
            
        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheCdFornecedor($nmFornecedor);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->cdfornecedor
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o nome do fornecedor */
    function preencheNomeFornecedor() {
        $this->load->model('Filtro_model');
        $cdFornecedor = $this->input->POST('cdfornecedores');

        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheNomeFornecedor($cdFornecedor);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o código do fabricante */
    function preencheCdFabricante() {
        $this->load->model('Filtro_model');
        $nmFabricante = $this->input->POST('nmFabricantes');
            
        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheCdfabricante($nmFabricante);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->cdfabricante
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o nome do fabricante */
    function preencheNomeFabricante() {
        $this->load->model('Filtro_model');
        $cdFabricante = $this->input->POST('cdFabricantes');

        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheNomeFabricante($cdFabricante);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o código da linha */
    function preencheCdLinha() {
        $this->load->model('Filtro_model');
        $nmLinha = $this->input->POST('nmLinhas');
            
        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheCdLinha($nmLinha);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->cdlinha
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }    

    /* Preencher o nome da linha */
    function preencheNomeLinha() {
        $this->load->model('Filtro_model');
        $cdLinha = $this->input->POST('cdLinhas');

        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheNomeLinha($cdLinha);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }

    /* Preencher o código da Categoria */
    function preencheCdCategoria() {
        $this->load->model('Filtro_model');
        $nmCategoria = $this->input->POST('nmCategorias');
            
        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheCdCategoria($nmCategoria);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->cdgrupo
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }
    
    /* Preencher o nome da categoria */
    function preencheNomeCategoria() {
        $this->load->model('Filtro_model');
        $cdCategoria = $this->input->POST('cdCategorias');

        $data['response'] = 'false';
        $query = $this->Filtro_model->preencheNomeCategoria($cdCategoria);
        
        if(!empty($query)) {
            $data['response'] = 'true';
            $data['message'] = array();
            foreach ($query as $row) {
                $data['message'][] = array(
                    'value' => $row->descricao
                );
            }
        }

        if('IS_AJAX') {
            echo json_encode($data);
        }
    }
}
