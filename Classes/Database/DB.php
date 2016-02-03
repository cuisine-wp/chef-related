<?php

//change this namespace
    namespace ChefRelated\Database;

    use DateTime;
    use Exception;
    use Cuisine\Utilities\Logger;

    class DB{

        /**
         * Install the database tables
         * @return [type] [description]
         */
        public static function install() {
                
            global $wpdb;
            $table_name = $wpdb->prefix . 'chef_related_posts';
            
            $charset_collate = $wpdb->get_charset_collate();
            try {
            
                $sql = "CREATE TABLE {$table_name} (
                    ID mediumint(9) NOT NULL AUTO_INCREMENT,
                    post_id mediumint(9) NOT NULL,
                    post_data longtext DEFAULT '' NOT NULL,
                    related_post_id mediumint(9) NOT NULL,
                    related_post_data longtext DEFAULT '' NOT NULL,
                    UNIQUE KEY ID (ID)
                ) $charset_collate;";

                
                $result = $wpdb->query( $sql );

                if( $result === false )
                    throw new Exception( $wpdb->last_error );

                

            } catch( Exception $ex ) {
                
                Logger::error( $ex->getMessage() );
            
            }

            update_option( 'chef_related_posts_table_installed', true );

        }

        /**
         * insert new post relation
         * @param  [int] $postID - post_id
         * @param  [string] $postData - post data (title, id)
         * @param  [int] $relatedPostID - post_id
         * @param  [string] $relatedPostData - post data (title, id)
         * @return [int] ID - The ID of the inserted relation
         */
        public static function insert( $postID, $postData, $relatedPostID, $relatedPostData ){
          
            global $wpdb;
            $table_name = $wpdb->prefix . 'chef_related_posts';
            $datetime = date( 'Y-m-d H:i:s' );

            try {
                $result = $wpdb->query( 
                    $wpdb->prepare( 
                    "
                    INSERT INTO {$table_name}
                    (post_id, post_data, related_post_id, related_post_data)
                    VALUES (%d, %s, %d, %s)
                    "
                , $postID, $postData, $relatedPostID, $relatedPostData ));

                if( $result === false )
                    throw new Exception( $wpdb->last_error );

                
            } catch( Exception $ex ) {
             
                Logger::error( $ex->getMessage() );
            
            }
        }


        /**
         * deletes a relationship
         * @param  [int] $ID - relationship ID
         * @param  [boolean] $bidirections - relationship type (single or both ways)
         */
        public static function delete( $postID, $bidirectional = false ){
            
            global $wpdb;
            $table_name = $wpdb->prefix . 'chef_related_posts';

            try{
                $queryString = $wpdb->prepare( 
                    "
                    DELETE FROM {$table_name} 
                    WHERE post_id = %d
                    "
                , $postID);
                if($bidirectional) {
                    $queryString .= ' ' . $wpdb->prepare(
                        "OR related_post_id = %d"
                    , $postID);
                }

                $result = $wpdb->get_results( $queryString );

               
                if( $result === false )
                    throw new Exception( $wpdb->last_error );

            }catch( Exception $ex ) {

                Logger::error( $ex->getMessage() );
            
            }
        }

       
        /**
         * get the post relations
         * @param  [int] $postID - the id of a post 
         * @param  [boolean] $bidirections - relationship type (single or both ways)
         * @return [array]  the query result
         */
        public static function get( $postID, $bidirectional = false ){
            
            global $wpdb;
            $table_name = $wpdb->prefix . 'chef_related_posts';

            try{
                
                $queryString = $wpdb->prepare( 
                    "
                    SELECT *
                    FROM {$table_name} 
                    WHERE post_id = %d
                    "
                , $postID);

                if($bidirectional) {
                    $queryString .= ' ' . $wpdb->prepare(
                        "OR related_post_id = %d"
                    , $postID);
                }
                
                $result = $wpdb->get_results( $queryString );

                if( $result === false )
                    throw new Exception( $wpdb->last_error );

                return $result;

            }catch( Exception $ex ) {

                Logger::error( $ex->getMessage() );
            
            }
        }
    }
?>