<?php

namespace Drupal\lista_tareas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Defines a form that configures forms module settings.
 */
class EditTarea extends FormBase {

  /**
    * {@inheritdoc }
    */
    public function getFormId() {
     return 'lista_tareas_form';
   }

  public function show( $id ){
    $database = \Drupal::database(); //conexion
    $query = $database->select('tareas', 't'); // Seleccion de la tabla taraeas
    $query->fields('t'); // campos a mostrar
    $query->condition('t.id', $id, '='); // validacion que sea igual el id que se recibe
    $result = $query->execute()->fetchAssoc();

    return $result;
  }

  public function buildForm(array $form, FormStateInterface $form_state, $id = null) {
    //Se reciben los datos desde la funcion show de arriba para ponerlos en los campos como valor
    $data = array();
    $data = $this->show($id);

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => true,
      '#value' => $data['nombre'],
    ];
    $form['descripcion'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Descripcion'),
      '#value' => $data['descripcion'],
    );
    $form['expiration'] = array(
      '#type' => 'date',
      '#title' => $this->t('Fecha de entrega'),
      '#value' => $data['fecha_entrega'],
    );
    /*
    $form['add']['custom_content_block_file'] = array(
      '#type' => 'managed_file',
      '#name' => 'custom_content_block_file',
      '#title' => t('Tarea'),
      '#description' => t('Suba un archivo word'),
      '#upload_location' => 'public://tareas'
    ),
    */

      $form['myfile'] = [
        '#title' => $this->t('Remplazar archivo de tarea'),
        '#type' => 'file',
        '#upload_location' => 'public://tareas'
        // DO NOT PROVILDE '#required' => TRUE or your form will always fail validation!
      ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Actualizar'),
      '#button_type' => 'primary',
    ];
      return $form;
  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state, $id=null) {

      $validators = ['file_validate_extensions' => ['docx xlsx']]; //validaion del tipo de documento
      $dest = 'public://tareas'; // espacio publico
      $file = file_save_upload('myfile', $validators, $dest); // Guardamos el archivo en la carpeta
      #Nota: por el momento el arhcivo se guarda en un estatus de temporal file, se mueve pero no es
      #permanetne el archvio

      //Se reciben los valores de formulario
     $campos = array(
       'nombre' => $form_state->getValue('nombre'),
       'fecha_entrega' => $form_state->getValue('expiration'),
       'descripcion' => $form_state->getValue('descripcion'),
       'template' => $form_state->getValue('myfile')
     );

     $connection = \Drupal::database();

     $query = $connection->update('tareas')
                         ->fields($campos)
                         ->condition('id', $id, '=')
                         ->execute();;

     $result = $connection->insert('tareas')->fields($campos)->execute();

  }
}
