<?php

use Restserver\Libraries\REST_Controller;

defined('BASEPATH') or exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
require APPPATH . 'libraries/Format.php';

class TaskCategory extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model('TaskCategory_model', 'category');
  }
  public function index_get()
  {
    $id = $this->get('id');
    if ($id === null) {
      $task_category = $this->category->getTaskCategory();
    } else {
      $task_category = $this->category->getTaskCategory($id);
    }

    if ($task_category) {
      $this->response([
        'status' => TRUE,
        'data' => $task_category,
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'ID tidak ditemukan',
      ], REST_Controller::HTTP_NOT_FOUND);
    }
  }

  public function index_delete()
  {
    $id = $this->delete('id');
    if ($id === null) {
      $this->response([
        'status' => false,
        'message' => 'Masukkan ID !'
      ], REST_Controller::HTTP_BAD_REQUEST);
    } elseif ($this->category->deleteCategory($id) > 0) {
      $this->response([
        'status' => true,
        'id' => $id,
        'message' => 'Data berhasil di hapus.',
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => FALSE,
        'message' => 'ID tidak ditemukan !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function index_post()
  {
    $data = [
      'name' => $this->post('name')
    ];

    if ($this->category->createCategory($data) > 0) {
      $this->response([
        'status' => true,
        'message' => 'Category baru berhasil ditambah.',
      ], REST_Controller::HTTP_CREATED);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Gagal menambahkan category task !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }

  public function index_put()
  {
    $id = $this->put('id');
    $data = [
      'name' => $this->put('name')
    ];

    if ($this->category->updateCategory($data, $id) > 0) {
      $this->response([
        'status' => true,
        'message' => 'Data berhasil diubah.',
      ], REST_Controller::HTTP_OK);
    } else {
      $this->response([
        'status' => false,
        'message' => 'Gagal menyunting data !',
      ], REST_Controller::HTTP_BAD_REQUEST);
    }
  }
}
