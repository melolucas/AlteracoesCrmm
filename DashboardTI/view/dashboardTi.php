<style>
    i.fa.fa-percent {
        font-size: 70px !important;
    }
    div#efeito.col-md-3 {
        transition: all 0.5s;
        cursor: pointer;
    }
    div#efeito.col-md-3:hover{
        -webkit-filter: drop-shadow(10px 5px 5px rgba(0,0,0,.3));
        filter: drop-shadow(10px 5px 5px rgba(0,0,0,.3));
    }
</style>

<div id="page-content" class="p20 clearfix dashboard-view">
    <?php $this->load->view("dashboards/dashboard_header"); ?>
    <?php $this->load->view("dashboards/helper_js"); ?>
</div>
<div style="margin-top: -75px">
    <h4> Dashboard TI </h4>
</div><br>

<div class="container" style="background-color: white; width: 90%; margin-top: 1%">
    <center>
        <div class="container" style="margin-top: 1%;">
            <span>
                <form method="post" action="<?php echo get_uri('Dashboard/dashboardTi/'); ?>"> <!-- Usar um método para tudo no controller -->
                    <div class="col-md-3" style="text-align: right;">
                        <h4>Período:</h4>
                    </div>

                    <div class="col-md-3">                       
                        <input id="dataInicial" onkeyup="formatar(this,'##.##.####',event)" name="dataInicial" class="form-control datetimepicker" value="<?php if($dataInicio) { echo $dataInicio;}; ?>" placeholder="Data inicial" type="text" required style="text-align: center">
                    </div>

                    <div class="col-md-3">
                        <input id="dataFinal" onkeyup="formatar(this,'##.##.####',event)"  name="dataFinal" class="form-control datetimepicker" value="<?php if($dataFinal) { echo $dataFinal;}; ?>" placeholder="Data final" type="text" required style="text-align: center">
                    </div>

                    <div class="col-md-3" style="text-align: left;">
                        <button id="btnBuscar" type="submit" class="btn btn-primary btn-block" style="border-radius: 5px; width: 50%"> Definir Período </button>
                    </div>
                </form>
            </span>
        </div>
    </center>

    <center>
        <div class="row" style="margin-top: 1%;">
            <div class="col-md-12">
                <span>                
                    <!-- Quantidade de Chamados(Total) -->
                    <div class="col-md-3">
                        <div class="" id="efeito">
                            <div class="panel panel-primary">
                                <div class="panel-body ">
                                    <div class="widget-icon">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="widget-details" style="margin-top: 10px">
                                        <?php echo lang("dashboardTIAbertos"); ?>           

                                        <h1><?php echo $quantidade[0]->resultado; ?></h1>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chamados Finalizados -->
                    <div class="col-md-3">
                        <div class="" id="efeito">
                            <div class="panel panel-primary">
                                <div class="panel-body ">
                                    <div class="widget-icon">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="widget-details" style="margin-top: 10px">
                                        <?php echo lang("dashboardTIFinalizados"); ?>
                                        <h1><?php echo $quantidadeFinalizados[0]->resultado; ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chamados Respondidos -->
                    <div class="col-md-3">
                        <div class="" id="efeito">
                            <div class="panel panel-primary">
                                <div class="panel-body ">
                                    <div class="widget-icon">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="widget-details" style="margin-top: 10px">
                                        <?php echo lang("dashboardTIRespondidos"); ?>
                                        <h1><?php echo $quantidadeRespondidos[0]->resultado; ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Chamados em Aberto -->
                    <div class="col-md-3">
                        <div class="" id="efeito">
                            <div class="panel panel-primary red">
                                <div class="panel-body ">
                                    <div class="widget-icon">
                                        <i class="fa fa-comments"></i>
                                    </div>
                                    <div class="widget-details" style="margin-top: 10px">
                                        <?php echo lang("dashboardTIEmAberto"); ?>
                                        <h1><?php echo $quantidadeEmAberto[0]->resultado; ?></h1>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </span>
            </div>
        </div>
    </center>        
</div>

