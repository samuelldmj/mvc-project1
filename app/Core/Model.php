<?php

trait Model
{
    use Database;

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "desc";
    protected $order_column = 'id';
    public $errors = [];

    public function retrieve()
    {
        $query = "SELECT * FROM $this->table";
        $result = $this->query($query);
        return $result;
    }

    public function update($id, $data = [], $columnName = 'id')
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        $query = rtrim($query, ", ");
        $query .= " WHERE $columnName = :$columnName";
        $data[$columnName] = $id;
        $this->query($query, $data);
        return false;
    }

    public function delete($id, $columnName = 'id')
    {
        $data[$columnName] = $id;
        $query = "DELETE FROM $this->table WHERE $columnName = :$columnName";
        $this->query($query, $data);
        return false;
    }

    public function insert($data)
    {
        if (!empty($this->allowedColumns)) {
            foreach ($data as $key => $value) {
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(',', $keys) . ") VALUES (:" . implode(',:', $keys) . ")";
        $this->query($query, $data);
        return false;
    }


    public function where($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keysNot = array_keys($data_not);

        $query = "SELECT * FROM $this->table WHERE ";
        $conditions = [];

        foreach ($keys as $key) {
            $conditions[] = "$key = :$key";
        }

        foreach ($keysNot as $key) {
            $conditions[] = "$key != :$key";
        }

        $query .= implode(' AND ', $conditions);
        $query .= " ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";

        $data = array_merge($data, $data_not);
        return $this->query($query, $data);
    }

    public function findAll()
    {
        $query = "SELECT * FROM $this->table ORDER BY $this->order_column $this->order_type LIMIT $this->limit OFFSET $this->offset";
        return $this->query($query);
    }

    public function first($data, $data_not = [])
    {
        $keys = array_keys($data);
        $keysNot = array_keys($data_not);
        $query = "SELECT * FROM $this->table WHERE  ";
        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " AND ";
        }

        foreach ($keysNot as $key) {
            $query .= $key . " != :" . $key . " AND ";
        }

        $query = trim($query, " AND ");
        $query .= " LIMIT 1";
        // echo $query;
        $data = array_merge($data, $data_not);
        $result = $this->query($query, $data);
        if ($result) {
            return $result[0];
        }
        return false;
    }

}
