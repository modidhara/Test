<?php
class DBConfig{
    private $username;
    private $password;
    private $server;
    private $dbname;
    
    public $connection;
    
    public function __construct(){
        $this->db_connect();
    }
    
    private function db_connect(){
        $this->server = 'localhost';
        $this->username = 'root';
        $this->password = '';
        $this->dbname = 'demo';
        
        $this->connection = new mysqli($this->server, $this->username, $this->password, $this->dbname);
        
        if($this->connection->connect_error):
            die("Connection failed:" . $connection->connect_error);
        else:
            return $this->connection;
        endif;
        
    }
    
    public function getData($query){
        $result = $this->connection->query($query);
        
        if( $result == false ){
            return false;
        }
        
        $rows = array();
        
        while( $row = $result->fetch_assoc() ):
            $rows[] = $row;
        endwhile;
        
        return $rows;
    }
    
    public function execute($query){ 
        $result = $this->connection->query($query);
        
        if( $result == false ):
            echo 'Error: '. $this->connection->error;
            return false;
        else:
            return true;
        endif;
    }
    
    public function get_last_insert_id(){
        $id = $this->connection->insert_id;
        
        return $id;
    }

        public function escape_string($value){
        return $this->connection->real_escape_string();
    }
}
?>