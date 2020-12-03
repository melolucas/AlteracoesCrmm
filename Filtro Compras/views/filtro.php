<style>
    .bordas {
        min-height: 100px;
        box-shadow: 0px 0px 0px 1px #CDCDCD; 
        border-radius: 5px;
    }
</style>

<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

<div class="container bordas" style="background-color: white; margin-top: 2%">
    <div class="container" style="margin-top: 2%; background-color: white; width: 70%">
        <form method="POST" action="<?php echo base_url('index.php/filtro');?>">
            <!-- Envia pro controller -->
            <!-- Definir periodo -->
            <div class="container-fluid"> 
                <table class="table table-borderless">                   
                    <tbody>
                        <tr>
                            <!-- <th>Período:</th> -->
                            <td><input type="text" class="form-control" placeholder="Data de início" style="text-align: center;" onkeypress="mascaraData(this)"></td>
                            <td><input type="text" class="form-control" placeholder="Data final" style="text-align: center;" onkeypress="mascaraData(this)"></td>
                        </tr>    
                    </tbody>
                </table>
            </div>

            <!-- Inicio do filtro -->
            <div class="container-fluid">
                <table class="table table-borderless">
                    <!-- Cabeçalho -->
                    <thead class="table-dark">
                        <div class="row">
                            <tr>
                                <div class="col-2">
                                    <th scope="col"></th>
                                </div>

                                <div class="col-7">
                                    <th scope="col" style="text-align: center; width: 20%">Código</th>
                                </div>

                                <div class="col-3">
                                    <th scope="col" style="text-align: center; width: 70%">Nome</th>
                                </div>
                            </tr>
                        </div>
                    </thead>

                    <tbody>
                        <!-- Produtos -->
                        <tr>
                            <th scope="row">Produto:</th>
                            <div class="col-5">
                                <td><input id="cdproduto" name="cdproduto" class="form-control"></td>
                            </div>
                            <div class="col-7">
                                <td><input oninput="handleInput(event)" id="nmProduto" name="nmProduto" type="text" class="form-control uppercase"></td>
                            </div>
                        </tr>
                        <!-- Fornecedores -->
                        <tr>
                            <th scope="row">Fornecedor:</th>
                            <td><input id="cdfornecedor" name="cdfornecedor" class="form-control"></td>
                            <td><input oninput="handleInput(event)" id="nmfornecedor" name="nmfornecedor" type="text" class="form-control"></td>
                        </tr>
                        <!-- Fabricantes -->
                        <tr>
                            <th scope="row">Fabricante:</th>
                            <td><input id="cdfabricante" name="cdfabricante" type="text" class="form-control"></td>
                            <td><input oninput="handleInput(event)" id="nmfabricante" name="nmfabricante" type="text" class="form-control"></td>
                        </tr>
                        <!-- Linhas -->
                        <tr>
                            <th scope="row">Linha:</th>
                            <td><input id="cdlinha" name="cdlinha" type="text" class="form-control"></td>
                            <td><input oninput="handleInput(event)" id="nmlinha" name="nmlinha" type="text" class="form-control"></td>
                        </tr>
                        <!-- Categorias -->
                        <tr>
                            <th scope="row">Categoria:</th>
                            <td><input id="cdcategoria" name="cdcategoria" type="text" class="form-control"></td>
                            <td><input oninput="handleInput(event)" id="nmcategoria" name="nmcategoria" type="text" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <!-- Submit do formulário -->
            <div class="col-12" style="margin-top: 1%; text-align: center; padding-bottom: 2%; margin-top: -1%">
                <button id="btnfiltro" name="submit" value="submit" type="button" onclick="alert('Não ta pronto panaca!')" class="btn btn-primary" style="border-radius: 5px">Filtrar</button>
            </div>
        </form>
    </div>
</div>

<script type="text/javascript">

/* Deixar os inputs em maiúsculo */
function handleInput(e) {
   var ss = e.target.selectionStart;
   var se = e.target.selectionEnd;
   e.target.value = e.target.value.toUpperCase();
   e.target.selectionStart = ss;
   e.target.selectionEnd = se;
}