<!-- ranking -->
<div class="container" style="margin-top: 2%;">
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-3" id="efeito">
                <div class="thumbnail" style="box-shadow: 0px 0px 1px 1px #D9D9F3; height: 200px; margin-top: 10px">
                    <div class="caption" style="margin-left: 10px; margin-top: -20px; background-color: #1ccacc; width: 110px; border-radius: 2px; color: #FFFFFF">
                        <center><h1><i class="fa fa-trophy" aria-hidden="true"></i></h1></center>
                    </div>                
                    <div class="card-body">
                        <center>
                            <h3 style="margin-top: 20px">
                                <div>
                                    <?php if($ranking[0]->assigned_to == 3) echo '1º - William'; ?>
                                    <?php if($ranking[0]->assigned_to == 22) echo '1º - Antônio'; ?>
                                    <?php if($ranking[0]->assigned_to == 91) echo '1º - Jorge'; ?>
                                    <?php if($ranking[0]->assigned_to == 239) echo '1º - Lucas Melo'; ?>
                                </div>
                            </h3>
                            <h2>
                                <div>
                                    <?php echo $ranking[0]->rank; ?>
                                </div>
                            </h2>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-md-3" id="efeito">
                <div class="thumbnail" style="box-shadow: 0px 0px 1px 1px #D9D9F3; height: 200px; margin-top: 10px">
                    <div class="caption" style="margin-left: 10px; margin-top: -20px; background-color: #1ccacc; width: 110px; border-radius: 2px; color: #FFFFFF">
                        <center><h1><i class="fa fa-trophy" aria-hidden="true"></i></h1></center>
                    </div>             
                    <div class="card-body">
                        <center>
                            <h3 style="margin-top: 20px">
                                <div>
                                    <?php if($ranking[1]->assigned_to == 3) echo '2º - William'; ?>
                                    <?php if($ranking[1]->assigned_to == 22) echo '2º - Antônio'; ?>
                                    <?php if($ranking[1]->assigned_to == 91) echo '2º - Jorge'; ?>
                                    <?php if($ranking[1]->assigned_to == 239) echo '2º - Lucas Melo'; ?>
                                </div>
                            </h3>
                            <h2>
                                <div>
                                    <?php echo $ranking[1]->rank; ?>
                                </div>
                            </h2>
                        </center>
                    </div>
                </div>
            </div>
            <div class="col-md-3" id="efeito">
                <div class="thumbnail" style="box-shadow: 0px 0px 1px 1px #D9D9F3; height: 200px; margin-top: 10px">
                    <div class="caption" style="margin-left: 10px; margin-top: -20px; background-color: #1ccacc; width: 110px; border-radius: 2px; color: #FFFFFF">
                        <center><h1><i class="fa fa-trophy" aria-hidden="true"></i></h1></center>
                    </div>        
                    <div class="card-body">
                        <center>
                            <h3 style="margin-top: 20px">
                                <div>
                                    <?php if($ranking[2]->assigned_to == 3) echo '3º - William'; ?>
                                    <?php if($ranking[2]->assigned_to == 22) echo '3º - Antônio'; ?>
                                    <?php if($ranking[2]->assigned_to == 91) echo '3º - Jorge'; ?>
                                    <?php if($ranking[2]->assigned_to == 239) echo '3º - Lucas Melo'; ?>
                                </div>
                            </h3>
                            <h2>
                                <div>
                                    <?php echo $ranking[2]->rank; ?>
                                </div>
                            </h2>
                        </center>
                    </div>
                </div>
            </div>    
            <div class="col-md-3" id="efeito">
                <div class="thumbnail" style="box-shadow: 0px 0px 1px 1px #D9D9F3; height: 200px; margin-top: 10px">
                    <div class="caption" style="margin-left: 10px; margin-top: -20px; background-color: #1ccacc; width: 110px; border-radius: 2px; color: #FFFFFF">
                        <center><h1><i class="fa fa-trophy" aria-hidden="true"></i></h1></center>
                    </div>
                    <div class="card-body">
                        <center>
                            <h3 style="margin-top: 20px">
                                <div>
                                    <?php if($ranking[3]->assigned_to == 3) echo '4º - William'; ?>
                                    <?php if($ranking[3]->assigned_to == 22) echo '4º - Antônio'; ?>
                                    <?php if($ranking[3]->assigned_to == 91) echo '4º - Jorge'; ?>
                                    <?php if($ranking[3]->assigned_to == 239) echo '4º - Lucas Melo'; ?>
                                </div>
                            </h3>
                            <h2>
                                <div>
                                    <?php echo $ranking[3]->rank; ?>
                                </div>
                            </h2>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Gráfico de chamados -->
