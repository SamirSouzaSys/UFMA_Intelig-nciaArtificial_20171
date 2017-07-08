<?php
require __DIR__ . '/vendor/autoload.php';

use KNN\Knn\Knn;

define("DEBUG", true);

if (DEBUG) print "<pre>";

$url = "https://archive.ics.uci.edu/ml/machine-learning-databases/iris/iris.data";

$knn = new Knn($url, 0.66, 4);
$knn->loadDataset();

if (DEBUG) {
  print "Trainig: " . count($knn->trainingSet) . "\n";
  print "Test: " . count($knn->testSet) . "\n";
  print "Total: ". (count($knn->trainingSet)+count($knn->testSet)). "\n";
};

$predictions = [];
$lengthTest = count($knn->testSet);

for ($i = 0; $i < $lengthTest; $i++) {
  $neighbors = $knn->getNeighbors($i, 3);

  $result = $knn->getResponse($neighbors);
  $predictions[] = $result;

  $actualClass = end($knn->testSet[$i]);
  $knn->buildIndividualConfusionMatrix($actualClass, $result, $i);

  print "\n$i> Predicted= ";
  print_r($result);
  print ", actual= ";
  print_r(end($knn->testSet[$i]));
}
$knn->buildGeneralConfusionMatrix();

//die();

//$accuracy = $knn->getAccuracy($predictions);

//print "\n\nAccuracy: ";
//print_r($accuracy);
//print "%";

$knn->fillIndividualConfusionMatrix();
$knn->fillGeneralConfusionMatrix();

print "\n\nResultado tabela Individual\n";
print_r($knn->classificationTable);
print "\n\nResultado tabela Geral\n";
print_r($knn->generalClassificationTable);

if (DEBUG) print "</pre>";

