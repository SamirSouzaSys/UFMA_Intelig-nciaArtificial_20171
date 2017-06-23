<?php

namespace KNN\Knn;
/**
 * Created by PhpStorm.
 * User: samirsouza<samir.guitar@gmail.com>
 * Date: 19/06/17
 * Time: 22:43
 */
class Knn
{
  public $url;
  public $trainingSet;
  public $testSet;
  public $splitNumber;

  public function __construct($url = null, $splitNumber = null)
  {
    $this->url = $url;
    $this->splitNumber = $splitNumber;
    $this->trainingSet = [];
    $this->testSet = [];
  }

  /**
   * Handle - Preparo dos dados
   * @param null $url
   * @param null $splitNumber
   * @return string
   */
  public function handle($url = null, $splitNumber = null)
  {
    if ($url == null)
      $url = $this->url;

    if ($splitNumber == null)
      $splitNumber = $this->splitNumber;

    try {
      $content = file_get_contents($url);

      if ($content === false) {
        return "Wrong data!";
      }
    } catch (Exception $e) {
      return "Problem: " . $e;
    }

    $content = preg_split("/[\n,]+/", $content);

    $total = count($content);
    for ($j = 0, $i = 0; $i < $total - 1; $j++, $i += 5) {
      $arrayData = [
        //'compPet'
        'compPet' => $content[$i],
        //'largPet'
        'largPet' => $content[$i + 1],
        //'compSep'
        'compSep' => $content[$i + 2],
        //'largSep'
        'largSep' => $content[$i + 3],
        $content[$i + 4]
      ];

      if (rand(1, 100) < $splitNumber * 100) {
        $this->trainingSet[$i] = $arrayData;
      } else {
        $this->testSet[$i] = $arrayData;
      }
    }
  }

  /**
   * Similarity - cálculo da distância entre duas instâncias
   */
  public function similarity($instance1, $instance2, $length)
  {
    // EuclideanDistance
    $distance = 0;
    for($i= 0; $i<$length; $i++){
      $distance += pow( ($instance1[$i] - $instance2[$i]), 2);
    }
    return sqrt($distance);
  }

  /**
   * Neighbors: localiza k instâncias de dados mais semelhantes;
   *
   */
  public function Neighbors()
  {

  }

  /**
   * Response: gera uma resposta a partir de um conjunto de instância de dados;
   *
   */
  public function Response()
  {

  }

  /**
   * Accuracy: calcula a accuracy da predição (classificação);
   *
   */
  public function Accuracy()
  {

  }
}