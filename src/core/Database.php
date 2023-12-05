<?php

namespace MyApp\Core;

use PDO, PDOException;

class Database
{
    // Private property untuk menyimpan koneksi database
    private $conn;

    // Properties untuk nama tabel dan kolom
    private $tableName;
    private $column = [];

    // Constructor untuk menginisialisasi koneksi database saat objek dibuat
    public function __construct()
    {
        $this->conn = $this->setConnection();
    }

    // Metode untuk mengatur nama tabel
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    // Metode untuk mengatur kolom
    public function setColumn($column)
    {
        $this->column = $column;
    }

    // Metode protected untuk mengatur koneksi database menggunakan PDO
    protected function setConnection()
    {
        try {
            $host = getenv('DB_HOST');
            $db = getenv('DB_NAME');
            $user = getenv('DB_USER');
            $pass = getenv('DB_PASS');
            $port = getenv('DB_PORT');

            // Membuat objek PDO untuk koneksi database
            $conn  = new PDO("mysql:host=$host;dbname=$db;port=$port", $user, $pass);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $e) {
            // Menangani kesalahan koneksi
            die($e->getMessage());
        }
    }

    // Metode untuk menjalankan query SQL dengan parameter opsional
    public function qry($query, $params = array())
    {
        // Menyiapkan dan mengeksekusi statement SQL
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

    // Metode untuk melakukan SELECT query dengan filter opsional
    public function get($params = array())
    {
        $column = implode(', ', $this->column);
        $query = "SELECT $column FROM {$this->tableName}";
        $paramValue = [];

        // Memproses filter jika diberikan
        if (!empty($params)) {
            $query .= " WHERE 1=1 ";
            foreach ($params as $key => $value) {
                $query .= "AND {$key} = ? ";
                array_push($paramValue, $value);
            }
        }

        // Menjalankan query
        return $this->qry($query, $paramValue);
    }

    // Metode untuk melakukan INSERT data ke dalam tabel
    public function insertData($data = array())
    {
        // Menangani kasus jika data kosong
        if (empty($data)) {
            return false;
        }

        $columnValue = [];
        $kolom = [];
        $param = [];

        // Membuat array untuk kolom, nilai, dan parameter
        foreach ($data as $key => $value) {
            array_push($kolom, $key);
            array_push($columnValue, $value);
            array_push($param, "?");
        }

        // Menggabungkan array menjadi string untuk query
        $kolom = implode(', ', $kolom);
        $param = implode(', ', $param);
        $query = "INSERT INTO {$this->tableName} ($kolom) VALUES ($param)";

        // Menjalankan query
        return $this->qry($query, $columnValue);
    }

    // Metode untuk melakukan UPDATE data dalam tabel
    public function updateData($data = array(), $param = array())
    {
        // Menangani kasus jika data kosong
        if (empty($data)) {
            return false;
        }

        $columnValue = [];
        $kolom = [];
        $query = "UPDATE {$this->tableName} ";

        // Membuat bagian SET dari query
        foreach ($data as $key => $value) {
            array_push($kolom, $key . " = ?");
            array_push($columnValue, $value);
        }

        // Menggabungkan array menjadi string untuk bagian SET
        $kolom = implode(', ', $kolom);
        $query = $query . "SET $kolom WHERE 1=1 ";

        $whereColumn = [];

        // Membuat bagian WHERE dari query
        foreach ($param as $key => $value) {
            array_push($whereColumn, "AND {$key} = ?");
            array_push($columnValue, $value);
        }

        // Menggabungkan array menjadi string untuk bagian WHERE
        $whereColumn = implode(', ', $whereColumn);
        $query = $query . $whereColumn;

        // Menjalankan query
        return $this->qry($query, $columnValue);
    }

    // Metode untuk melakukan DELETE data dalam tabel
    public function deleteData($param = array())
    {
        // Menangani kasus jika parameter kosong
        if (empty($param)) {
            return false;
        }

        $query = "DELETE FROM {$this->tableName} WHERE 1=1 ";
        $whereColumn = [];
        $columnValue = [];

        // Membuat bagian WHERE dari query
        foreach ($param as $key => $value) {
            array_push($whereColumn, "AND {$key} = ?");
            array_push($columnValue, $value);
        }

        // Menggabungkan array menjadi string untuk bagian WHERE
        $whereColumn = implode(', ', $whereColumn);
        $query = $query . $whereColumn;

        // Menjalankan query
        return $this->qry($query, $columnValue);
    }
}
