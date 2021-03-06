<?php
namespace Pure\DataBase\Tables{
    class pure_bp_users_quotes implements \Pure\DataBase\Table{
        public function create(){
            global $wpdb;
            $table_name = \Pure\DataBase\TablesNames::instance()->quotes;
            if($wpdb->get_var('SHOW TABLES LIKE "'.$table_name.'"') !== $table_name){
                \Pure\DataBase\TablesQuery::$query .=   'CREATE TABLE '.
                                                        $table_name.' ( '.
                                                            'id '.              'BIGINT(20) '.  'NOT NULL AUTO_INCREMENT PRIMARY KEY,'.
                                                            'user_id '.         'BIGINT(20) '.  'NOT NULL,'.
                                                            'date_created '.    'DATETIME '.    'NOT NULL,'.
                                                            'quote '.           'TEXT '.        'NOT NULL,'.
                                                            'meta '.            'TEXT '.        'NOT NULL,'.
                                                            'active '.          'TINYINT '.     'NOT NULL);';
            }
        }
    }
}
?>