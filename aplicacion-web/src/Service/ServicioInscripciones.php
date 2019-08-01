<?php
namespace App\Service;

class ServicioInscripciones {
  // Busca en la base de datos si ya existe una inscripción con los mismos datos.
    function existeInscripcionExamen($dbfname, $inscripcion) { 
        // Remueve las '/' para poder comparar las fechas de inscripción
        $inscripcion = str_replace("/", '', $inscripcion);
        // Booleano utilizado para determinar si ya existe una inscripcion.
        $flagMatch = false;
        // Abre la base de datos
        $fdbf = fopen($dbfname,'rb'); 
        $fields = array(); 
        $buf = fread($fdbf,32); 
        // Obtiene la cabecera
        $header = unpack( "VRecordCount/vFirstRecord/vRecordLength", substr($buf,4,8));     
        $goon = true; 
        $unpackString='';         
        // Obtiene los campos    
        while ($goon && !feof($fdbf)) {
            $buf = fread($fdbf,32);         
            if (substr($buf,0,1)==chr(13)) {          
                $goon=false; // end of field list         
            } else {             
                $field=unpack( "a11fieldname/A1fieldtype/Voffset/Cfieldlen/Cfielddec", substr($buf,0,18));             
                //echo 'Field: '.json_encode($field).'<br/>';             
                $unpackString.="A$field[fieldlen]$field[fieldname]/";             
                array_push($fields, $field);        
            }    
        }         
    
        // Va al primer registro    
        fseek($fdbf, $header['FirstRecord']+1);    
        // Lee registros    
        for ($i = 1; $i <= $header['RecordCount']; $i++) {         
            $buf = fread($fdbf,$header['RecordLength']);                 
            $registro = unpack($unpackString,$buf);        
            $datosInsc = $registro['CODIGO'].$registro['MATERIA'].$registro['FECHA'];                    
            if ($datosInsc === $inscripcion){    
                echo "Datos: ".$datosInsc." - Inscripcion: ".$inscripcion;
                $flagMatch = true;                    
                break;            
            }            
        }             
        
        fclose($fdbf);     
        return($flagMatch);  
    } 
}

?>