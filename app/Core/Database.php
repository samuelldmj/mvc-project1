<?php

// $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;

// $conn = new PDO($string, DBUSER, DBPASS);

trait Database
{
    private function connect()
    {
        $string = "mysql:hostname=" . DBHOST . ";dbname=" . DBNAME;
        $conn = new PDO($string, DBUSER, DBPASS);
        return $conn;
    }

    public function query($query, $data = [])
    {
        $conn = $this->connect();
        $stm = $conn->prepare($query);

        // Ensure all data is scalar
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Handle array conversion for specific cases like WHERE IN
                $data[$key] = implode(',', $value);
            }
        }

        $exec = $stm->execute($data);
        if ($exec) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result)) {
                return $result;
            }
        }

        return false;
    }

    public function get_row($query, $data = [])
    {
        $conn = $this->connect();
        $stm = $conn->prepare($query);

        // Ensure all data is scalar
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                // Handle array conversion for specific cases like WHERE IN
                $data[$key] = implode(',', $value);
            }
        }

        $exec = $stm->execute($data);
        if ($exec) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result)) {
                return $result[0];
            }
        }

        return false;
    }
}
