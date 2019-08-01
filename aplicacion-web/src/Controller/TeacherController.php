<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\AusenciaDocente;

// Controlador encargado de gestionar el registro de futuras ausencias de docentes

class TeacherController extends Controller {

    /**
    * @Route("/ausencias", name="ausencias")
    */
    public function index() {
        $repositorio = $this->getDoctrine()->getRepository(AusenciaDocente::Class);
        $ausencias = $repositorio->findAll();
        return $this->render('ausencias/lista-ausencias.html.twig', array('ausencias' => $ausencias ));
    }

    /** Método encargado de crear una nueva ausencia docente
    *
    * @Route("/ausencias/crear", name="crear-ausencia")
    * @Method({"GET"})
    */

    public function create() {

        $datos = array(
            'docente' => '',
            'fechaIni' => '',
            'fechaFin' => ''
        );


        // Array utilizado para enviar mensajes de error en los campos que corresponda
        $error = array();

        // Variable usado para enviar un mensaje de confirmación si la actualización se realizó correctamente
        $valid = 0;

        // Validación del formulario

        if (!empty($_POST)){
            // Verifica que los campos no estén vacios
            $valDocente = !empty($_POST["_docente"]);
            $valFechaIni = !empty($_POST["_fecha-ini"]);
            $valFechaFin = !empty($_POST["_fecha-fin"]);

            // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
            $docente = $_POST["_docente"];
            $fechaIni = new \DateTime($_POST["_fecha-ini"]);
            $fechaFin = new \DateTime($_POST["_fecha-fin"]);

            // Verifica que la fecha de fin sea mayor a la de inicio
            if ($valFechaFin){
                $diferencia = $fechaIni->diff($fechaFin);
                $diferencia = $diferencia->format('%r%a');
            } else {
                $diferencia = 1;
            }

            if ($valDocente && $valFechaIni && $diferencia >= 0){

                $ausencia = new AusenciaDocente();
                $ausencia->setDocente($docente);
                $ausencia->setFechaIni($fechaIni);
                if ($valFechaFin) $ausencia->setFechaFin($fechaFin);

                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ausencia);
                $entityManager->flush();
                $valid = 1;
            } else {
                // En caso de error, envia array indicando los campos con errores.
                $error = array(
                    'docente' => $valDocente,
                    'fechaIni' => $valFechaIni,
                    'diferencia' => $diferencia
                );

                // Guarda los datos del formulario en un array para recargarlos
                $datos['docente'] = $docente;
                if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
                if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');


            }
        }

        return $this->render('ausencias/crear-ausencia.html.twig', array(
            'error' => $error,
            'valid' => $valid,
            'datos' => $datos)
        );
    }

    /** Método encargado de actualizar información de una ausencia docente
    *
    * @Route("/ausencias/actualizar/{id}", name="update-ausencia")
    */

    public function update($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $ausencia = $entityManager->getRepository(AusenciaDocente::class)->find($id);
        $fechaFin = $ausencia->getFechaFin();
        if (!is_null($fechaFin))$fechaFin = $fechaFin->format('Y-m-d');

        // Obtiene los datos de la ausencia para colocarlos en el formulario
        $datos = array(
            'docente' => $ausencia->getDocente(),
            'fechaIni' => $ausencia->getFechaIni()->format('Y-m-d'),
            'fechaFin' => $fechaFin
        );

        // Array utilizado para enviar mensajes de error en los campos que corresponda
        $error = array();

        // Variable usado para enviar un mensaje de confirmación si la actualización se realizó correctamente
        $valid = 0;

        // Validación del formulario
        if (!empty($_POST)) {
            // Verifica que los campos no estén vacios
            $valDocente = !empty($_POST["_docente"]);
            $valFechaIni = !empty($_POST["_fecha-ini"]);
            $valFechaFin = !empty($_POST["_fecha-fin"]);

            // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
            $docente = $_POST["_docente"];
            $fechaIni = new \DateTime($_POST["_fecha-ini"]);
            $fechaFin = new \DateTime($_POST["_fecha-fin"]);

            // Verifica que la fecha de fin sea mayor a la de inicio
            if ($valFechaFin){
                $diferencia = $fechaIni->diff($fechaFin);
                $diferencia = $diferencia->format('%r%a');
            } else {
                $diferencia = 1;
            }

            // Realiza la actualización si los datos son correctos
            if ($valDocente && $valFechaIni && $diferencia >= 0) {
                $ausencia->setDocente($docente);
                $ausencia->setFechaIni($fechaIni);
                if ($valFechaFin) $ausencia->setFechaFin($fechaFin);
                $entityManager->flush();
                $valid = 1;
            } else {
                // En caso de error, envia array indicando los campos con errores.
                $error = array(
                    'docente' => $valDocente,
                    'fechaIni' => $valFechaIni,
                    'diferencia' => $diferencia
                );
            }

            // Guarda los datos del formulario en un array para recargarlos
            $datos['docente'] = $docente;
            if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
            if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');
        }

        return $this->render('ausencias/update-ausencia.html.twig', array(
            'id' => $id,
            'error' => $error,
            'datos' => $datos,
            'valid' => $valid
        ));
    }

    /** Método encargado de eliminar una ausencia docente
    *
    * @Route("/ausencias/eliminar/{id}", name="delete-ausencia")
    */

    public function delete($id) {
        $entityManager = $this->getDoctrine()->getManager();
        $ausencia = $entityManager->getRepository(AusenciaDocente::class)->find($id);

        if ($ausencia) {
            $entityManager->remove($ausencia);
            $entityManager->flush();
        }

        $repositorio = $this->getDoctrine()->getRepository(AusenciaDocente::Class);
        $ausencias = $repositorio->findAll();
        return $this->render('ausencias/lista-ausencias.html.twig', array('ausencias' => $ausencias ));
    }

    /** Método encargado de generar un json conteniendo las ausencias
    *
    * @Route("/info/ausencias", name="info-ausencias")
   */

    public function show() {
        $repositorio = $this->getDoctrine()->getRepository(AusenciaDocente::class);
        $ausencias = $repositorio->findAll();
        $datosTodo = array();


        foreach ($ausencias as $ausencia){

            $fecha = $ausencia->getFechaIni()->format('Y-m-d');
            // Si existe fechaFin, la agrega
            if (!is_null($ausencia->getFechaFin())){
                $fechaFin = $ausencia->getFechaFin();
                $fechaFin = $fechaFin->format('Y-m-d');
                $fecha = $fecha." hasta ".$fechaFin;
            }

            $datos = array(
                'docente' => $ausencia->getDocente(),
                'fecha' => $fecha
            );

            array_push($datosTodo, $datos);
        }

        $json = json_encode($datosTodo, JSON_UNESCAPED_UNICODE);

        return new Response($json);
  }

}
