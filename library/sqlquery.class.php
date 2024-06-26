<?php

class SQLQuery
{
    protected $_dbHandle;
    protected $_result;
    protected $_query;
    protected $_table;


    public $_describe = array();

    protected $_orderBy;
    protected $_order;
    protected $_extraConditions;
    protected $_hO;
    protected $_hM;
    protected $_hMABTM;
    protected $_page;
    protected $_limit;
    protected $_hMpage;
    protected $_hMlimit;
    protected $_hMorder;
    protected $_hMorderBy;


    /** Connects to database **/

    function connect($address, $account, $pwd, $name)
    {
        $this->_dbHandle = new mysqli($address, $account, $pwd, $name);
        if (!$this->_dbHandle) {
            die("Connection failed: " . mysqli_connect_error());
        }
        if ($this->_dbHandle != null) {
            if (mysqli_select_db($this->_dbHandle, $name)) {
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** Disconnects from database **/

    function disconnect()
    {
        if (@mysqli_close($this->_dbHandle) != 0) {
            return 1;
        } else {
            return 0;
        }
    }

    /** Select Query **/

    function where($field, $value)
    {
        $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` = \'' . mysqli_real_escape_string($this->_dbHandle, $value) . '\' AND ';
    }

    function like($field, $value)
    {
        $this->_extraConditions .= '`' . $this->_model . '`.`' . $field . '` LIKE \'%' . mysqli_real_escape_string($this->_dbHandle, $value) . '%\' AND ';
    }

    function showHasOne()
    {
        $this->_hO = 1;
    }

    function showHasMany()
    {
        $this->_hM = 1;
    }

    function showHMABTM()
    {
        $this->_hMABTM = 1;
    }

    function setLimit($limit)
    {
        $this->_limit = $limit;
    }

    function setPage($page)
    {
        $this->_page = $page;
    }

    function setHasManyLimit($child, $limit)
    {
        $this->_hMlimit[$child] = $limit;
    }

    function setHasManyPage($child, $page)
    {
        $this->_hMpage[$child] = $page;
    }

    function orderBy($orderBy, $order = 'ASC')
    {
        $this->_orderBy = $orderBy;
        $this->_order = $order;
    }

    function hasManyOrderBy($model, $orderBy, $order = 'ASC')
    {
        $this->_hMorderBy[$model] = $orderBy;
        $this->_hMorder[$model] = $order;
    }

    function search()
    {
        global $inflect;

        $from = '`' . $this->_table . '` as `' . $this->_model . '` ';
        $conditions = '\'1\'=\'1\' AND ';
        $conditionsChild = '';
        $fromChild = '';

        if ($this->_hO == 1 && isset($this->hasOne)) {

            foreach ($this->hasOne as $alias => $model) {
                $table = strtolower($inflect->pluralize($model));
                $singularAlias = strtolower($alias);
                $from .= 'LEFT JOIN `' . $table . '` as `' . $alias . '` ';
                $from .= 'ON `' . $this->_model . '`.`' . $singularAlias . '_id` = `' . $alias . '`.`id`  ';
            }
        }
//	    var_dump([$from, $this->_hO, $this->hasOne]); die();
        if ($this->id) {
            $conditions .= '`' . $this->_model . '`.`id` = \'' . mysqli_real_escape_string($this->_dbHandle, $this->id) . '\' AND ';
        }

        if ($this->_extraConditions) {
            $conditions .= $this->_extraConditions;
        }

        $conditions = substr($conditions, 0, -4);

        if (isset($this->_orderBy)) {
            $conditions .= ' ORDER BY `' . $this->_model . '`.`' . $this->_orderBy . '` ' . $this->_order;
        }

        if (isset($this->_page)) {
            $offset = ($this->_page - 1) * $this->_limit;
            $conditions .= ' LIMIT ' . $this->_limit . ' OFFSET ' . $offset;
        }

        $this->_query = 'SELECT * FROM ' . $from . ' WHERE ' . $conditions;


//        var_dump($this->_query) ;die();
        $this->_result = mysqli_query($this->_dbHandle, $this->_query);


        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();
        $numOfFields = mysqli_num_fields($this->_result);
        for ($i = 0; $i < $numOfFields; ++$i) {
            $finfo = $this->_result->fetch_field_direct($i);
            array_push($table, $finfo->table);
            array_push($field, $finfo->name);
        }
        if (mysqli_num_rows($this->_result) > 0) {
            while ($row = mysqli_fetch_row($this->_result)) {
                for ($i = 0; $i < $numOfFields; ++$i) {
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
//                echo var_dump($tempResults)."</br>";
                if ($this->_hM == 1 && isset($this->hasMany)) {
                    foreach ($this->hasMany as $aliasChild => $modelChild) {
                        $queryChild = '';
                        $conditionsChild = '';
                        $fromChild = '';

                        $tableChild = strtolower($inflect->pluralize($modelChild));
                        $pluralAliasChild = strtolower($inflect->pluralize($aliasChild));
                        $singularAliasChild = strtolower($aliasChild);

                        $fromChild .= '`' . $tableChild . '` as `' . $aliasChild . '`';

                        $conditionsChild .= '`' . $aliasChild . '`.`' . strtolower($this->_model) . '_id` = \'' . $tempResults[$this->_model]['id'] . '\'';

                        if (isset($this->_hMorderBy[$aliasChild])) {
                            $conditionsChild .= ' ORDER BY `' . $modelChild . '`.`' . $this->_hMorderBy[$aliasChild] . '` ' . $this->_hMorder[$aliasChild];
                        }

                        if (isset($this->_hMpage[$aliasChild])) {
                            $offset = ($this->_hMpage[$aliasChild] - 1) * $this->_hMlimit[$aliasChild];
                            $conditionsChild .= ' LIMIT ' . $this->_hMlimit[$aliasChild] . ' OFFSET ' . $offset;
                        }


						$queryChild =  'SELECT * FROM '.$fromChild.' WHERE '.$conditionsChild;
//						var_dump($queryChild);
						$resultChild = mysqli_query( $this->_dbHandle, $queryChild);
//                        var_dump("ketqua    "+ $resultChild);
//                        echo "$queryChild";


                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();

                        if (mysqli_num_rows($resultChild) > 0) {

                            $numOfFieldsChild = mysqli_num_fields($resultChild);
//                            echo "$numOfFieldsChild</br>";
                            for ($j = 0; $j < $numOfFieldsChild; ++$j) {
                                $finfo = $resultChild->fetch_field_direct($j);
                                array_push($tableChild, $finfo->table);
                                array_push($fieldChild, $finfo->name);
                            }

                            while ($rowChild = mysqli_fetch_row($resultChild)) {
                                for ($j = 0; $j < $numOfFieldsChild; ++$j) {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }
                        }

                        $tempResults[$aliasChild] = $resultsChild;

                        mysqli_free_result($resultChild);
                    }
                }
//                echo var_dump($tempResults)."kaka</br>";

                if ($this->_hMABTM == 1 && isset($this->hasManyAndBelongsToMany)) {
                    foreach ($this->hasManyAndBelongsToMany as $aliasChild => $tableChild) {
                        $queryChild = '';
                        $conditionsChild = '';
                        $fromChild = '';

                        $tableChild = strtolower($inflect->pluralize($tableChild));
                        $pluralAliasChild = strtolower($inflect->pluralize($aliasChild));
                        $singularAliasChild = strtolower($aliasChild);

                        $sortTables = array($this->_table, $pluralAliasChild);
                        sort($sortTables);
                        $joinTable = implode('_', $sortTables);

                        $fromChild .= '`' . $tableChild . '` as `' . $aliasChild . '`,';
                        $fromChild .= '`' . $joinTable . '`,';

                        $conditionsChild .= '`' . $joinTable . '`.`' . $singularAliasChild . '_id` = `' . $aliasChild . '`.`id` AND ';
                        $conditionsChild .= '`' . $joinTable . '`.`' . strtolower($this->_model) . '_id` = \'' . $tempResults[$this->_model]['id'] . '\'';
                        $fromChild = substr($fromChild, 0, -1);

                        $queryChild = 'SELECT * FROM ' . $fromChild . ' WHERE ' . $conditionsChild;
//                        var_dump($queryChild);
                        $resultChild = mysqli_query($this->_dbHandle, $queryChild);
//                         var_dump($resultChild);
//
                        $tableChild = array();
                        $fieldChild = array();
                        $tempResultsChild = array();
                        $resultsChild = array();

                        if (mysqli_num_rows($resultChild) > 0) {

                            $numOfFieldsChild = mysqli_num_fields($resultChild);

                            for ($j = 0; $j < $numOfFieldsChild; ++$j) {
                                $finfo = $resultChild->fetch_field_direct($j);
                                array_push($tableChild, $finfo->table);
                                array_push($fieldChild, $finfo->name);
                            }

                            while ($rowChild = mysqli_fetch_row($resultChild)) {
                                for ($j = 0; $j < $numOfFieldsChild; ++$j) {
                                    $tempResultsChild[$tableChild[$j]][$fieldChild[$j]] = $rowChild[$j];
                                }
                                array_push($resultsChild, $tempResultsChild);
                            }

                        }

                        $tempResults[$aliasChild] = $resultsChild;
                        mysqli_free_result($resultChild);
                    }
                }

                array_push($result, $tempResults);
            }

            if (mysqli_num_rows($this->_result) == 1 && $this->id != null) {
                mysqli_free_result($this->_result);
                $this->clear();
                return ($result[0]);
            } else {
                mysqli_free_result($this->_result);
                $this->clear();
                return ($result);
            }
        } else {
            mysqli_free_result($this->_result);
            $this->clear();
            return $result;
        }

    }

    /** Custom SQL Query **/

    function custom($query)
    {
//        echo "custom";
        global $inflect;

        $this->_result = mysqli_query($this->_dbHandle, $query);

        $result = array();
        $table = array();
        $field = array();
        $tempResults = array();

        if (substr_count(strtoupper($query), "SELECT") > 0) {
            if (mysqli_num_rows($this->_result) > 0) {
                $numOfFields = mysqli_num_fields($this->_result);
                for ($i = 0; $i < $numOfFields; ++$i) {
                    $finfo = $this->_result->fetch_field_direct($i);
                    array_push($table, $finfo->table);
                    array_push($field, $finfo->name);
                }
                while ($row = mysqli_fetch_row($this->_result)) {
                    for ($i = 0; $i < $numOfFields; ++$i) {
                        $table[$i] = ucfirst($inflect->singularize($table[$i]));
                        $tempResults[$table[$i]][$field[$i]] = $row[$i];
                    }
                    array_push($result, $tempResults);
                }
            }
            mysqli_free_result($this->_result);
        }
        $this->clear();
        return ($result);
    }

    /** Describes a Table **/

    protected function _describe()
    {
        global $cache;
        $this->_describe = null;
//        khong tu update cache
//        $cache->get('describe'.$this->_table)
        if (!$this->_describe) {
            $this->_describe = array();
            $query = 'DESCRIBE ' . $this->_table;
            $this->_result = mysqli_query($this->_dbHandle, $query);
            while ($row = mysqli_fetch_row($this->_result)) {
                array_push($this->_describe, $row[0]);
            }

            mysqli_free_result($this->_result);
            $cache->set('describe' . $this->_table, $this->_describe);
        }

        foreach ($this->_describe as $field) {
            $this->$field = null;
        }
//        var_dump(["des", $this->_describe, $cache]);
    }

    /** Delete an Object **/

    function delete()
    {

        if ($this->id) {
            $query = 'DELETE FROM ' . $this->_table . ' WHERE `id`=\'' . $this->id . '\'';
            $this->_result = mysqli_query($this->_dbHandle, $query);
            $this->clear();
            if ($this->_result == 0) {
                /** Error Generation **/
                return -1;
            }
        } else {
            if ($this->_extraConditions) {
                $from = '`' . $this->_table . '` as `' . $this->_model . '` ';
                $conditions = '\'1\'=\'1\' AND ';
                $conditions .= $this->_extraConditions;
                $conditions = substr($conditions, 0, -4);
                $query = 'DELETE FROM ' . $from . ' WHERE ' . $conditions;
                $this->_result = mysqli_query($this->_dbHandle, $query);
                $this->clear();
                if ($this->_result == 0) {
                    /** Error Generation **/
                    return -1;
                }
            }

        }
        return 0;

    }

    /** Saves an Object i.e. Updates/Inserts Query **/

    function save()
    {
        $query = '';
        if (isset($this->id)) {
            $updates = '';

            foreach ($this->_describe as $field) {
                if ($this->$field) {
                    $updates .= '`' . $field . '` = \'' . mysqli_real_escape_string($this->_dbHandle, $this->$field) . '\',';
                }
            }

            $updates = substr($updates, 0, -1);
            $query = 'UPDATE ' . $this->_table . ' SET ' . $updates . ' WHERE `id`=\'' . mysqli_real_escape_string($this->_dbHandle, $this->id) . '\'';
//            var_dump([$this->_describe,   $query]);die();

        } else {
            $fields = '';
            $values = '';
            foreach ($this->_describe as $field) {
                if ($this->$field) {
                    $fields .= '`' . $field . '`,';
                    $values .= '\'' . mysqli_real_escape_string($this->_dbHandle, $this->$field) . '\',';
                }
            }
            $values = substr($values, 0, -1);
            $fields = substr($fields, 0, -1);

            $query = 'INSERT INTO ' . $this->_table . ' (' . $fields . ') VALUES (' . $values . ')';

        }


        $this->_result = mysqli_query($this->_dbHandle, $query);
        if ($this->_dbHandle->insert_id != null) $this->insert_id = $this->_dbHandle->insert_id;
//        var_dump([ $query]);
        $this->clear();
        if ($this->_result == 0) {
            /** Error Generation **/
            return -1;
        }
    }

    /** Clear All Variables **/

    function clear()
    {
        foreach ($this->_describe as $field) {
            $this->$field = null;
        }

        $this->_orderby = null;
        $this->_extraConditions = null;
        $this->_hO = null;
        $this->_hM = null;
        $this->_hMABTM = null;
        $this->_page = null;
        $this->_order = null;
        $this->_hMpage = null;
        $this->_hMlimit = null;
    }

    /** Pagination Count **/

    function totalPages()
    {
//	    echo "pages";
        if ($this->_query && $this->_limit) {
            $pattern = '/SELECT (.*?) FROM (.*)LIMIT(.*)/i';
            $replacement = 'SELECT COUNT(*) FROM $2';
            $countQuery = preg_replace($pattern, $replacement, $this->_query);
//            var_dump([$this->_query,  $countQuery]); die();
            $this->_result = mysqli_query($this->_dbHandle, $countQuery);
            $count = mysqli_fetch_row($this->_result);
            $totalPages = ceil($count[0] / $this->_limit);


            return $totalPages;
        } else {
            /* Error Generation Code Here */
            return -1;
        }
    }

    /** Get error string **/

    function getError()
    {
        return mysqli_error($this->_dbHandle);
    }
}