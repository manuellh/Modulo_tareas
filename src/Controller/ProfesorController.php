<?php

namespace Drupal\lista_tareas\Controller;

use Drupal;
use Symfony\Component\HttpFoundation\Response;
use Drupal\node\Entity\Node;
use Drupal\Core\Controller\ControllerBase;

class ProfesorController extends ControllerBase{

  public function template() {
    // Sacar datos de la base de datos
        $connection = \Drupal::database();
        $query = $connection->query("SELECT * FROM {tareas}");
        $result = $query->fetchAll();
      // Recorrer los resultados y guardar los nodos en un array
        if (!empty($result)) {
          #\Drupal::messenger()->addMessage(t('Consulta exitosa'));
        }else {
          \Drupal::messenger()->addError(t('Se ha presentado un error'));
        }

      return [
        '#theme' => 'profesor/tareas', //Nombre de la matriz del modulo
        '#tareas' => $result,
      ];
  }

  public function show($id) {

    $database = \Drupal::database(); // Conexion
    $query = $database->select('tareas', 't'); //Slelecionar la tabla con el elias t
    $query->fields('t'); // columnas de la tabla, en este caso son todas
    $query->condition('t.id', $id, '='); // Condicion para donde el id es igual al que se esta recibiendo
    $result = $query->execute()->fetchAll();

    return [
      '#theme' => 'profesor/tareas_show', //Nombre de la matriz del modulo
      '#tareas' => $result, // Variable array debuelta a twig
    ];
  }


}
 ?>