/* Mascara do input de data */
function mascaraData(val) {
  var pass = val.value;
  var expr = /[0123456789]/;

  for (i = 0; i < pass.length; i++) {
    // charAt -> retorna o caractere posicionado no índice especificado
    var lchar = val.value.charAt(i);
    var nchar = val.value.charAt(i + 1);

    if (i == 0) {
      // search -> retorna um valor inteiro, indicando a posição do inicio da primeira
      // ocorrência de expReg dentro de instStr. Se nenhuma ocorrencia for encontrada o método retornara -1
      // instStr.search(expReg);
      if ((lchar.search(expr) != 0) || (lchar > 3)) {
        val.value = "";
      }

    } else if (i == 1) {

      if (lchar.search(expr) != 0) {
        // substring(indice1,indice2)
        // indice1, indice2 -> será usado para delimitar a string
        var tst1 = val.value.substring(0, (i));
        val.value = tst1;
        continue;
      }

      if ((nchar != '.') && (nchar != '')) {
        var tst1 = val.value.substring(0, (i) + 1);

        if (nchar.search(expr) != 0)
          var tst2 = val.value.substring(i + 2, pass.length);
        else
          var tst2 = val.value.substring(i + 1, pass.length);

        val.value = tst1 + '.' + tst2;
      }

    } else if (i == 4) {

      if (lchar.search(expr) != 0) {
        var tst1 = val.value.substring(0, (i));
        val.value = tst1;
        continue;
      }

      if ((nchar != '.') && (nchar != '')) {
        var tst1 = val.value.substring(0, (i) + 1);

        if (nchar.search(expr) != 0)
          var tst2 = val.value.substring(i + 2, pass.length);
        else
          var tst2 = val.value.substring(i + 1, pass.length);

        val.value = tst1 + '.' + tst2;
      }
    }

    if (i >= 6) {
      if (lchar.search(expr) != 0) {
        var tst1 = val.value.substring(0, (i));
        val.value = tst1;
      }
    }
  }

  if (pass.length > 10)
    val.value = val.value.substring(0, 10);
  return true;
}

/* Autocomplete produtos */
$("#nmProduto").autocomplete({
    minLength: 3,
    source: 
    function(request, add){
        $.ajax({
            url: "<?php echo base_url('index.php/filtro/autoCompletarNmProdutos/');?>",
            dataType: 'json',
            type: 'POST',
            data: {nmProduto:request.term},
            success:
            function(data){
                if(data.response =="true"){
                    add(data.message);
                }
            }
        });
    }    
});

/* Autocomplete fornecedores */
$("#nmfornecedor").autocomplete({
    minLength: 2,
    source: 
    function(request, add){
        $.ajax({
            url: "<?php echo base_url('index.php/filtro/autoCompletarnmfornecedor/');?>",
            dataType: 'json',
            type: 'POST',
            data: {nmfornecedor:request.term},
            success:    
            function(data){
                if(data.response =="true"){
                    add(data.message);
                }
            },
        });
    }    
});

/* Autocomplete fabricantes */
$("#nmfabricante").autocomplete({
    minLength: 2,
    source: 
    function(request, add){
        $.ajax({
            url: "<?php echo base_url('index.php/filtro/autoCompletarnmfabricante/');?>",
            dataType: 'json',
            type: 'POST',
            data: {nmfabricante:request.term},
            success:    
            function(data){
                if(data.response =="true"){
                    add(data.message);
                }
            },
        });
    }    
});

/* Autocomplete linhas */
$("#nmlinha").autocomplete({
    minLength: 2,
    source: 
    function(request, add){
        $.ajax({
            url: "<?php echo base_url('index.php/filtro/autoCompletanmlinha/');?>",
            dataType: 'json',
            type: 'POST',
            data: {nmlinha:request.term},
            success:    
            function(data){
                if(data.response =="true"){
                    add(data.message);
                }
            },
        });
    }    
});

/* Autocomplete categorias */
$("#nmcategoria").autocomplete({
    minLength: 2,
    source:
    function(request, add){
        $.ajax({
            url: "<?php echo base_url('index.php/filtro/autoCompletanmcategoria/');?>",
            dataType: 'json',
            type: 'POST',
            data: {nmcategoria:request.term},
            success:
            function(data){
                if(data.response =="true"){
                    add(data.message);
                }
            },
        });
    }
});

/*                     PREENCHER CAMPOS VAZIOS:                         */

var cdproduto = $('#cdproduto').val();   

