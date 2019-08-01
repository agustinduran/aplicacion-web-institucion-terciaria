<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\FechaCalendario;

class CalendarController extends Controller{

  /**
  * @Route("/calendario", name="calendario")
  * @Method({"GET"})
  */
  public function index(){

    $repositorio = $this->getDoctrine()->getRepository(FechaCalendario::Class);
    $fechas = $repositorio->findBy([], ['fecha_ini' => 'DESC']);

    return $this->render('calendario/calendario.html.twig', array('fechas' => $fechas ));
  }

  /**
  * @Route("/calendario/crear", name="crear-fecha")
  * @Method({"GET"})
  */
  public function create(){
    $datos = array(
      'fechaIni' => '',
      'fechaFin' => '',
      'descripcion' => ''
    );

    // Array para mensajes de error y variable para mensaje de confirmación.
    $error = array();
    $valid = 0;

    // Validación del formulario
    if (!empty($_POST)){

      // Verifica que los campos no estén vacios
      $valFechaIni = !empty($_POST["_fecha-ini"]);
      $valFechaFin = !empty($_POST["_fecha-fin"]);
      $valDescripcion = !empty($_POST["_descripcion"]);
      // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
      $descripcion = $_POST["_descripcion"];
      $fechaIni = new \DateTime($_POST["_fecha-ini"]);
      $fechaFin = new \DateTime($_POST["_fecha-fin"]);
      // Verifica que la fecha de fin sea mayor a la de inicio
      // En caso de que no se ingrese fecha de fin, da un valor valido para permitir crear
      if ($valFechaFin){
        $diferencia = $fechaIni->diff($fechaFin);
        $diferencia = $diferencia->format('%r%a');
      } else {
          $diferencia = 1;
      }


      if ($valFechaIni && $valDescripcion && $diferencia >= 0){
        $fecha = new FechaCalendario();
        $fecha->setFechaIni($fechaIni);
        if ($valFechaFin)$fecha->setFechaFin($fechaFin);
        $fecha->setDescripcion($descripcion);
        $fecha->setVisible(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($fecha);
        $entityManager->flush();

        $valid = 1;
      } else {
        // envia un array con datos para los mensajes de error
        $error = array(
          'fechaIni' => $valFechaIni,
          'descripcion' => $valDescripcion,
          'diferencia' => $diferencia
        );
        // envia un array con los datos del formulario para recargarlos
        if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
        if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');
        $datos['descripcion'] = $descripcion;
      }
    }

    return $this->render('calendario/crear-fecha.html.twig', array(
      'error' => $error,
      'valid' => $valid,
      'datos' => $datos)
    );
  }

  /**
   * @Route("/calendario/actualizar/{id}", name="update-fecha")
   */

   public function update($id){

     $entityManager = $this->getDoctrine()->getManager();
     $fecha = $entityManager->getRepository(FechaCalendario::class)->find($id);
     $fechaFin = $fecha->getFechaFin();
     if (!is_null($fechaFin))$fechaFin = $fechaFin->format('Y-m-d');

     $datos = array(
      'fechaIni' => $fecha->getFechaIni()->format('Y-m-d'),
      'fechaFin' => $fechaFin,
      'descripcion' => $fecha->getDescripcion()
    );

    $error = array();
    $valid = 0;

    // Validación del formulario
    if (!empty($_POST)){

      // Verifica que los campos no estén vacios
      $valFechaIni = !empty($_POST["_fecha-ini"]);
      $valFechaFin = !empty($_POST["_fecha-fin"]);
      $valDescripcion = !empty($_POST["_descripcion"]);
      // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
      $fechaIni = new \DateTime($_POST["_fecha-ini"]);
      $fechaFin = new \DateTime($_POST["_fecha-fin"]);
      $descripcion = $_POST["_descripcion"];
      // Verifica que la fecha de fin sea mayor a la de inicio
      // En caso de que no se ingrese fecha de fin, da un valor valido para permitir crear
      if ($valFechaFin){
        $diferencia = $fechaIni->diff($fechaFin);
        $diferencia = $diferencia->format('%r%a');
      } else {
          $diferencia = 1;
      }

      if ($valFechaIni && $valDescripcion && $diferencia >= 0){
        $fecha->setFechaIni($fechaIni);
        if ($valFechaFin)$fecha->setFechaFin($fechaFin);
        $fecha->setDescripcion($descripcion);

        $entityManager->flush();

        $valid = 1;
      } else {
        // envia un array con datos para los mensajes de error
        $error = array(
          'fechaIni' => $valFechaIni,
          'descripcion' => $valDescripcion,
          'diferencia' => $diferencia
        );
      }

      // envia un array con los datos del formulario para recargarlos
      if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
      if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');
      $datos['descripcion'] = $descripcion;
    }


     return $this->render('calendario/update-fecha.html.twig', array(
       'id' => $id,
       'error' => $error,
       'datos' => $datos,
       'valid' => $valid
     ));
   }

   /** Método encargado de eliminar una fecha
    *
    * @Route("/calendario/eliminar/{id}", name="delete-fecha")
   */

  public function delete($id){

    $entityManager = $this->getDoctrine()->getManager();
    $fecha = $entityManager->getRepository(FechaCalendario::class)->find($id);

    if ($fecha){
      $entityManager->remove($fecha);
      $entityManager->flush();
    }

    $repository = $this->getDoctrine()->getRepository(FechaCalendario::Class);
    $fechas = $repository->findAll();

    return $this->render('calendario/calendario.html.twig', array('fechas' => $fechas ));

  }

  /** Método encargado de generar un json conteniendo las fechas
    *
    * @Route("/info/fechas", name="fechas")
   */

  public function show(){
    $repository = $this->getDoctrine()->getRepository(FechaCalendario::Class);
    $fechas = $repository->findAll();
    $datosTodo = array();


    foreach ($fechas as $fecha){
      $fechaFin  = null;
      if (!is_null($fecha->getFechaFin())){
        $fechaFin = $fecha->getFechaFin();
        $anioFin =$fechaFin->format('Y');
        $mesFin =$fechaFin->format('m');
        $diaFin =$fechaFin->format('d');
      } else {
        $anioFin = null;
        $mesFin = null;
        $diaFin = null;
      }

      $datos = array(
        'descripcion' => $fecha->getDescripcion(),
        'año' => $fecha->getFechaIni()->format('Y'),
        'mes' => $fecha->getFechaIni()->format('m'),
        'dia' => $fecha->getFechaIni()->format('d'),
        'añoFin' => $anioFin,
        'mesFin' => $mesFin,
        'diaFin' => $diaFin
      );

      array_push($datosTodo, $datos);
    }

    $json = json_encode($datosTodo, JSON_UNESCAPED_UNICODE);


    return new Response($json);
  }


}

?>