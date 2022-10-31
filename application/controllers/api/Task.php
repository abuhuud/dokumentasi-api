<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class Task extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('Task_model', 'task');
  }
  public function index_get()
  {
    $id = $this->get('id');
    if ($id === null) {
      $task = $this->task->getTask();
    } else {
      $task = $this->task->getTask($id);
    }

    if ($task) {
      $this->response([
        'status' => TRUE,
        'data' => $task,
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'ID Task tidak ditemukan',
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_delete()
  {
    $id = $this->delete('id');
    if ($id === null) {
      $this->response([
        'status' => false,
        'message' => 'Masukkan ID Task!'
      ], REST_Controller::HTTP_BAD_REQUEST);
    } elseif ($this->task->deleteTask($id) > 0) {
      $this->response([
        'status' => true,
        'id' => $id,
        'message' => 'Task berhasil di hapus.',
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'ID task tidak ditemukan !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function index_post()
  {
    $file = $_FILES['doc_url'];
    $path = "uploads/docs";
    if (!is_dir($path)) {
      mkdir($path, 0777, true);
    }

    if (!empty($file['name'])) {
      $config['upload_path'] = './' . $path;
      $config['allowed_types'] = "jpg|jpeg|png|pdf";
      $this->upload->initialize($config);
      if ($this->upload->do_upload('doc_url')) {
        // Mendapatkan file yang berhasil di upload
        $uploadData = $this->upload->data();
        $path_file = './' . $path . $uploadData['file_name'];
      }
    }

    $data = [
      'category_id' => $this->post('category_id'),
      'title' => $this->post('title'),
      'description' => $this->post('description'),
      'start_date' => $this->post('start_date'),
      'finish_date' => $this->post('finish_date'),
      'status' => $this->post('status'),
      'doc_url' => $path_file,
    ];

    if ($this->task->createTask($data) > 0) {
      $this->response([
        'status' => true,
        'message' => 'Task baru berhasil ditambahkan.',
      ], REST_Controller::HTTP_CREATED);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Gagal menambahkan task !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function index_put()
  {
    $id = $this->put('id');

    $data = [
      'category_id' => $this->put('category_id'),
      'title' => $this->put('title'),
      'description' => $this->put('description'),
      'start_date' => $this->put('start_date'),
      'finish_date' => $this->put('finish_date'),
      'status' => $this->put('status'),
      'doc_url' => $this->put('doc_url'),
    ];

    if ($this->task->updateTask($data, $id) > 0) {
      $this->response([
        'status' => true,
        'message' => 'Task berhasil diubah.',
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Gagal menyunting task !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }
}
