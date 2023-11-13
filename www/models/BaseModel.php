<?php

use \Phpfastcache\CacheManager;

require_once(__ROOT__ . '/config/DB.php');
require_once(__ROOT__ . '/utils/ClassUtils.php');
require_once(__ROOT__ . '/utils/Cache.php');

use CodeInc\StripAccents\StripAccents;

class BaseModel implements JsonSerializable
{
    private $_data;

    public static string $nome_tabella;
    protected array $_fields;

    private array $_values;
    private string $_columns;
    private string $_bind_columns;

    public ?array $errors;

    public function __construct(array $properties = array())
    {
        $properties = remove_accents_from_arraykey($properties);
        //Encoding per i campi che hanno caratteri accentati
        foreach ($this->_fields as $field)
            if (isset($properties[StripAccents::strip($field)]))
                $this->_data[StripAccents::strip($field)] = $properties[StripAccents::strip($field)];
    }

    public function __set(string $property, $value)
    {
        $methodName = ucfirst(str_replace(" ", "", $property));
        if (method_exists($this, $method = 'set' . $methodName)) {
            $this->$method($property, $value);
        }

        return $this->_data[$property] = $value;
    }

    //TODO: Display della Località
    //https://stackoverflow.com/questions/30112110/when-passing-an-array-to-twig-words-with-special-characters-are-disappearing
    public function __get(string $property)
    {
        //Gestioni chiavi con gli spazi
        $methodName = str_replace("`", "", str_replace(" ", "", ucwords($property)));

        //La variabile che ritorna da Twig ha un altro carattere finale
        // mb_detect_encoding


        if (method_exists($this, $method = 'get' . $methodName)) {
            return $this->$method($property);
        }

        //Località non viene riconosciuto da array_key_exists
        return array_key_exists($property, $this->_data)
            ? $this->_data[$property]
            : null;
    }

    public function __isset(string $property): bool
    {
        return array_key_exists($property, $this->_data);
    }

    private function _prepare()
    {
        $props = non_null_properties($this, $this->_fields);

        $this->_columns = implode(", ", wraparound_char($props, '`'));
        $this->_bind_columns = ':' . implode(", :", remove_accents_from_array(remove_spaces_from_array($props)));
        // $this->_bind_columns = ':' . implode(", :", prepare_bindings($props));
        $this->_values = class_to_array($this, remove_accents_from_array($props));
    }

    static function getSafeFieldsName($fields, bool $isPDO = false)
    {
        $res = array();
        foreach ($fields as $field => $val) {
            // $enc_field = $field;

            // if (str_starts_with($enc_field,"Localit"))
            //     $enc_field = "Località";
            // if($isPDO){
            //     $enc_field = replace_accents($enc_field);
            // } 
            $res[str_replace(" ", "_", mb_convert_encoding($field, "ISO-8859-1", "UTF-8"))] = $val;
        }
        return $res;
    }

    public function jsonSerialize():mixed
    {
        $data = $this->_data;

        /* add also null value fields */
        foreach ($this->_fields as $field)
            if (!isset($data[StripAccents::strip($field)]))
                $data[StripAccents::strip($field)] = "";

        return $data;
    }

    public function save(): int
    {
        $this->_prepare();
        $updates = array();
        foreach ($this->_fields as $column) {
            $safe_column = str_replace(" ", "_", $column);
            $updates[] = "`$column`=VALUES(`$column`)";
        }
        $updates_str = implode(',', $updates);

        $sql = "INSERT INTO " . static::$nome_tabella . " ($this->_columns) VALUES ($this->_bind_columns) ON DUPLICATE KEY UPDATE $updates_str";
        $sth = DB::get()->prepare($sql);
        $binded_values = self::getSafeFieldsName($this->_values, true);

        $sth->execute($binded_values);

        if (isset($this->_values["id"]) && $this->_values["id"] != '') {
            return (int) $this->_values["id"];
        } else {
            return (int) DB::get()->lastInsertId();
        }
    }

