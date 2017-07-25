<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <!--    <script src="assets/js/bootstrap.min.js"></script>-->
    <title>KNN: implementação em PHP</title>
</head>
<body>
<div class="container">
    <h1>A k-Nearest Neighbors (kNN) implementation in PHP</h1>
    <p>
        Projeto implementado para compor a 2ª nota da disciplina de Inteligência Artificial - UFMA

        Implementação do Algoritmo k-Nearest Neighbors (kNN) segundo o paradigma Orientado a Objetos e usando a linguagem PHP
    </p>
    <a class="btn btn-block btn-social btn-github" href="https://github.com/SamirSouzaSys/UFMA_InteligenciaArtificial_20171" target="_blank"> Project on GitHUB
    </a>
</div>
<div class="container">
    <hr>
</div>
<div class="container">
    <form name="dataSetsAvaliable">
        <div class="form-group">
            <label for="datasetSelected">Selecione a base que deseja usar para testar o KNN aqui implementado</label>
            <select id="datasetSelected" class="form-control" name="selectDataSet">
                <option attributesNumber="4"
                        value="https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data"> Iris Data Set
                    - https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data
                </option>
                <option attributesNumber="6"
                        value="https://archive.ics.uci.edu/ml/machine-learning-databases/car/car.data"> Car Evaluation
                    Data Set - https://archive.ics.uci.edu/ml/machine-learning-databases/car/car.data
                </option>
            </select>
        </div>
        <div class="form-group row">
            <div class="col-md-6">
                <label for="splitNumberSelected">Defina a quantidade de Split(Text / Trainning)</label>
                <input type="number" class="form-control" id="splitNumberSelected" name="splitNumberSelected" step="0.1"
                       min="0.1" max="100" value="66.0">
            </div>
            <div class="col-md-6">
                <label for="kNumberSelected">Defina o K(Número de vizinhos)</label>
                <input type="number" class="form-control" id="kNumberSelected" name="kNumberSelected" min="1" max="7"
                       value="3">
            </div>
        </div>
        <div class="pull-right">
        <a href="#" id="btnSubmit" class="btn btn-warning">Testar!</a>
        </div>
    </form>
</div>
<div class="container">
    <hr>
</div>

<div class="container hidden" id="resultsDiv">
    <h2>Resultados</h2>
    <div class="form-group">
        <label id="trainningAmount"></label>
        <label id="testAmount"></label>
        <label id="totalAmount"></label><br/>
    </div>
    <div class="form-group">
        <label for="generalMatrix">General Matrixs</label>
        <textarea id="generalMatrix" class="form-control" rows="1"></textarea>
    </div>
    <div class="form-group">
        <label for="textAreaIndividualMatrix">Individual Matrixs</label>
        <textarea id="textAreaIndividualMatrix" class="form-control" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="textAreaPredicted">Predições Erradas</label>
        <textarea id="textAreaPredicted" class="form-control" rows="3"></textarea>
    </div>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="./assets/js/jquery-3.2.1.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="./assets/js/bootstrap.js"></script>

<script type="text/javascript" language="javascript">
    $(document).on('click', "#btnSubmit", function () {
        var dataset = $("#datasetSelected option:selected").attr("value");
        var attributesNumber = $("#datasetSelected  option:selected").attr("attributesNumber");
        var splitNumberSelected = $("#splitNumberSelected").val();
        var kNumberSelected = $("#kNumberSelected").val();

        var dataSent = "dataset=" + dataset + "&attributesNumber=" + attributesNumber + "&splitNumberSelected=" + splitNumberSelected + "&kNumberSelected=" + kNumberSelected;

        $.ajax({
            method: "POST",
            url: "../src/Run.php/dataSent",
            data: dataSent,
            datatype: 'json',
        }).done(function (result) {
            result = JSON.parse(result);
            refreshData(result);
        })
            .fail(function (jqXHR, textStatus, result) {
                console.log("Fail!!");
                console.log("jqXHR!!" + jqXHR);
                console.log("textStatus!!" + textStatus);
                console.log("msg!!" + result);
            })
    });
    function refreshData(data) {
        //Clear Inputs/TextsFields
        $("#trainningAmount").text('');
        $("#testAmount").text('');
        $("#totalAmount").text('');
        $("#attributesNumber").text('');
        $("#generalMatrix").text('');
        $("#textAreaIndividualMatrix").text('');
        $("#textAreaPredicted").text("");


        $("#trainningAmount").text("(Trainning Amount -> " + data.trainningAmount + ") + (");
        $("#testAmount").text("Test Amount -> " + data.testAmount + ") = (");
        $("#totalAmount").text("Total Amount -> " + data.totalAmount + ")");
        $("#attributesNumber").text("Attributes Number -> " + data.attributesNumber);

        var contentTableGeneralMatrix = "";
        $.each(data.generalResultTab, function (name, value) {
            contentTableGeneralMatrix = contentTableGeneralMatrix + name + " -> " + value + "  |  ";
        });
        contentTableGeneralMatrix = contentTableGeneralMatrix
        $("#generalMatrix").text(contentTableGeneralMatrix);

        var contentTableIndividualMatrix = "";
        $.each(data.individualsResultTab, function (name, value) {
            contentTableIndividualMatrix = contentTableIndividualMatrix + name + " - Results-> ";
            $.each(value, function (nam, val) {
                contentTableIndividualMatrix = contentTableIndividualMatrix + nam + " -> " + val + '\t';
            });
            contentTableIndividualMatrix = contentTableIndividualMatrix + '\n';
        });
        $("#textAreaIndividualMatrix").text(contentTableIndividualMatrix);

        var resultComparative = "";
        var countI = 0;
        $.each(data.comparative, function () {
            $.each(this, function (name, value) {
                resultComparative = resultComparative + '\n' + name + " " + value;
                countI += 1;
            });
        });
        resultComparative = "Total de Predições erradas => " + countI + resultComparative;
        $("#textAreaPredicted").text(resultComparative);

        $("#resultsDiv").removeClass('hidden');

    }
</script>
</body>
</html>