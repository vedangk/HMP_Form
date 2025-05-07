<?php

namespace Drupal\person_info_form\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SubmissionListController extends ControllerBase {

  protected $database;

  public function __construct(Connection $database) {
    $this->database = $database;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  public function list() {
    $header = [
      'id' => $this->t('ID'),
      'first_name' => $this->t('First Name'),
      'last_name' => $this->t('Last Name'),
      'email' => $this->t('Email'),
      'phone_type' => $this->t('Phone Type'),
      'phone_number' => $this->t('Phone Number'),
      'favorite_colors' => $this->t('Favorite Colors'),
      'created' => $this->t('Submitted At'),
    ];

    $query = $this->database->select('person_info_submissions', 'p')
      ->fields('p')
      ->orderBy('created', 'DESC')
      ->execute();

    $rows = [];
    foreach ($query as $record) {
      $rows[] = [
        'id' => $record->id,
        'first_name' => $record->first_name,
        'last_name' => $record->last_name,
        'email' => $record->email,
        'phone_type' => $record->phone_type,
        'phone_number' => $record->phone_number,
        'favorite_colors' => $record->favorite_colors,
        'created' => \Drupal::service('date.formatter')->format($record->created, 'short'),
      ];
    }

    return [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => $this->t('No submissions found.'),
    ];
  }

}
