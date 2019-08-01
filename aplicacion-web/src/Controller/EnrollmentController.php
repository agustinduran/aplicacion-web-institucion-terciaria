<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Service\ServicioInscripciones;

class EnrollmentController extends Controller
{
    /**
     * @Route("/inscripcion/examenes", methods={"GET"}, name="inscripcion-examen")
     */
    public function inscripcionExamen($codigo = 0, $materia = 0, $fecha = 0, ServicioInscripciones $serInscr){
        $request = Request::createFromGlobals();
        $codigo = $request->get('codigo');
        $materia = $request->get('materia');
        $fecha = $request->get('fecha');
        $nombreArchivo = "inscripcionesExamenes.txt";
        $dirDbf = $this->get('kernel')->getProjectDir() . '/public/dbf/ANOTAFIN.dbf';
        $msg = "";
        $flagInscrito = false;
        $repetida = false;

        // Crea el archivo de inscripciones si no existe
        if (!file_exists($nombreArchivo)){
            touch($nombreArchivo);
        }

        // Si las entradas son validas
        if ($codigo != 0 && $materia !== 0 && $fecha !== 0){
            $inscripcion = $codigo."\t".$materia."\t".$fecha."\r\n";

            // Verificar que la inscripción no esté en el archivo esperando confirmación
            if ($archivo = fopen($nombreArchivo, "r")){
                while(!feof($archivo)){
                    $linea = fgets($archivo);
                    if ($linea === $inscripcion){
                        $repetida = true;
                        $msg = "La inscripción a la mesa de examen ingresada ya ha sido gestionada y está en proceso de confirmación.";
                    }
                }
                fclose($archivo);
            }

            // Si no esta esperando confirmación, verificar que no esté en la base de datos
            if (!$repetida && $serInscr->existeInscripcionExamen($dirDbf, str_replace("\t",'',$inscripcion))){
                $repetida = true;
                $msg = "La inscripción a esa mesa de examen ya ha sido realizada";
            }

            // Realiza la inscripción.
            if (!$repetida ){
                file_put_contents($nombreArchivo, $inscripcion, FILE_APPEND | LOCK_EX);
                $flagInscrito = true;
                $msg = "Inscripción a mesa de examen para la materia ".$materia." el día ".$fecha." realizada correctamente.";
            }
        } else {
            $msg = "Ocurrió un error al realizar la inscripción.";
        }

        $json = json_encode(array('flag' => $flagInscrito,'mensaje' => $msg), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new Response($json);
    }

    /**
     * @Route("/inscripcion/materias", methods={"GET"}, name="inscripcion-materia")
     */
    public function inscripcionMaterias($codigo = 0){
        $request = Request::createFromGlobals();
        $codigo = $request->get('codigo');
        $nombreArchivo = "inscripcionesMaterias.txt";
        $msg = "";
        $flagInscrito = false;
        $repetida = false;

        // Crea el archivo de inscripciones si no existe
        if (!file_exists($nombreArchivo)){
            touch($nombreArchivo);
        }

        // Si las entradas son validas
        if ($codigo != 0){
            $inscripcion = $codigo."\t"."\n";

            // Verificar que la inscripción no esté en el archivo esperando confirmación
            if ($archivo = fopen($nombreArchivo, "r")){
                while(!feof($archivo)){
                    $linea = fgets($archivo);
                    if ($linea === $inscripcion){
                        $repetida = true;
                        $msg = "La inscripción a cursada ya ha sido gestionada y está en proceso de confirmación.";
                    }
                }
                fclose($archivo);
            }

            // Realiza la inscripción.
            if (!$repetida ){
                file_put_contents($nombreArchivo, $inscripcion, FILE_APPEND | LOCK_EX);
                $flagInscrito = true;
                $msg = "Inscripción a cursada realizada correctamente.";
            }
        } else {
            $msg = "Ocurrió un error al realizar la inscripción.";
        }

        $json = json_encode(array('flag' => $flagInscrito,'mensaje' => $msg), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return new Response($json);
    }

}

?>