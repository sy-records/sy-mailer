<?php

namespace SyMailer;

class Db
{
    private $db;

    private $table;

    private static $instance;

    public static $phpmailer_error;

    public static $id;

    public static function create()
    {
        if (!self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    private function __construct()
    {
        global $wpdb;

        $this->db = $wpdb;
        $this->table = $wpdb->prefix . 'sy_mailer_logs';
    }

    public function insert($data)
    {
        array_walk($data, function (&$value, $key) {
            if (is_array($value)) {
                $value = maybe_serialize($value);
            }
        });

        $result_set = $this->db->insert(
            $this->table,
            $data,
            array_fill(0, count($data), '%s')
        );

        if (!$result_set) {
            error_log('Sy Mailer Log insert error: ' . $this->db->last_error);

            return false;
        }

        return $this->db->insert_id;
    }

    public function update($data, $where = [])
    {
        array_walk($data, function (&$value, $key) {
            if (is_array($value)) {
                $value = maybe_serialize($value);
            }
        });

        $this->db->update(
            $this->table,
            $data,
            $where,
            array_fill(0, count($data), '%s'),
            array('%d')
        );
    }

    public function get()
    {
        $where = '';
        $where_cols = [];
        $prepare_array = [];
        if (isset($_GET['search']['value']) && !empty($_GET['search']['value'])) {
            $search = sanitize_text_field($_GET['search']['value']);

            foreach ($_GET['columns'] as $key => $col) {
                if ($col['searchable'] && !empty($col['data']) && $col['data'] !== 'timestamp') {
                    $column = sanitize_text_field(wp_unslash($col['data']));
                    $where_cols[] = "`{$column}` LIKE %s";
                    $prepare_array[] = '%' . $this->db->esc_like($search) . '%';
                }
            }

            if (!empty($where_cols)) {
                $where = implode(' OR ', $where_cols);
            }
        }

        $limit = [];
        if (isset($_GET['start'])) {
            $limit[] = absint($_GET['start']);
        }

        if (isset($_GET['length'])) {
            $limit[] = absint($_GET['length']);
        }

        $limit_query = '';
        if (!empty($limit)) {
            $limit_query = implode(',', $limit);
        }

        $orderby = 'timestamp';
        $order = 'DESC';

        if (!empty($_GET['order'][0])) {
            $col_num = absint($_GET['order'][0]['column']);
            $col_name = sanitize_text_field(wp_unslash($_GET['columns'][$col_num]['data']));
            $order_dir = sanitize_text_field(wp_unslash($_GET['order'][0]['dir']));
            $orderby = "`{$col_name}`";
            $order = "{$order_dir}";
        }

        if (!empty($prepare_array)) {
            $sql = $this->db->prepare(
                "SELECT * from {$this->table} WHERE {$where} ORDER BY {$orderby} {$order} LIMIT {$limit_query};",
                $prepare_array
            );
        } else {
            $sql = $this->db->prepare(
                "SELECT * from {$this->table} ORDER BY {$orderby} {$order} LIMIT {$limit_query};",
                $orderby
            );
        }

        error_log($sql);

        return $this->db->get_results($sql, ARRAY_A);
    }

    public function records_count()
    {
        return $this->db->get_var("SELECT COUNT(*) FROM {$this->table};");
    }
}
