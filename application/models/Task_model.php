<?php

class Task_model extends CI_Model
{
  public function getTask($id = null)
  {
    if ($id === null) {
      return $this->db->get('tasks')->result_array();
    } else {
      return $this->db->get_where('tasks', ['id' => $id])->result_array();
    }
  }

  public function deleteTask($id)
  {
    $this->db->delete('tasks', ['id' => $id]);
    return $this->db->affected_rows();
  }

  public function createTask($data)
  {
    $this->db->insert('tasks', $data);
    return $this->db->affected_rows();
  }

  public function updateTask($data, $id)
  {
    $this->db->update('tasks', $data, ['id' => $id]);
    return $this->db->affected_rows();
  }
}