/* Se o cdProduto estiver vazio ele autocompleta */
$("#nmProduto").on("focus", function() {
    $("#nmProduto").on("focusout", function(data) {
        var nmproduto = $('#nmProduto').val();

        if(cdproduto === '' || cdproduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheCdProduto');?>",
                dataType: 'json',
                type: 'POST',
                data: {prProdutos:nmproduto},
                success:
                function(data){
                    if(data.response == "true") {                                   
                        $("#cdproduto").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o nmProduto estiver vazio ele autocompleta */
var checkNmProduto = $('#nmProduto').val(); 
$("#cdproduto").on("focus", function() {
    $("#cdproduto").on("focusout", function(data) {
        var cdproduto = $('#cdproduto').val();

        if(checkNmProduto === '' || checkNmProduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheNomeProduto');?>",
                dataType: 'json',
                type: 'POST',
                data: {cdProduto:cdproduto},
                success:
                function(data){
                    if(data.response == "true") {                                    
                        $("#nmProduto").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o cdFornecedor estiver vazio ele autocompleta */
$("#nmfornecedor").on("focus", function() {
    $("#nmfornecedor").on("focusout", function(data) {
        var nmfornecedor = $('#nmfornecedor').val();

        if(cdproduto === '' || cdproduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheCdFornecedor');?>",
                dataType: 'json',
                type: 'POST',
                data: {nmFornecedor:nmfornecedor},
                success:
                function(data){
                    if(data.response == "true") {                                     
                        $("#cdfornecedor").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o nmFornecedor estiver vazio ele autocompleta */
var checkNmFornecedor = $('#nmfornecedor').val(); 
$("#cdfornecedor").on("focus", function() {
    $("#cdfornecedor").on("focusout", function(data) {
        var cdfornecedor = $('#cdfornecedor').val();

        if(checkNmProduto === '' || checkNmProduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheNomeFornecedor');?>",
                dataType: 'json',
                type: 'POST',
                data: {cdfornecedores:cdfornecedor},
                success:
                function(data){
                    if(data.response == "true") {                                     
                        $("#nmfornecedor").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o cdFabricante estiver vazio ele autocompleta */
$("#nmfabricante").on("focus", function() {
    $("#nmfabricante").on("focusout", function(data) {
        var nmFabricante = $('#nmfabricante').val();

        if(cdproduto === '' || cdproduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheCdFabricante');?>",
                dataType: 'json',
                type: 'POST',
                data: {nmFabricantes:nmFabricante},
                success:
                function(data){
                    if(data.response == "true") {                                      
                        $("#cdfabricante").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o nmFabricante estiver vazio ele autocompleta */
var checkNmFabricante = $('#nmfabricante').val(); 
$("#cdfabricante").on("focus", function() {
    $("#cdfabricante").on("focusout", function(data) {
        var cdfabricante = $('#cdfabricante').val();

        if(checkNmFabricante === '' || checkNmFabricante === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheNomefabricante');?>",
                dataType: 'json',
                type: 'POST',
                data: {cdFabricantes:cdfabricante},
                success:
                function(data){
                    if(data.response == "true") {                                    
                        $("#nmfabricante").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o cdLinha estiver vazio ele autocompleta */
$("#nmlinha").on("focus", function() {
    $("#nmlinha").on("focusout", function(data) {
        var nmlinha = $('#nmlinha').val();

        if(cdproduto === '' || cdproduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheCdlinha');?>",
                dataType: 'json',
                type: 'POST',
                data: {nmLinhas:nmlinha},
                success:
                function(data){
                    if(data.response == "true") {                                   
                        $("#cdlinha").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o nmLinha estiver vazio ele autocompleta */
var checkNmLinha = $('#nmlinha').val(); 
$("#cdlinha").on("focus", function() {
    $("#cdlinha").on("focusout", function(data) {
        var cdlinha = $('#cdlinha').val();

        if(checkNmLinha === '' || checkNmLinha === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheNomeLinha');?>",
                dataType: 'json',
                type: 'POST',
                data: {cdLinhas:cdlinha},
                success:
                function(data){
                    if(data.response == "true") {                                      
                        $("#nmlinha").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o cdCategoria estiver vazio ele autocompleta */
$("#nmcategoria").on("focus", function() {
    $("#nmcategoria").on("focusout", function(data) {
        var nmcategoria = $('#nmcategoria').val();

        if(cdproduto === '' || cdproduto === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheCdCategoria');?>",
                dataType: 'json',
                type: 'POST',
                data: {nmCategorias:nmcategoria},
                success:
                function(data){
                    if(data.response == "true") {                                     
                        $("#cdcategoria").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})

/* Se o nmCategoria estiver vazio ele autocompleta */
var checkNmCategoria = $('#nmcategoria').val(); 
$("#cdcategoria").on("focus", function() {
    $("#cdcategoria").on("focusout", function(data) {
        var cdcategoria = $('#cdcategoria').val();

        if(checkNmCategoria === '' || checkNmCategoria === null) {
            $.ajax({
                url: "<?php echo base_url('index.php/filtro/preencheNomeCategoria');?>",
                dataType: 'json',
                type: 'POST',
                data: {cdCategorias:cdcategoria},
                success:
                function(data){
                    if(data.response == "true") {                                    
                        $("#nmcategoria").val(data.message[0].value);                            
                    }
                }
            });
        } 
    })
})
</script>
