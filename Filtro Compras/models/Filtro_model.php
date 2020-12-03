<?php

class Filtro_model extends CI_Model {
        
    public function __construct() {
            parent::__construct();
    }

    /* Autocompletar produtos */
    public function selecionaNomeProdutos($nmProduto) {
        $Postgre = $this->load->database('postgre', TRUE);        

        $sql = "SELECT descricao FROM pdv_produtos pp WHERE cdsubgrupo <> 28 AND pp.descricao LIKE '$nmProduto%' ORDER BY descricao LIMIT 15";
        return  $Postgre->query($sql)->result();       
    }

    /* Autocompletar fornecedores */
    public function selecionaNomeFornecedor($nmFornecedor) {
        $Postgre = $this->load->database('postgre', TRUE);        

        $sql = "SELECT descricao FROM pdv_fornecedores pf WHERE pf.descricao LIKE '$nmFornecedor%' ORDER BY descricao LIMIT 15";
        return  $Postgre->query($sql)->result();    
    }

    /* Autocompletar fabricante */
    public function selecionaNomeFabricante($nmFabricante) {
        $Postgre = $this->load->database('postgre', TRUE);        

        $sql = "SELECT descricao FROM pdv_fabricante pf WHERE pf.descricao LIKE '$nmFabricante%' ORDER BY descricao LIMIT 15";
        return  $Postgre->query($sql)->result();  
    }

    /* Autocompletar linha */
    public function selecionaNomeLinha($nmlinha) {
        $Postgre = $this->load->database('postgre', TRUE);        

        $sql = "SELECT descricao FROM pdv_linha pl WHERE pl.descricao LIKE '$nmlinha%' ORDER BY descricao LIMIT 15";
        return  $Postgre->query($sql)->result();      
    }

    /* Autocompletar categoria */
    public function selecionaNomeCategoria($nmcategoria) {
        $Postgre = $this->load->database('postgre', TRUE);        

        $sql = "SELECT descricao FROM pdv_categoria pc WHERE pc.cdgrupo = 1 AND pc.descricao LIKE '$nmcategoria%' ORDER BY descricao LIMIT 15";
        return  $Postgre->query($sql)->result();          
    }

    /*                 Preencher input vazio:                 */

    /* Preencher cdProduto */
    public function preencheCdProduto($nmProduto) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao, cdproduto FROM pdv_produtos pp WHERE pp.descricao = '$nmProduto' AND NOT pp.descricao = '' LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preencher nmProduto */
    public function preencheNomeProduto($cdProduto) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao FROM pdv_produtos pp WHERE pp.cdproduto = $cdProduto LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche cdFornecedor */
    public function preencheCdFornecedor($nmFornecedor) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT cdfornecedor FROM pdv_fornecedores pf WHERE pf.descricao = '$nmFornecedor' AND NOT pf.descricao = '' LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche nmFornecedor */
    public function preencheNomeFornecedor($cdFornecedor) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao FROM pdv_fornecedores pf WHERE pf.cdfornecedor = $cdFornecedor LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche cdFabricante */
    public function preencheCdFabricante($nmFabricante) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT cdfabricante FROM pdv_fabricante pf WHERE pf.descricao = '$nmFabricante' AND NOT pf.descricao = '' LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche nmFabricante*/
    public function preencheNomeFabricante($cdFabricante) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao FROM pdv_fabricante pf WHERE pf.cdFabricante = $cdFabricante LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche cdlinha */
    public function preencheCdlinha($nmLinha) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT cdlinha FROM pdv_linha pl WHERE pl.descricao = '$nmLinha' AND NOT pl.descricao = '' LIMIT 1";
        return  $Postgre->query($sql)->result();
    }  
    
    /* Preenche nmLinha*/
    public function preencheNomeLinha($cdLinha) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao FROM pdv_linha pl WHERE pl.cdlinha = $cdLinha LIMIT 1";
        return  $Postgre->query($sql)->result();
    }

    /* Preenche cdCategoria */
    public function preencheCdCategoria($nmCategoria) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT cdgrupo FROM pdv_categoria pc WHERE pc.descricao = '$nmCategoria' AND cdsubgrupo = 1 AND NOT pc.descricao = '' LIMIT 1";
        return  $Postgre->query($sql)->result();
    }
    
    /* Preenche nmCategoria*/
    public function preencheNomeCategoria($cdCategoria) {
        $Postgre = $this->load->database('postgre', TRUE);
        
        $sql = "SELECT descricao FROM pdv_categoria pc WHERE pc.cdgrupo = $cdCategoria AND cdsubgrupo = 1 LIMIT 1";
        return  $Postgre->query($sql)->result();
    }    
}