<div class="container" style="margin-top: 1%; background-color: white; padding-top: 1%"> 
    <center>
        <div class="row" >
            <div class="col-md-6" >                
                <div class="" id="graficoUm">
                    <div class="input-group"> <!-- Form -->
                        <form id="formUm">
                            <select class="form-control" id="mesGrafico" name="mesGrafico" style="width: 62%;">
                                <option value="" disabled selected>---</option>
                                <option value="01">Janeiro</option>
                                <option value="02">Fevereiro</option>
                                <option value="03">Março</option>
                                <option value="04">Abril</option>
                                <option value="05">Maio</option>
                                <option value="06">Junho</option>
                                <option value="07">Julho</option>
                                <option value="08">Agosto</option>
                                <option value="09">Setembro</option>
                                <option value="10">Outubro</option>
                                <option value="11">Novembro</option>
                                <option value="12">Dezembro</option>
                            </select>
                            <button type="button" id="btnMes" class="btn btn-primary" style="width: 38%; margin-top: 0.5px; text-align: center">Buscar</button>
                        </form>
                    </div>
                
                    <div class="panel panel-default"> <!-- Gráfico -->
                        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="bar-chart" width="800" height="450"></canvas>
                        </div>
                    </div><br>
                    
                    <script>
                        // Bar chart
                        new Chart(document.getElementById("bar-chart"), {
                            type: 'bar',
                            data: {
                            labels: ["William", "Antônio", "Jorge", "Lucas"],
                            datasets: [
                                {
                                label: "Chamados fechados",
                                backgroundColor: ["#3e95cd", "#8e5ea2","#3cba9f","#ffbc40"],
                                data: [
                                    <?php 
                                    foreach ($graficoWillian as $data) {
                                        echo $data->fechados;
                                    }    
                                    ?>,
                                    <?php 
                                    foreach ($graficoAntonio as $data) {
                                        echo $data->fechados;
                                    }    
                                    ?>,
                                    <?php 
                                    foreach ($graficoJorge as $data) {
                                        echo $data->fechados;
                                    }    
                                    ?>,
                                    <?php 
                                    foreach ($graficoLucas as $data) {
                                        echo $data->fechados;
                                    }    
                                    ?>
                                ]
                                }
                            ]
                            },
                            options: {
                            legend: { display: false },
                            title: {
                                display: true,
                                text: 'Total de chamados fechados no mês: (<?php echo $mesGraf; ?>)'
                            }
                            }
                        });
                    </script>

                    <!-- Refresh na div -->
                    <script>
                        $(document).ready(function(){
                            $("#btnMes").on("click", function() {
                                var mesGrafico = $('#mesGrafico').val();            

                                $.ajax({
                                    url: "<?php echo base_url('index.php/Dashboard/dashboardTi');?>",               
                                    type: 'POST',
                                    data: {mesgrafico:mesGrafico},
                                    cache:false                              
                                }).done(function(dados){
                                    $("#graficoUm").html($(dados).find("#graficoUm"));                
                                })
                            })
                        })
                    </script>                    
                </div>
            </div>
           
            <div class="col-md-6">              
                <div id="graficoDois">
                    <div class="input-group">
                        <form id="formDois">
                            <select class="form-control" id="fechadosForm" name="fechadosForm" style="width: 62%;">
                                <option value="Todos">Todos</option>
                                <option value="William">William</option>
                                <option value="Antonio">Antônio</option>
                                <option value="Jorge">Jorge</option>
                                <option value="Lucas">Lucas</option>
                            </select>
                            <button type="button" id="btnFechados" class="btn btn-primary" style="width: 38%; margin-top: 0.5px; text-align: center">Buscar</button>
                        </form>
                    </div>

                    <div class="panel panel-default">
                        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="line-chart" width="800" height="450"></canvas>
                        </div>
                    </div>
                    <br>
                    
                    <script>          
                        // Line chart
                        new Chart(document.getElementById("line-chart"), {
                        type: 'line',
                        data: {
                            labels: [ <?php foreach ($line_chart as $data) { echo ($data->dia) . ", "; } ?> ],            
                            datasets: [{ 
                                data: [<?php foreach ($line_chart as $data) { echo ($data->fechados) . ", "; } ?>],
                                label: "<?php if($nmFechados) { echo $nmFechados;}; ?>",
                                borderColor: "#3e95cd",
                                fill: false
                            }
                            ]
                        },
                        options: {
                            title: {
                            display: true,
                            text: 'Chamados fechados:'
                            }
                        }
                        });  
                    </script>                     
                    
                    <!-- Refresh na div -->
                    <script> /* Gráfico dois */
                        $(document).ready(function(){
                            $("#btnFechados").on("click", function() {
                                setTimeout( function() {
                                    var fechadosForm = $('#fechadosForm').val();            

                                    $.ajax({
                                        url: "<?php echo base_url('index.php/Dashboard/dashboardTi');?>",               
                                        type: 'POST',
                                        data: {fechadosform:fechadosForm},
                                        cache:false                          
                                    }).done(function(dados){
                                        $("#graficoDois").html($(dados).find("#graficoDois"));                
                                    })  
                                }, 1000);           
                            })
                        })
                    </script>
                </div>
            </div>            
        </div>
    </center> 
</div>

<script>
    /* Mascara data */
    function formatar(src, mask,e) {
        var tecla =""
        if (document.all)
            tecla = event.keyCode;
        else
            tecla = e.which;        
        if(tecla != 8){

            if (src.value.length == src.maxlength){
                return;
            }
            var i = src.value.length;
            var saida = mask.substring(0,1);
            var texto = mask.substring(i);
            if (texto.substring(0,1) != saida)
            {
                src.value += texto.substring(0,1);
            }
        }
    }             
</script>
