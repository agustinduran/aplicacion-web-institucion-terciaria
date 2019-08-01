<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

// Controlador encargado del pase de información

class InformationController extends Controller {

    /** Método encargado de devolver información sobre una materia
    *
    * @Route("/info/materia", name="info-materia")
    */
    public function getMateria($codigoMateria = 0) {
        $request = Request::createFromGlobals();
        $dirDbf = $this->get('kernel')->getProjectDir() . '\public\dbf\MATERIAS.dbf';

        // Conexión sólo lectura
        $conex = dbase_open($dirDbf, 0);

        if ($conex) {
            $total_registros = dbase_numrecords($conex);
            $codigo = $request->get('codigoMateria');

            // Empieza desde el último al primero (más óptimo)
            for ($i = $total_registros; $i >= 1; $i--) {
                // Compara el codigoMateria, posición 0 es el codigoMateria
                if (  dbase_get_record($conex, $i)[0] == $codigoMateria ) {
                    $registroMateria = dbase_get_record_with_names($conex,$i);
                    break;
                }
            }

            return new Response(json_encode($registroMateria));

        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }

    }

    /** Método encargado de devolver información sobre las carreras
    *
    * @Route("/info/materias-disponibles", name="info-materias-disponibles")
    */
    public function getMateriasDisponibles($legajo = 0) {
        $request = Request::createFromGlobals();
        $dirDbfNotas = $this->get('kernel')->getProjectDir() . '\public\dbf\NOTAS.dbf';
        $dirDbfMaterias = $this->get('kernel')->getProjectDir() . '\public\dbf\MATERIAS.dbf';
        $conex_notas = dbase_open($dirDbfNotas, 0);
        $conex_materias = dbase_open($dirDbfMaterias, 0);

        if ($conex_notas && $conex_materias) {
            $arrayMaterias = array();
            $total_registros_notas = dbase_numrecords($conex_notas);
            $total_registros_materias = dbase_numrecords($conex_materias);

            $legajo = $request->get('legajo');
            $legajo_menos_uno = $legajo-1;

            $k = 0;

            $inf = 0;
            $sup = $total_registros_notas-1;

            // Algoritmo de busqueda binaria
            while ($inf <= $sup) {
                $centro = round($inf+$sup)/2;

                // la posición 0 es el legajo
                $row = (int)dbase_get_record($conex_notas, $centro)[0];

                if ($row == $legajo_menos_uno) {

                    for ($i = $centro; true; $i++) {

                        if (dbase_get_record($conex_notas, $i)[0] == $legajo && strcmp(substr(trim(dbase_get_record($conex_notas, $i)[3]), 0, 2)  , "SI") == 0 && ( strcmp( trim(dbase_get_record($conex_notas, $i)[7])  , "") == 0   || strcmp( trim(dbase_get_record($conex_notas, $i)[7])  , "*") == 0 ) ) {

                            $arrayMaterias[] = dbase_get_record_with_names($conex_notas,$i);
                            $materia = dbase_get_record($conex_notas, $i)[1];

                            for ($j = 1; $j <= $total_registros_materias; $j++) {
                                if ($materia == dbase_get_record($conex_materias, $j)[0]) {
                                   $arrayMaterias[$k]["NOMBREMATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[1]);
                                   $arrayMaterias[$k]["NOMBREMATERIACORTO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[2]);
                                   $arrayMaterias[$k]["TIPOMAT"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[4]);
                                   $arrayMaterias[$k]["CICLO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[6]);
                                }
                            }
                        $k++;
                        }

                        // Cuando el siguiente registro no pertenece a dicho legajo, sale del for
                        if (dbase_get_record($conex_notas, ($i+1))[0] > $legajo) {
                            break;
                        }
                    }

                    // Sale del while
                    break;
                } else if ($row < $legajo_menos_uno) {
                    $inf = $centro+1;
                } else {
                    $sup = $centro-1;
                }
            }
            return new Response(json_encode($arrayMaterias, JSON_UNESCAPED_UNICODE));
        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }

    }

    /** Método encargado de devolver información académica de un alumno
    *
    * @Route("/info/alumno", name="info-alumno")
    */
    public function getInfoAlumno($legajo = 0) {
        // Elimina las advertencias para registros que tengan campos vacios
        error_reporting(E_ALL ^ E_NOTICE);

        $request = Request::createFromGlobals();
        $dirDbfAlumnos = $this->get('kernel')->getProjectDir() . '\public\dbf\ALUMNOS.dbf';

        // Conexión sólo lectura
        $conex_alumnos = dbase_open( $dirDbfAlumnos, 0);

        // si la conexión se pudo realizar
        if( $conex_alumnos) {
            // longitud de registros
            $total_registros = dbase_numrecords($conex_alumnos);
            // captura el parámetro de la URL de legajo
            $legajo = $request->get('legajo');

            $inf = 0;
            $sup = $total_registros- 1 ;

            // Algoritmo de busqueda binaria
            while ($inf <= $sup) {
                $centro = round($inf + $sup)/ 2 ;

                // la posición 0 es el legajo
                $row = (int)dbase_get_record($conex_alumnos, $centro)[0];

                if ($row == $legajo) {
                    // Una vez que lo encuentra, transforma los caracteres especiales en utf8 para que no haya problemas en el JSON
                    for ($j = 0; $j <= dbase_numfields($conex_alumnos); $j++) {
                        $alumno[dbase_get_header_info($conex_alumnos)[$j]["name"]] = iconv("CP850", "UTF-8", dbase_get_record_with_names($conex_alumnos, $centro)[dbase_get_header_info($conex_alumnos)[$j]["name"]]);
                    }
                    // Sale del while
                    break;
                } else if ($row < $legajo) {
                    $inf = $centro+ 1 ;
                } else {
                    $sup = $centro- 1 ;
                }
            }

            // Muestra los datos en forma de JSON
            return new Response(json_encode($alumno, JSON_UNESCAPED_UNICODE));
        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }
    }

    /** Método encargado de devolver información sobre las calificaciones de un alumno
    *
    * @Route("/info/notas", name="info-notas")
    */
    public function getInfoNotas($legajo = 0) {
        $request = Request::createFromGlobals();

        $dirDbfNotas = $this->get('kernel')->getProjectDir() . '\public\dbf\NOTAS.dbf';
        $dirDbfMaterias = $this->get('kernel')->getProjectDir() . '\public\dbf\MATERIAS.dbf';
        $conex_notas            = dbase_open( $dirDbfNotas, 0);
        $conex_materias         = dbase_open( $dirDbfMaterias,0 );

        if ( $conex_notas && $conex_materias) {
            $arrayNotas = array();
            $total_registros_notas = dbase_numrecords($conex_notas);
            $total_registros_materias = dbase_numrecords($conex_materias);

            $legajo = $request->get("legajo");
            $legajo_menos_uno = $legajo- 1 ;

            $k = 0;

            $inf = 0;
            $sup = $total_registros_notas- 1 ;

            // Algoritmo de busqueda binaria
            while ($inf <= $sup) {
                $centro = round(($inf + $sup)/ 2);

                // la posición 0 es el legajo
                $row = (int)dbase_get_record($conex_notas, $centro)[0];

                if ($row == $legajo_menos_uno) {

                    for ($i = $centro; true; $i++) {
                        // La posición 0 es el legajo
                        if (dbase_get_record($conex_notas, $i)[0] == $legajo) {

                            // agrega al array el registro con su nombre de columna
                            $arrayNotas[] = dbase_get_record_with_names($conex_notas, $i);
                            // guarda el nombre de materia de la nota
                            $materia = dbase_get_record($conex_notas, $i)[1];

                            for ($j = 1; $j <= $total_registros_materias; $j++) {
                                // busca la materia en la tabla de materias (inner join)
                                if ($materia == dbase_get_record($conex_materias, $j)[0]) {
                                    // le agrega info adicional como nombre de materia
                                    $arrayNotas[$k]["NOMBREMATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[1]);
                                    $arrayNotas[$k]["NOMBREMATERIACORTO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[2]);
                                    $arrayNotas[$k]["TIPOMAT"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[4]);
                                    $arrayNotas[$k]["CICLO"] = dbase_get_record($conex_materias, $j)[6];
                                }
                            }

                            $k++;
                        }
                        // Cuando el siguiente registro no pertenece a dicho legajo, sale del for
                        if (dbase_get_record($conex_notas, ($i+ 1 ))[0] > $legajo) {
                            break;
                        }
                    }
                    // Sale del while
                    break;
                } else if ($row < $legajo_menos_uno) {
                    $inf = $centro+ 1 ;
                } else {
                    $sup = $centro- 1 ;
                }
            }

            return new Response(json_encode($arrayNotas, JSON_UNESCAPED_UNICODE));
        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }
    }

    /** Método encargado de devolver las mesas a las que un alumno puede inscribirse
    *
    * @Route("/info/mesas", name="info-mesas")
    */
    public function getInfoMesas($legajo = 0, $anio = "", $turno = "") {
        $request = Request::createFromGlobals();
        $dirDbfNotas = $this->get('kernel')->getProjectDir() . '\public\dbf\NOTAS.dbf';
        $dirDbfMaterias = $this->get('kernel')->getProjectDir() . '\public\dbf\MATERIAS.dbf';
        $dirDbfMesas = $this->get('kernel')->getProjectDir() . '\public\dbf\MESAS.dbf';
        $conex_notas          = dbase_open($dirDbfNotas, 0);
        $conex_materias       = dbase_open($dirDbfMaterias, 0);
        $conex_mesas       = dbase_open( $dirDbfMesas, 0);

        if ($conex_notas && $conex_materias && $conex_mesas) {
            $arrayMesas = array();
            $total_registros_notas = dbase_numrecords($conex_notas);
            $total_registros_materias = dbase_numrecords($conex_materias);
            $total_registros_mesas = dbase_numrecords($conex_mesas);

            $legajo = $request->get('legajo');
            $legajo_menos_uno = $legajo - 1;
            $anio = $request->get('anio');
            $turno = $request->get('turno');

            $k = 0;

            $inf = 0;
            $sup = $total_registros_notas- 1;

            // Algoritmo de busqueda binaria
            while ($inf <= $sup) {
                $centro = round($inf + $sup)/2;

                // la posición 0 es el legajo
                $row = (int)dbase_get_record($conex_notas, $centro)[0];

                if ($row == $legajo_menos_uno) {

                    for ($i = $centro; true; $i++) {

                        if (dbase_get_record($conex_notas, $i)[0] == $legajo && strcmp(trim(dbase_get_record($conex_notas, $i)[11]), "SI") == 0) {

                                $materia = dbase_get_record($conex_notas, $i)[1];

                                // z es por si hay dos llamados
                                $z = 0;

                                for ($j = 1; $j <= $total_registros_mesas; $j++) {

                                        if ($anio == dbase_get_record($conex_mesas, $j)[0]
                                            && strcmp($turno, trim(dbase_get_record($conex_mesas, $j)[1])) == 0
                                            && $materia == dbase_get_record($conex_mesas, $j)[2]
                                            && $z< 2) {

                                            $arrayMesas[$k + $z]["ANIO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[0]);
                                            $arrayMesas[$k + $z]["TURNO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[1]);
                                            $arrayMesas[$k + $z]["MATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[2]);
                                            $arrayMesas[$k + $z]["LLAMADO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[3]);
                                            $arrayMesas[$k + $z]["PRESIDENTE"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[5]);
                                            $arrayMesas[$k + $z]["FECHA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[8]);
                                            $arrayMesas[$k + $z]["ACTA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[9]);
                                            $arrayMesas[$k + $z]["DIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[10]);
                                            $arrayMesas[$k + $z]["FECHAPOR"] = iconv("CP437", "UTF-8", dbase_get_record($conex_mesas, $j)[11]);

                                            for ($y = 1; $y <= $total_registros_materias; $y++) {
                                                if ($materia == dbase_get_record($conex_materias, $y)[0]) {
                                                    $arrayMesas[$k + $z]["NOMBREMATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $y)[1]);
                                                    $arrayMesas[$k + $z]["NOMBREMATERIACORTO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $y)[2]);
                                                    $arrayMesas[$k + $z]["TIPOMAT"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $y)[4]);
                                                    $arrayMesas[$k + $z]["CICLO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $y)[6]);
                                                }
                                            }
                                            $z++;
                                        } else if ($z == 2) {
                                            $z = 0;
                                            break;
                                        } else if ($z == 1) {
                                            break;
                                        } else {
                                            $z = 0;
                                        }
                                    }
                                // restarle Z lo hice por si en una mesa hay un solo llamado
                                $k = $k + 2 - $z;
                            }

                        // Cuando el siguiente registro no pertenece a dicho legajo, sale del for
                        if (dbase_get_record($conex_notas, ($i+ 1 ))[0] > $legajo) {
                            break;
                        }
                    }
                    // Sale del while
                    break;
                } else if ($row < $legajo_menos_uno) {
                    $inf = $centro+ 1 ;
                } else {
                    $sup = $centro- 1 ;
                }
            }

            return new Response(json_encode($arrayMesas, JSON_UNESCAPED_UNICODE));
        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }
    }

    /** Método encargado de devolver información sobre las carreras
    *z
    * @Route("/info/finales", name="info-final")
    */
    public function getInfoFinal($legajo = 0) {
        $request = Request::createFromGlobals();

        // Testeo de tiempo
        //$tiempo_inicio = microtime_float();
        // Son necesarias las dos tablas
        $dirDbfFinales = $this->get('kernel')->getProjectDir() . '\public\dbf\ANOTAFIN.dbf';
        $dirDbfMaterias = $this->get('kernel')->getProjectDir() . '\public\dbf\MATERIAS.dbf';
        $conex_anotafin = dbase_open($dirDbfFinales, 0);
        $conex_materias = dbase_open($dirDbfMaterias, 0);

        if($conex_anotafin && $conex_materias) {
            $arrayFinales = array();
            $total_registros_anotafin = dbase_numrecords($conex_anotafin);
            $total_registros_materias = dbase_numrecords($conex_materias);

            $legajo = $request->get("legajo");
            $k = 0;

            // Algoritmo de busqueda binaria
            for ($i = 1; $i <= $total_registros_anotafin; $i++) {

                // La posición 0 es el legajo
                if (dbase_get_record($conex_anotafin, $i)[0] == $legajo) {

                    // agrega al array el registro con su nombre de columna
                    $arrayFinales[$k] = dbase_get_record_with_names($conex_anotafin,$i);
                     $arrayFinales[$k]["CODIGO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[0]);
                    $arrayFinales[$k]["MATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[1]);
                    $arrayFinales[$k]["ANIO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[2]);
                    $arrayFinales[$k]["TURNO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[3]);
                    $arrayFinales[$k]["LLAMADO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[4]);
                    $arrayFinales[$k]["FECHA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[5]);
                    $arrayFinales[$k]["TIPOALU"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[6]);
                    $arrayFinales[$k]["ACTA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[7]);
                    $arrayFinales[$k]["FECHAPOR"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[8]);
                    $arrayFinales[$k]["MODIPOR"] = iconv("CP437", "UTF-8", dbase_get_record($conex_anotafin, $i)[9]);

                    // guarda el nombre de materia de la nota
                    $materia = dbase_get_record($conex_anotafin, $i)[1];

                    for ($j = 1; $j <= $total_registros_materias; $j++) {

                        // busca la materia en la tabla de materias (inner join)
                        if ($materia == dbase_get_record($conex_materias, $j)[0]) {
                            // le agrega info adicional como nombre de materia
                            $arrayFinales[$k]["NOMBREMATERIA"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[1]);
                            $arrayFinales[$k]["NOMBREMATERIACORTO"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[2]);
                            $arrayFinales[$k]["TIPOMAT"] = iconv("CP437", "UTF-8", dbase_get_record($conex_materias, $j)[4]);
                            $arrayFinales[$k]["CICLO"] = dbase_get_record($conex_materias, $j)[6];
                        }
                    }

                    $k++;

                    // Cuando el siguiente registro no pertenece a dicho legajo, sale del for
                    if (dbase_get_record($conex_anotafin, ($i+1))[0] !=  $legajo) {
                        break;
                    }
                }
            }

            return new Response(json_encode($arrayFinales, JSON_UNESCAPED_UNICODE));
        } else {
            return new Response('No se pudo acceder al fichero dbf');
        }
    }

    /** Método encargado de devolver información sobre las carreras
    *
    * @Route("/info/carreras", name="info-carreras")
    */
    public function getInfoCarreras(){
        $archivoCarreras = $this->get('kernel')->getProjectDir() . '\public\Carreras.json';
        $json = json_decode(file_get_contents($archivoCarreras), true);

        return new JsonResponse($json);
    }

}
