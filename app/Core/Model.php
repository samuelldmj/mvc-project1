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
        // Filter data to only allowed columns
        $data = array_filter($data, fn($key) => in_array($key, $this->allowedColumns), ARRAY_FILTER_USE_KEY);

        if (empty($data)) {
            return false; // No data to update
        }

        $keys = array_keys($data);
        $query = "UPDATE $this->table SET " . implode(' = ?, ', $keys) . " = ? WHERE $columnName = ?";
        return $this->query($query, array_merge(array_values($data), [$id])) !== false; // Return success/failure
    }

    public function insert($data)
    {
        // Filter data to only allowed columns
        $data = array_filter($data, fn($key) => in_array($key, $this->allowedColumns), ARRAY_FILTER_USE_KEY);

        if (empty($data)) {
            return false; // No data to insert
        }

        $keys = array_keys($data);
        $query = "INSERT INTO $this->table (" . implode(',', $keys) . ") VALUES (" . str_repeat('?,', count($keys) - 1) . "?)";
        return $this->query($query, array_values($data)) !== false; // Return success/failure
    }

    public function delete($id, $columnName = 'id')
    {
        $query = "DELETE FROM $this->table WHERE $columnName = ?";
        return $this->query($query, [$id]) !== false; // Return success/failure
    }

    public function first($where)
    {
        $query = "SELECT * FROM $this->table WHERE ";
        $keys = array_keys($where);
        $query .= implode(' = ? AND ', $keys) . " = ? LIMIT 1";
        $result = $this->query($query, array_values($where));

        return $result ? $result[0] : false; // Return first result or false
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

        // Merge data for named parameters
        $data = array_merge($data, $data_not);
        return $this->query($query, $data);
    }
}

