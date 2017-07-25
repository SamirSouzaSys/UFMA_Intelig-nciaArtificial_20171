<?php

require_once __DIR__ . '/../vendor/autoload.php';

use KNN\Knn;

if (empty($_POST)) {
  header('Location: ../public/');
}

$url = $_POST['dataset'];
$attributesNumber = $_POST['attributesNumber'];
$splitNumber = $_POST['splitNumberSelected'];
$kNumber = $_POST['kNumberSelected'];

$knn = new Knn($url, floatval($splitNumber), $attributesNumber, $kNumber);
$knn->loadDataset();

$trainningAmount = count($knn->trainingSet);
$testAmount = count($knn->testSet);
$totalAmount =
  count($knn->trainingSet) +
  count($knn->testSet);

$predictions = [];
$lengthTest = count($knn->testSet);

$dataPredictedsActuals = array();

for ($i = 0; $i < $lengthTest; $i++) {
  $neighbors = $knn->getNeighbors($i);

  $result = $knn->getResponse($neighbors);
  $predictions[] = $result;

  $actualClass = end($knn->testSet[$i]);
  $knn->buildIndividualConfusionMatrix($actualClass, $result, $i);

  $predictedStr = "$i> Predicted = " . $result;
  $actual = end($knn->testSet[$i]);
  $actualStr = ">>>>>> Truly = " . $actual;
  if ($result != $actual) {
    $dataPredictedsActuals[] = [$predictedStr => $actualStr];
//    " <<<< Different";
  }
}
$knn->buildGeneralConfusionMatrix();

$knn->fillIndividualConfusionMatrix();
$knn->fillGeneralConfusionMatrix();

$finalData = json_encode([
  "url" => $url,
  "attributesNumber" => $attributesNumber,
  "splitNumber" => $splitNumber,
  "trainningAmount" => $trainningAmount,
  "testAmount" => $testAmount,
  "totalAmount" => $totalAmount,
  "comparative" => $dataPredictedsActuals,
  "kNumber" => $kNumber,
  "individualsResultTab" => $knn->classificationTable,
  "generalResultTab" => $knn->generalClassificationTable
]);

print $finalData;
