<?php

trait Model
{
    use Database;

    protected $limit = 10;
    protected $offset = 0;
    protected $order_type = "desc";
    protected $order_column = 'id';
    public $errors = [];

    public function update($id, $data = [], $columnName = 'id')
    {
        $data = array_filter($data, fn($key) => in_array($key, $this->allowedColumns), ARRAY_FILTER_USE_KEY);

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET " . implode(' = ?, ', $keys) . " = ? WHERE $columnName = ?";
        $this->query($query, array_merge(array_values($data), [$id]));
        return false;
    }

    public function insert($data)
    {
        $data = array_filter($data, fn($key) => in_array($key, $this->allowedColumns), ARRAY_FILTER_USE_KEY);

        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(',', $keys) . ") VALUES (" . str_repeat('?,', count($keys) - 1) . "?)";
        $this->query($query, array_values($data));
        return false;
    }

    public function delete($id, $columnName = 'id')
    {
        $query = "DELETE FROM $this->table WHERE $columnName = ?";
        $this->query($query, [$id]);
    }

    public function first($where)
    {
        $query = "SELECT * FROM $this->table WHERE ";
        $keys = array_keys($where);
        $query .= implode(' = ? AND ', $keys) . " = ? LIMIT 1";
        $result = $this->query($query, array_values($where));

        return $result ? $result[0] : false;
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
}
