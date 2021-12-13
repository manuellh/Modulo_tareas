<?php

namespace Drupal\lista_tareas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Defines a form that configures forms module settings.
 */
class CrearTarea extends FormBase {

  /**
    * {@inheritdoc }
    */
    public function getFormId() {
     return 'lista_tareas_form';
   }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['nombre'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Nombre'),
      '#required' => true,
    ];
    $form['descripcion'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Descripcion'),
    );
    $form['expiration'] = array(
      '#type' => 'date',
      '#title' => $this->t('Fecha de entrega'),
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
        '#title' => $this->t('Subir archivo de tarea'),
        '#type' => 'file',
        '#upload_location' => 'public://tareas'
        // DO NOT PROVILDE '#required' => TRUE or your form will always fail validation!
      ];

    $form['actions']['#type'] = 'actions';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Asignar'),
      '#button_type' => 'primary',
    ];
      return $form;
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    $all_files = $this->getRequest()->files->get('files', []);
    if (!empty($all_files['myfile'])) {
      $file_upload = $all_files['myfile'];
      if ($file_upload->isValid()) {
        $form_state->setValue('myfile', $file_upload->getRealPath());
        return;
      }
    }

    $form_state->setErrorByName('myfile', $this->t('Debe de agregar un archivo word o excel para continuar.'));
  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

      #$fid = reset($form_state->getValue('myfile'));
      #$file = File::load('myfile');
/*
$doc = $form_state->getValue('custom_content_block_file');
$file = \Drupal\file\Entity\File::load($doc[0]);
$file->setPermanent();
$file->save();

$uri = $file->getFileUri();
*/


      $validators = ['file_validate_extensions' => ['docx xlsx']];
      $dest = 'public://tareas';
      $file = file_save_upload('myfile', $validators, $dest, FALSE, 0);

     $campos = array(
       'nombre' => $form_state->getValue('nombre'),
       'fecha_entrega' => $form_state->getValue('expiration'),
       'descripcion' => $form_state->getValue('descripcion'),
       'template' => $form_state->getValue('myfile')
     );

     $connection = \Drupal::database();
     $result = $connection->insert('tareas')->fields($campos)->execute();

  }
}
