<?php

namespace Drupal\person_info_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class PersonInfoForm extends FormBase {

  protected $logger;

  public function __construct(LoggerInterface $logger) {
    $this->logger = $logger;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('logger.channel.default')
    );
  }

  public function getFormId() {
    return 'person_info_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $phone_type = $form_state->getValue('phone_type');

    $form['#attributes']['class'][] = 'person-info-form-wrapper';
    $form['#attached']['library'][] = 'person_info_form/form_styles';

    $form['heading'] = [
      '#markup' => '<h1 class="form-title">Sign up</h1><h2 class="form-subtitle">Enter your credentials</h2>',
    ];

    $form['first_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('First name'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Enter first name')],
    ];
    $form['last_name'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Last name'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Enter last name')],
    ];
    $form['email'] = [
      '#type' => 'email',
      '#title' => $this->t('Email address'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Enter Email address')],
    ];
    $form['phone_type'] = [
      '#type' => 'select',
      '#title' => $this->t('Phone type'),
      '#options' => [
        'Home' => 'Home',
        'Business' => 'Business',
        'Mobile' => 'Mobile',
      ],
      '#required' => TRUE,
      '#ajax' => [
        'callback' => '::ajaxCallback',
        'wrapper' => 'agree-wrapper',
      ],
    ];
    $form['phone_number'] = [
      '#type' => 'tel',
      '#title' => $this->t('Phone number'),
      '#required' => TRUE,
      '#attributes' => ['placeholder' => $this->t('Enter phone number')],
    ];

    $form['agree_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'agree-wrapper'],
      'agree' => [],
    ];

    if ($phone_type === 'Mobile') {
      $form['agree_wrapper']['agree'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('I agree to receiving SMS messages'),
      ];
    }

    $form['favorite_colors'] = [
      '#type' => 'select',
      '#title' => $this->t('Favorite color(s)'),
      '#options' => [
        'Red' => 'Red',
        'Blue' => 'Blue',
        'Green' => 'Green',
        'Yellow' => 'Yellow',
      ],
      '#multiple' => TRUE,
      '#required' => TRUE,
      '#attributes' => [
        'class' => ['favorite-colors-select'],
      ],
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }

  public function ajaxCallback(array &$form, FormStateInterface $form_state) {
    return $form['agree_wrapper'];
  }

  public function validateForm(array &$form, FormStateInterface $form_state) {
    if (!preg_match('/^\d{10}$/', $form_state->getValue('phone_number'))) {
      $form_state->setErrorByName('phone_number', $this->t('Phone number must be 10 digits.'));
    }

    $colors = $form_state->getValue('favorite_colors');
    if (empty($colors) || !is_array($colors) || count($colors) < 2) {
      $form_state->setErrorByName('favorite_colors', $this->t('Please select at least two colors.'));
    }
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $data = $form_state->getValues();

    \Drupal::database()->insert('person_info_submissions')
      ->fields([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'phone_type' => $data['phone_type'],
        'phone_number' => $data['phone_number'],
        'favorite_colors' => implode(', ', $data['favorite_colors']),
        'created' => \Drupal::time()->getCurrentTime(),
      ])
      ->execute();

    $this->logger->info('Form submitted: @data', ['@data' => print_r($data, TRUE)]);
    $this->messenger()->addMessage($this->t('Form submitted successfully and saved to the database.'));
  }

}
