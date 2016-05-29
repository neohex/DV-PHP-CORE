<?php

namespace App\Helpers;
use Alchemy\Zippy\Zippy;
use Session;
/* 
*@author Eddymens <eddymens@devless.io
 */
use App\Helpers\Helper as Helper;
use Illuminate\Support\Facades\Storage as Storage;
use App\Http\Controllers\DbController as DvSchema;
class DevlessHelper extends Helper
{
    //
    /**
     * set paramters for notification plate
     * 
     * @param type $message
     * @param type $message_color
     */
    public static function flash($message,$message_color="#736F6F")
    {
        $custom_colors = 
        [
            'error' => '#EA7878',
            'warning' => '#F1D97A',
            'success' => '#7BE454',
            'notification' => '#736F6F',
        ];
        (isset($custom_colors[$message_color]))?$notification_color = 
                $custom_colors[$message_color]
                : $notification_color = $message_color;
                
        session::flash('color', $notification_color);
        session::flash('flash_message', $message);
    }
    
    /*
     * get service_component from db 
     * @param $service_name 
     * @return service_object
     */
    public static function get_service_components($service_name)
    {
        $service = \DB::table('services')
                ->where('name',$service_name)->first();
        
        $tables = \DB::table('table_metas')
                ->where('service_id',$service->id)->get();
        
        $views_folder =$service_name;
        
        $service_components['service'] = $service;
        $service_components['tables'] = $tables;
        $service_components['views_folder'] = $views_folder;
        
        $service_components = self::convert_to_json($service_components);
                
        return $service_components;
    }

    public static function get_all_services()
    {
        $services = \DB::table('services')->get();
        $tables = \DB::table('table_metas')->get();
        
        $services_components['services'] = $services;
        $services_components['tables'] = $tables;
       
        $services_components = self::convert_to_json($services_components);
        
        return $services_components;
       
    }
    
    public static function convert_to_json($service_components)
    {
            
        $formatted_service_components = json_encode($service_components,true);
        
        return $formatted_service_components;
        
    }
    
    
    public static function zip_folder($service_folder_path,$extension)
    {
        $dvext = $extension;
        // Load Zippy
        $zippy = Zippy::load();
                //Creates a service_folder_name.pkg 
                //that contains a directory "folder" that contains
        //files contained in "service_folder_name" recursively
        
        $folder_name = basename($service_folder_path);
        
        $archive = $zippy->create($service_folder_path.'.zip', array(
            $folder_name => $service_folder_path
        ), true);
        
        
       rename($service_folder_path.'.zip',$service_folder_path.$dvext);
       self::deleteDirectory($service_folder_path);
       return $folder_name.$dvext;     

    }
    
    
    public static function expand_package($service_folder_path,$delete_package)
    {   
            $zip = new \ZipArchive;
            
            $service_basename = basename($service_folder_path);
            $state_or_payload = true;
            
            //convert from srv/pkg to zip
            $new_service_folder_path = preg_replace('"\.srv$"', '.zip', $service_folder_path);
            $state_or_payload = 
                 (rename($service_folder_path,$new_service_folder_path))? $new_service_folder_path
                    :false;
            
            $res = $zip->open($new_service_folder_path);
            if ($res === TRUE)
            {
                 $zip->extractTo(config('devless')['views_directory']);
                 $zip->close();
                 
                 self::deleteDirectory($new_service_folder_path);   
                 $state_or_payload = ($delete_package)? self::deleteDirectory($service_folder_path):false;
            }     
            else 
            {
                return false;
            }
            
            $folder_name = preg_replace('"\.srv$"', '', $service_basename);
            $exported_folder_path = config('devless')['views_directory'].$folder_name;
            return $exported_folder_path;
    }
    
    
    public static function add_service_to_folder($service_name,$service_components,
            $package_name = null)
    {
        if($package_name == null){$package_name  = $service_name; }
        
        $temporal_package_path = storage_path().'/'.$package_name;
        
        $service_schema_path = $temporal_package_path.'/service.json';
        
        $new_assets_path = storage_path().'/'.$package_name.'/view_assets/';
        
        $service_view_path = $new_assets_path.'/'.$service_name;
        
        $views_directory = config('devless')['views_directory'].$service_name;
        
        if(!file_exists($temporal_package_path) && !file_exists($service_view_path))
        {
            mkdir($temporal_package_path);
            mkdir($new_assets_path);
            
        }
        
        
        if(!file_exists($service_view_path)){mkdir($service_view_path);}
        
        //move asset files to temporal folder
        self::recurse_copy($views_directory, $service_view_path);
        
        if(!file_exists($service_schema_path))
        {
            $fb = fopen($service_schema_path,'w');
            $fb = fwrite($fb, $service_components);
        }
       
        //return folder_name
        return $temporal_package_path;
            
    }
    
