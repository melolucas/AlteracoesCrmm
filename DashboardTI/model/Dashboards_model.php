<?php

class Dashboards_model extends Crud_model {

    private $table = null;

    function __construct() {
        $this->table = 'dashboards';
        $this->table = 'users';
        parent::__construct($this->table);
    }

    function get_details($options = array()) {
        $dashboard_table = $this->db->dbprefix("dashboards");

        $where = "";

        $user_id = get_array_value($options, "user_id");
        if ($user_id) {
            $where .= " AND $dashboard_table.user_id=$user_id";
        }
        
        $id = get_array_value($options, "id");
        
        if ($id) {
            $where .= " AND $dashboard_table.id= $id";            
        }

        $sql = "SELECT $dashboard_table.*, IF($dashboard_table.sort!=0, $dashboard_table.sort, $dashboard_table.id) AS new_sort
                FROM $dashboard_table
                WHERE $dashboard_table.deleted=0 $where
                ORDER BY new_sort DESC";

        return $this->db->query($sql);
    }

    public function selecionaCargo(){

        $email =$this->login_user->email;

        $this->db->select('role_id, job_title');
        $this->db->from('users');
        $this->db->where('email', $email);

        $query = $this->db->get();
        return $query->result();
    }

    public function estoqueFiliais(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                sum(vlrcustomedio) EstoqueMedio
                from posicaoestoques
                where data = current_date -2
                and cdloja <> 200
                and cdsubgrupo <> 28";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function estoqueFiliais2(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                sum(vlrcustomedio) EstoqueMedio
                from posicaoestoques
                where data = current_date -1
                and cdloja <> 200
                and cdsubgrupo <> 28";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cdMaxxi(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT 
                sum(vlrcustomedio) EstoqueMedio
                from posicaoestoques
                where data = current_date -2
                and cdloja = 200
                and cdsubgrupo <> 28";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cdMaxxi2(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT 
                sum(vlrcustomedio) EstoqueMedio
                from posicaoestoques
                where data = current_date -1
                and cdloja = 200
                and cdsubgrupo <> 28";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cdGAM(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT valorestoque from estoquemaxxigam
                where data = current_date -2
                limit 1";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cdGAM2(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT valorestoque from estoquemaxxigam
                where data = current_date -1
                limit 1";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cmv30d(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                sum(vlrcusto) as cmv30
                from totaldia where data >= current_date -31
                and data < current_date";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cmv60d(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT 
                sum(vlrcusto)/2 as CMV60
                from totaldia where data >= current_date -61
                and data < current_date";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function cmv90d(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                sum(vlrcusto)/3 as CMV90
                from totaldia where data >= current_date -90
                and data < current_date";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function top10Estoque(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT 
                f.descricao,
                sum(vlrcustomedio) as CustoMedio,
                sum(vlrcustomedio) * 100/ (select sum(vlrcustomedio) from posicaoestoques where data = current_date -1 and cdsubgrupo <> 28) Part
                from posicaoestoques p, pdv_filiais f
                where p.cdloja = f.cdloja
                and data = current_date -1
                and p.cdsubgrupo <> 28
                group by 1
                order by 3 desc limit 10";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }   

    public function top10Vendas(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                f.descricao,
                sum(vlrliquido) as Venda
                from totaldialoja p, pdv_filiais f
                where p.cdloja = f.cdloja
                and mes =extract(month from current_date)
                group by 1
                order by 2 desc limit 10";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }   

    public function top10diasEstoque(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT 
                p.cdloja,
                f.descricao,
                sum(vlrcusto) as CustoReposição,
                (select sum(vlrcusto) from totaldialoja l where l.cdloja = p.cdloja and l.data >=current_date-31) as CMV30,
                cast(sum(vlrcusto)/(select sum(vlrcusto) from totaldialoja l where l.cdloja = p.cdloja and l.data >=current_date-31)*31 as Integer) as DiasEstoque
                from posicaoestoques p, pdv_filiais f
                where p.cdloja = f.cdloja
                and data =current_date -1
                and p.cdsubgrupo <> 28
                group by 1,2
                order by 5 asc limit 10";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }  
    
    public function top10diasEstoque2(){

        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT
                p.cdloja,
                f.descricao,
                sum(vlrcusto) as CustoReposição,
                (select sum(vlrcusto) from totaldialoja l where l.cdloja = p.cdloja and l.data >=current_date-31) as CMV30,
                cast(sum(vlrcusto)/(select sum(vlrcusto) from totaldialoja l where l.cdloja = p.cdloja and l.data >=current_date-31)*31 as Integer) as DiasEstoque
                from posicaoestoques p, pdv_filiais f
                where p.cdloja = f.cdloja
                and data = current_date -1
                and p.cdsubgrupo <> 28
                and p.cdloja <> 200
                group by 1,2
                order by 5 desc limit 10";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }

    public function resumoOperacao(){

        $Postgre = $this->load->database('postgre', TRUE);

        $data = date('01.m.Y');
        $sql = "SELECT 
                case when Tiponota = 'C' then 'Compra'
                when TipoNota = 'T' then 'Transferencia'
                when TipoNota = 'B' then 'Bonificacao'
                else 'Outras' end as Operacao,
                SUM(vlrproduto+vlricmsst-vlrdesconto) as Valores
                from dados_b2b dbb
                where dtEntrada >= '{$data}'
                and OPERACAO = 'E'
                group by 1";

        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function vendaAcumulada(){
        
        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT sum(vlrliquido) as liquido
                from totaldia t
                where mes = extract(month from current_date)
                and ano = extract(year from current_date)";
        
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function filiaisDesatualizadasEstoque(){
        
        $Postgre = $this->load->database('postgre', TRUE);

        $sql = "SELECT descricao from pdv_filiais where cdloja not in (select cdloja from posicaoestoques where data = current_date-1)
                and cdloja <> 998";
        
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function top10Itens_compra(){
        
        $Postgre = $this->load->database('postgre', TRUE);
        
        $data = date('01.m.Y');
        $sql = "SELECT
                p.descricao,
                sum(totalcompra) as Valor
                from totalcompraprod d, pdv_produtos p
                where data >= '{$data}'
                and d.cdproduto = p.cdproduto
                group by 1
                order by 2 desc
                limit 10";
                
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function top10Fabricantes_compra(){
        
        $Postgre = $this->load->database('postgre', TRUE);
        
        $data = date('01.m.Y');
        $sql = "SELECT
                f.descricao,
                sum(totalcompra) as Valor
                from totalcompraprod d, pdv_produtos p, pdv_fabricante f 
                where data >= '{$data}'
                and d.cdproduto = p.cdproduto
                and p.cdfabricante = f.cdfabricante 
                group by 1
                order by 2 desc
                limit 10";
        
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function top10Fornecedores_compra(){
        
        $Postgre = $this->load->database('postgre', TRUE);
        
        $data = date('01.m.Y');
        $sql = "SELECT
                f.descricao,
                sum(d.totalcompra) as Valor
                from totalcomprafornecedor d, pdv_fornecedores f 
                where data >= '{$data}'
                and d.cdfornecedor = f.cdfornecedor        
                group by 1
                order by 2 desc
                limit 10";
                
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function top10Categorias_compra(){
        
        $Postgre = $this->load->database('postgre', TRUE);
        
        $data = date('01.m.Y');
        $sql = "SELECT
                f.descricao,
                sum(totalcompra) as Valor
                from totalcompraprod d, pdv_produtos p, pdv_categoria f 
                where data >= '{$data}'
                and d.cdproduto = p.cdproduto
                and p.cdgrupo = f.cdgrupo
                and p.cdsubgrupo = f.cdsubgrupo
                group by 1
                order by 2 desc
                limit 10";
                        
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    } 

    public function vendaXcompra(){
        
        $Postgre = $this->load->database('postgre', TRUE);
        
        $dataInicio = date('01.m.Y');
        $dataFinal = date('d.m.Y');
        $sql = "SELECT
                data,
                SUM(totalcompra) as Compras,
                (select sum(vlrliquido) from totaldia t where data = t2.data) as Venda
                from totalcomprafornecedor t2 
                where data between '{$dataInicio}' and '{$dataFinal}'     
                group by 1
                order by 1 asc";
                
        return $Postgre->query($sql)->result();
        $this->$Postgre->close();
    }
    
    /* Dashboard TI */

    /* Cards */
    public function countChamados($dataInicio, $dataFinal) {

        if($dataInicio != '') {

            $dataIni = date('Y.m.d 00:00:00', strtotime($dataInicio));
            $dataFin = date('Y.m.d 23:59:59', strtotime($dataFinal));
        
            $where = "AND created_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        } else {
          
            $dataIni = date('Y.m.d 00:00:00');
            $dataFin = date('Y.m.d 23:59:59');

            $where = "AND created_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        }

        $sql = "SELECT COUNT(id) as resultado from tickets t where t.ticket_type_id = 1 $where";

        $res = $this->db->query($sql);
        return $res->result();

    }

    public function countFinalizados($dataInicio, $dataFinal) {
        
        if($dataInicio != '') {

            $dataIni = date('Y.m.d 00:00:00', strtotime($dataInicio));
            $dataFin = date('Y.m.d 23:59:59', strtotime($dataFinal));
        
            $where = "AND closed_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        } else {
          
            $dataIni = date('Y.m.d 00:00:00');
            $dataFin = date('Y.m.d 23:59:59');

            $where = "AND closed_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        }


        $sql = "SELECT COUNT(id) as resultado from tickets t where t.status = 'closed' and t.ticket_type_id = 1 $where";
        $res = $this->db->query($sql);
        return $res->result();   
    }

    public function countRespondidos($dataInicio, $dataFinal) {
        
        if($dataInicio != '') {

            $dataIni = date('Y.m.d 00:00:00', strtotime($dataInicio));
            $dataFin = date('Y.m.d 23:59:59', strtotime($dataFinal));
        
            $where = "AND last_activity_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        } else {
          
            $dataIni = date('Y.m.d 00:00:00');
            $dataFin = date('Y.m.d 23:59:59');

            $where = "AND last_activity_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        }


        $sql = "SELECT COUNT(id) as resultado from tickets t where t.status = 'client_replied' or t.status = 'open' and t.ticket_type_id = 1 $where";
        $res = $this->db->query($sql);
        return $res->result();   
    }

    public function countEmAberto($dataInicio, $dataFinal) {
        
        if($dataInicio != '') {

            $dataIni = date('Y.m.d 00:00:00', strtotime($dataInicio));
            $dataFin = date('Y.m.d 23:59:59', strtotime($dataFinal));
        
            $where = "AND last_activity_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        
        } else {
          
            $dataIni = date('Y.m.d 00:00:00');
            $dataFin = date('Y.m.d 23:59:59');

            $where = "AND last_activity_at BETWEEN '{$dataIni}' AND '{$dataFin}'";
        }

        $sql = "SELECT COUNT(id) as resultado from tickets t where t.ticket_type_id = 1 and t.status = 'new'  or t.status = 'client_replied' or t.status = 'open' $where";
        $res = $this->db->query($sql);
        return $res->result();
    }

    /* Gráfico */
    public function graficoChamados($dataIni, $dataFin) { /* William */
        $dataInicial = date('Y.m.01 00:00:00', strtotime($dataIni));
        $dataFinal = date('Y.m.d 23:59:59', strtotime($dataFin));        
        
        $sql = "SELECT count(id) as fechados
                FROM tickets 
                WHERE status = 'closed' AND ticket_type_id = 1 AND assigned_to = 3 AND closed_at BETWEEN '$dataInicial' AND '$dataFinal'";

        $res = $this->db->query($sql);
        return $res->result();
    }

    public function graficoChamadosDois($dataIni, $dataFin){ /* Antonio */
        $dataInicial = date('Y.m.01 00:00:00', strtotime($dataIni));
        $dataFinal = date('Y.m.d 23:59:59', strtotime($dataFin));        
        
        $sql = "SELECT count(id) as fechados
                FROM tickets 
                WHERE status = 'closed' AND ticket_type_id = 1 AND assigned_to = 22 AND closed_at BETWEEN '$dataInicial' AND '$dataFinal'";

        $res = $this->db->query($sql);
        return $res->result();    
    }
   
    public function graficoChamadosTres($dataIni, $dataFin){ /* Jorge */
        $dataInicial = date('Y.m.01 00:00:00', strtotime($dataIni));
        $dataFinal = date('Y.m.d 23:59:59', strtotime($dataFin));        
        
        $sql = "SELECT count(id) as fechados
                FROM tickets 
                WHERE status = 'closed' AND ticket_type_id = 1 AND assigned_to = 91 AND closed_at BETWEEN '$dataInicial' AND '$dataFinal'";

        $res = $this->db->query($sql);
        return $res->result();    
    }
    
    public function graficoChamadosQuatro($dataIni, $dataFin){ /* Lucas */
        
        $dataInicial = date('Y.m.01 00:00:00', strtotime($dataIni));
        $dataFinal = date('Y.m.d 23:59:59', strtotime($dataFin));

        $sql = "SELECT count(id) as fechados
                FROM tickets 
                WHERE status = 'closed' AND ticket_type_id = 1 AND assigned_to = 239 AND closed_at BETWEEN '$dataInicial' AND '$dataFinal'";

        $res = $this->db->query($sql);      
        
        return $res->result();    
    }
 
    public function lineChartTI($var){
        $dataInicial = date('Y.m.01 00:00:00');
        $dataFinal = date('Y.m.d 23:59:59');      

        switch($var){
            case 'Todos':
                $where = "";
                break;

            case 'William':
                $where = "AND assigned_to = 3";
                break;

            case 'Antonio':
                $where = "AND assigned_to = 22";
                break;
            
            case 'Jorge':
                $where = "AND assigned_to = 91";
                break;

            case 'Lucas':
                $where = "AND assigned_to = 239";
                break;   
        }

        $sql = "SELECT 
                    DATE_FORMAT(closed_at, '%d') AS dia, COUNT(id) AS fechados
                FROM
                    tickets
                WHERE
                    STATUS = 'closed' $where AND closed_at BETWEEN '$dataInicial' AND '$dataFinal' 
                    GROUP BY dia";      
        
        $res = $this->db->query($sql);
        return $res->result();

    }
}
