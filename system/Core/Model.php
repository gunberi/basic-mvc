<?php

namespace Sys\Core;

class Model
{

    private $db;
    protected $prefix = 'st_';
    protected $primaryKey = 'id';
    protected $table;
    private $select = '*';
    private $distinct;
    private $col = [];
    private $colNot = [];
    private $colLike = [];
    private $whereRaw;
    private $whereIn;
    private $whereNotIn;
    private $join;
    private $value = [];
    private $params = [];
    private $orderBy;
    private $limit;
    private $start = 0;
    private $sql;

    function __construct()
    {
        try {
            $this->db = new \PDO("mysql:host=localhost;dbname=mvc;charset=utf8", "root", "");
        } catch (PDOException $e) {
            print $e->getMessage();
        }
    }

    function db() {
        return $this->db;
    }

    function prefix() {
        return $this->prefix;
    }

    function find($id, $type = 'FETCH_OBJ')
    {
        $query = $this->db->query("SELECT * FROM {$this->prefix}{$this->table} WHERE {$this->primaryKey} = {$id} LIMIT 1");
        if($query) {
            return $query->fetch(1);
        }
        return false;
    }

	function findOrFail($id, $type = 'FETCH_OBJ')
    {
        $query = $this->db->query("SELECT * FROM {$this->prefix}{$this->table} WHERE {$this->primaryKey} = {$id} LIMIT 1");
		if($query) {
			return $query->fetch(1);
		} else {
			exit('Page Not Found');
		}
    }

    function distinct()
    {
        $this->distinct = 'DISTINCT ';
        return $this;
    }

    function select($select)
    {
        $this->select = $select;
        return $this;
    }

    function from($table) {
        $this->table = $table;
        return $this;
    }

    function where($col, $value)
    {
        array_push($this->col, $col);
        array_push($this->value, $value);
        $this->params[str_ireplace('.','',$col)] = $value;
        return $this;
    }

    function whereRaw($sql)
    {
        $this->whereRaw .= ($this->whereRaw ? " AND " : "") . "$sql";
        return $this;
    }

    function whereNot($col, $value)
    {
        array_push($this->colNot, $col);
        array_push($this->value, $value);
        $this->params[str_ireplace('.','',$col)] = $value;
        return $this;
    }

    function whereIn($col, $values)
    {
        if(is_array($values)) {
            $data = $values;
            $values = "";
            $i = 0;
            foreach ($data as $value) {
                $values .= ($i > 0 ? ",":"") .  (is_numeric($value) ? $value : "'$value'");
                $i++;
            }
        }

        $this->whereIn .= ($this->whereIn ? " AND " : "") .  "{$col} IN ({$values})";

        return $this;
    }

    function whereNotIn($col, $values)
    {
        if(is_array($values)) {
            $data = $values;
            $values = "";
            $i = 0;
            foreach ($data as $value) {
                $values .= ($i > 0 ? ",":"") .  (is_numeric($value) ? $value : "'$value'");
                $i++;
            }
        }

        $this->whereNotIn .= ($this->whereNotIn ? " AND " : "") .  "{$col} NOT IN ({$values})";

        return $this;
    }

    function like($col, $value)
    {
        array_push($this->colLike, $col);
        array_push($this->value, $value);
        return $this;
    }

    function join($table, $refer, $refer2, $type = ' ') {
        $this->join .= ($this->join ? " " : "") . "JOIN {$type} {$this->prefix}{$table} ON {$this->prefix}{$refer} = {$this->prefix}{$refer2}";
        return $this;
    }

    function limit($limit, $start = 0)
    {
        $this->limit = $limit;
        $this->start = $start;
        return $this;
    }

    function orderBy($col, $sort = 'ASC')
    {
        $this->orderBy .= ($this->orderBy ? ", " : "ORDER BY ") . "{$col} {$sort}";
        return $this;
    }

    function orderByRaw($sql) {
        $this->orderBy .= ($this->orderBy ? ", " : "ORDER BY ") . "$sql";
        return $this;
    }

    function get()
    {
    $sql = "SELECT {$this->distinct}{$this->select} FROM `{$this->prefix}{$this->table}` {$this->join}";
        if($this->col || $this->colNot || $this->colLike || $this->whereIn || $this->whereRaw) {
            $sql .= " WHERE ";
        }
        if ($this->col) {
            $i = 0;
            foreach ($this->col as $col) {
                $column = (stripos($col, '.') !== false) ?  $this->prefix . $col : $col;
                $sql .= ($i > 0 ? " AND " : "") . "{$column} = :" . str_ireplace('.','',$col);
                $i++;
            }
        }

        if ($this->colNot) {
            isset($i) ? $i = $i : $i = 0;
            foreach ($this->colNot as $col) {
                $sql .= ($i > 0 ? " AND " : "") . "{$col} != :" . str_ireplace('.','',$col);
                $i++;
            }
        }

        if ($this->colLike) {
            isset($i) ? $i = $i : $i = 0;
            foreach ($this->colLike as $col) {
                $sql .= ($i > 0 ? " AND " : "") . "{$col} LIKE :" . str_ireplace('.','',$col);
                $i++;
            }
        }

        if ($this->whereIn) {
            $sql .= (isset($i) ? " AND " : "") . $this->whereIn;
            $i = 1;
        }

        if ($this->whereNotIn) {
            $sql .= (isset($i) ? " AND " : "") . $this->whereNotIn;
            $i = 1;
        }

        if ($this->wherRaw) {
            $sql .= (isset($i) ? " AND " : "") . $this->wherRaw;
            $i = 1;
        }

        if ($this->orderBy) {
            $sql .= ' ' . $this->orderBy;
        }

        if ($this->limit) {
            $sql .=  " LIMIT {$this->limit}";
        }

        if ($this->start) {
            $sql .=  " OFFSET {$this->start}";
        }
        $sql;
        $this->sql = $sql;
        return $this;
    }

    function result() {
        $query = $this->db->prepare($this->sql);
        $query->execute($this->params??null);
        return $query->fetchAll();
    }

    function row() {
        $query = $this->db->prepare("{$this->sql} LIMIT 1");
        $query->execute($this->params??null);
        return $query->fetch(1);
    }

    function query($query)
    {
        $this->sql = $query;
        return $this;
    }

}
