<?php

// @see https://github.com/webdeasy/php-mysql-database-class/tree/main

require_once "config/DatabaseConfig.php";


class Database
{
    private $db;


    public function __construct()
    {
        $this->connect();
    }


    /**
     * The functions opens the connections to the database.
     */
    private function connect()
    {
        if( "sqlite" == DatabaseConfig::DB_TYPE )                   // SQLITE
        {
            $this->openSqlite( DatabaseConfig::DB_FILE, DatabaseConfig::SQL_FILE );
        }
        else                                                        // unknwn db type
        {
            die( "Unknown DB Type: " . DatabaseConfig::DB_TYPE );
        }
    }


    /**
     * The functions opens the connection to a sqlite database.
     * If the file doesnt exist, it will be created and loaded the structre.
     * 
     * @param string path and name of the db-file
     * @param string path and name of the structure-file
     */
    private function openSqlite( $dbFile, $sqlFile )
    {
        try
        {
            if( !file_exists( $dbFile ) )
            {
                // create database
                touch( $dbFile );
                chmod( $dbFile, 0777 );

                // load sql structure 
                if( file_exists( $sqlFile ) )
                {
                    $sql = file_get_contents( $sqlFile );
                    $this->db = new PDO( "sqlite:" . $dbFile );
                    $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    $this->db->exec( $sql );                          
                }
                else
                {
                    die( "SQL Structrefile not found: " . $sqlFile );
                }
            }
            else
            {
                $this->db = new PDO( "sqlite:" . $dbFile );
                $this->db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
            }
        }
        catch( PDOException $e )
        {
            die( "Error DB Connection: " . $e->getMessage() );
        }
    }


    public function execQuery( $query, $params=[] )
    {
        try
        {
            $statement = $this->db->prepare( $query );
            if( empty( $params ) ) $result = $statement->execute();
            else $result = $statement->execute( $params );
            if( false != $result ) return $statement->fetchAll( PDO::FETCH_ASSOC );
            else return [];
        }
        catch( PDOException $e )
        {
            echo( "PDO Error: " . $e->getMessage() );
            return null;
        }
    }
}