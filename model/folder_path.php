<?php
namespace Model;

use ACDCS;
class FolderPath{
    public function folderPath($folder_name) 
    {
        $request = $_POST;
        $array_fields = array('folder_path');
        $table 	   	= 'tbl_folder_path';
        $joins 	   	= '';
        $sql_where 	= 'WHERE folder_name = "'.$folder_name.'"';
        $sql_order 	= '';
        $sql_limit 	= 'LIMIT 0,1';
        $script = ACDCS::getInstance()->select_query_script($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        $query = ACDCS::getInstance()->select_query($array_fields,$table,$joins,$sql_where,$sql_order,$sql_limit);
        while($row = mysqli_fetch_array($query)){
            $request['folder_path'] = $row['folder_path'];
        }
        return $request;
    }
}

?>
