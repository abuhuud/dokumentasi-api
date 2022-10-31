<?php

class TaskCategory_model extends CI_Model
{
  public function getTaskCategory($id = null)
  {
    if ($id === null) {
      return $this->db->get('task_categories')->result_array();
    } else {
      return $this->db->get_where('task_categories', ['id' => $id])->result_array();
    }
  }

  public function deleteCategory($id)
  {
    $this->db->delete('task_categories', ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function createCategory($data)
  {
    $this->db->insert('task_categories', $data);
    return $this->db->affected_rows();
  }

  public function updateCategory($data, $id)
  {
    $this->db->update('task_categories', $data, ['id' => $id]);
    return $this->db->affected_rows();
  }
}
