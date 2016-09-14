<?php

namespace AssetRegistryPlugin;

class AssetTable extends \WP_List_Table
{
    /** @var  array */
    protected $data = [];

    /**
     * @return array
     */
    public function get_columns()
    {
        $columns = array(
            'handle' => 'Handle',
            'src' => 'Source',
            'type' => 'Type',
            'ver' => 'Version',
            'deps' => 'Dependencies',
        );
        return $columns;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);
        usort($this->data, array(&$this, 'usort_reorder'));
        $this->items = $this->data;;
    }

    public function column_default($item, $column_name)
    {
        switch ($column_name) {
            case 'handle':
            case 'src':
            case 'type':
            case 'ver':
            case 'deps':
                return $item->$column_name;
            default:
                return print_r($item, true);
        }
    }

    /**
     * @return array
     */
    function get_sortable_columns()
    {
        $sortable_columns = array(
            'handle' => array('handle', false),
            'src' => array('src', false),
            'type' => array('type', false),
            'ver' => array('ver', false),
            'deps' => array('deps', false),
        );
        return $sortable_columns;
    }

    /**
     * @param $a
     * @param $b
     * @return int
     */
    function usort_reorder($a, $b)
    {
        // If no sort, default to title
        $orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'handle';
        // If no order, default to asc
        $order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a->$orderby, $b->$orderby);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     * @param $type
     * @return AssetTable
     */
    public function addData($data, $type)
    {
        $this->translateData($data, $type);

        $this->data = array_merge($this->data, $data);
        return $this;
    }

    /**
     * @param array $data
     * @param $type
     * @return array
     */
    protected function translateData(array &$data, $type)
    {
        foreach ($data as &$asset) {

            $asset->type = $type;

            if (is_array($asset->deps)) {
                $asset->deps = implode(',', $asset->deps);
            }
        }
    }
}