    public static function recurse_copy($src,$dst)
    { 
        $dir = opendir($src); 
        @mkdir($dst); 
        while(false !== ( $file = readdir($dir)) ) 
        { 
            if (( $file != '.' ) && ( $file != '..' ))
            { 
                if ( is_dir($src . '/' . $file) )
                { 
                    recurse_copy($src . '/' . $file,$dst . '/' . $file); 
                } 
                else 
                { 
                    copy($src . '/' . $file,$dst . '/' . $file); 
                } 
            } 
        } 
        closedir($dir); 
    } 
    
    public static function deleteDirectory($dir) {
        if (!file_exists($dir))
        {
            return true;
        }

        if (!is_dir($dir)) 
        {
            return unlink($dir);
        }

        foreach (scandir($dir) as $item) 
        {
            if ($item == '.' || $item == '..')
            {
                continue;
            }

            if (!self::deleteDirectory($dir . DIRECTORY_SEPARATOR . $item))
            {
                return false;
            }

        }

        return rmdir($dir);
    }
    
    public static function get_file($filename)
    {
        
        $file_path = storage_path().'/'.$filename;
        if (!file_exists($file_path))
        {
            return false;
        }

        return $file_path;
    }
    
    public static function install_service($service_path)
    {
        $builder = new DvSchema();
        $service_file_path = $service_path.'/service.json';
        $fh = fopen($service_file_path, 'r');
        $service_json = fread($fh, filesize($service_file_path));
        fclose($fh);
        
        $service_object = json_decode($service_json,true);
        $service_id = [];
        $service_name = [];
        $service_id_map = [];
        $install_services = function($service) use(&$service_id, &$service_name, &$service_id_map )
        {
                $old_service_id = $service['id']; 
                $service_name[$old_service_id] = $service['name'];
                unset($service['id']);
                \DB::table('services')->insert($service);
                $new_service_id = \DB::getPdo()->lastInsertId();
                $service_id_map[$old_service_id] = $new_service_id ;
        };
        if(!isset($service_object['service'][0]))
        {
            $install_services($service_object['service']);
        }
        else
        {
             foreach($service_object['service'] as $service )
            {
                 $install_services($service);
            }
        }
        //get meta from service_table 
        $table_meta_install = function($service_table) use (&$service_id_map, &$builder,
                &$service_name )
        {
                $old_service_id = $service_table['service_id'];
                $new_service_id = $service_id_map[$old_service_id];
                $service_table['schema'] = json_decode($service_table['schema'],true);
                $resource = 'schema';
                $service_table['service_name'] = $service_name[$old_service_id];
                $service_table['driver'] = "default";
                $service_table['schema']['id'] = $new_service_id ;
                $service_table['params'] = [0 =>$service_table['schema']];
                $builder->create_schema($resource,$service_table);
        };
        if(!isset($service_object['tables'][0]))
        {
                $table_meta_install($service_object['tables']);
        }   
        else
        {
            foreach ($service_object['tables'] as $service_table )
            {   
               $table_meta_install($service_table);
            }
        }
    }
    
    public static function install_views()
    {
        return true;
    }
    
    
    
    
            
}