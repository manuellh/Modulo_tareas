<?php

namespace Drupal\lista_tareas\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines a form that configures forms module settings.
 */
class TareasForm extends FormBase {

  /**
    * {@inheritdoc }
    */
    public function getFormId() {
     return 'lista_tareas_form';
   }

  public function buildForm(array $form, FormStateInterface $form_state) {

  }

  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

    /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }
}