    public static function all(array $fields = null): array
    {
        $instance_cache = CacheManager::getInstance('Files');
        $instance_string = $instance_cache->getItem(static::$nome_tabella);

        if (!$instance_string->isHit()) {
            // $sql = 'SELECT * FROM ' . static::$nome_tabella;
            $sql = 'SELECT * FROM ' . static::$nome_tabella; // . ' LIMIT ' . 1500;
            $list = DB::get()->query($sql)->fetchAll();

            $instance_string->set($list)->expiresAfter(new DateInterval('P1M'));
            $instance_cache->save($instance_string);
        } else {
            $list = $instance_string->get();
        }

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    public static function where(array $conditions, bool $like = true): array
    {
        // $instance_cache = CacheManager::getInstance('Files');
        // $cache_name = array();


        // array_push($cache_name, static::$nome_tabella);
        // array_push($cache_name, $conditions);


        // print(implode("?",  $cache_name));
        // $instance_string = $instance_cache->getItem();

        $sql = 'SELECT * FROM ' . static::$nome_tabella;

        if (is_array($conditions) && !empty($conditions)) {
            $sql .= ' WHERE';

            $where = '';
            foreach ($conditions as $key => $prop) {
                $safe_key = str_replace(" ", "_", $key);

                if ($where == '')
                    $where .= " `$key` ";
                else
                    $where .= " AND `$key` ";

                if ($like) {
                    $where .= "LIKE ";
                    $conditions[$key] = '%' . $prop . '%';
                } else {
                    $where .= "= ";
                }

                $where .=  ":$safe_key";
            }
            $sql .= $where;
        }

        $sth = DB::get()->prepare($sql);
        $sth->execute(self::getSafeFieldsName($conditions));
        $list = $sth->fetchAll();

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    public static function select(array $selection): array
    {
        $instance_cache = CacheManager::getInstance('Files');
        $instance_string = $instance_cache->getItem(static::$nome_tabella . "?fields=" . implode(',', $selection));

        if (!$instance_string->isHit()) {
            $columns = implode(', ', wraparound_char($selection, "`"));
            $sql = 'SELECT ' . $columns . ' FROM ' . static::$nome_tabella;
            // . " LIMIT 1500";
            $list = DB::get()->query($sql)->fetchAll();

            $instance_string->set($list)->expiresAfter(new DateInterval('P1M'));
            $instance_cache->save($instance_string);
        } else {
            $list = $instance_string->get();
        }


        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        return $entities;
    }

    public static function count(): int
    {
        $sql = "SELECT count(*) FROM  " . static::$nome_tabella;

        $sth = DB::get()->prepare($sql);
        $sth->execute();

        return $sth->fetchColumn();
    }

    public static function countWhere(array $conditions, bool $like = true): int
    {
        $sql = "SELECT count(*) FROM  " . static::$nome_tabella;

        if (is_array($conditions)  && !empty($conditions)) {
            $sql .= ' WHERE';

            $where = '';
            foreach ($conditions as $key => $prop) {
                $safe_key = str_replace(" ", "_", $key);

                if ($where == '')
                    $where .= " `$key` ";
                else
                    $where .= " AND `$key` ";

                if ($like) {
                    $where .= "LIKE ";
                    $conditions[$key] = '%' . $prop . '%';
                } else {
                    $where .= "= ";
                }

                $where .=  ":$safe_key";
            }
            $sql .= $where;
        }

        $sth = DB::get()->prepare($sql);
        $sth->execute(self::getSafeFieldsName($conditions));
        $sth->execute();

        return $sth->fetchColumn();
    }

    public static function get(int $id)
    {
        // $instance_cache = CacheManager::getInstance('Files');
        // $instance_string = $instance_cache->getItem(static::$nome_tabella. "?id=" . $id);

        // if (!$instance_string->isHit()){

        $query = "SELECT * FROM " . static::$nome_tabella  . " WHERE id = :id";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'id' => $id,
        ]);
        $row = $sth->fetch();

        //     $instance_string->set($row)->expiresAfter(new DateInterval('PT1M'));
        //     $instance_cache->save($instance_string);

        // } else {
        //     $row = $instance_string->get();
        // }



        return new static($row);
    }

    public static function paginate(string $order, int $page, int $start, int $perPage, array $conditions = NULL, bool $like = true): array
    {
        //Order è la colonna per la quale ordinare in ordine alfabetico
        //Page sono le pagine
        //Start è la pagina di inizio
        //perPAge sono n elementi per pagina
        //conditions è la ricerca

        $sql = 'SELECT * FROM ' . static::$nome_tabella;

        if (is_array($conditions) && !empty($conditions)) {
            $sql .= ' WHERE';

            $where = '';
            foreach ($conditions as $key => $prop) {
                $safe_key = str_replace(" ", "_", $key);

                if ($where == '')
                    $where .= " `$key` ";
                else
                    $where .= " AND `$key` ";

                if ($like) {
                    $where .= "LIKE ";
                    $conditions[$key] = '%' . $prop . '%';
                } else {
                    $where .= "= ";
                }

                $where .=  ":$safe_key";
            }
            $sql .= $where;
        }

        $sql .= " ORDER BY ".$order;

        $sql .= " LIMIT $perPage";
        if ($start > 0)
            $sql .= " OFFSET $start";

        $sth = DB::get()->prepare($sql);
        $sth->execute(self::getSafeFieldsName($conditions));
        $list = $sth->fetchAll();

        $entities = array();
        foreach ($list as $row) {
            $entities[] = new static($row);
        }

        $count = self::count();
        $filtered = (is_array($conditions) && !empty($conditions)) ? self::countWhere($conditions) : $count;

        return [
            "draw" => $page,
            "recordsTotal" => self::count(),
            "recordsFiltered" => $filtered,
            "data" => $entities,
        ];
    }

    public static function delete(int $id): void
    {
        $query = "DELETE FROM " . static::$nome_tabella  . " WHERE id = :id;";
        $sth = DB::get()->prepare($query);
        $sth->execute([
            'ID' => $id,
        ]);
    }

    public function validate(): bool
    {
        return true;
    }
}
