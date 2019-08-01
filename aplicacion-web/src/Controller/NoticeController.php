<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Anuncios;

class NoticeController extends Controller{
  // anotacion para definir la ruta y el metodo que va a recibir desde aca, sin tener que ir a routes.yaml

  /**
  * @Route("/cartelera", name="cartelera")
  * @Method({"GET"})
  */
  public function index(){

    $repository = $this->getDoctrine()->getRepository(Anuncios::Class);
    $anuncios = $repository->findAll();

    return $this->render('cartelera/cartelera.html.twig', array('anuncios' => $anuncios ));
  }

  /**
  * @Route("/cartelera/crear", name="crear-anuncio")
  * @Method({"GET"})
  */
  public function create(){
    $datos = array(
      'contenido' => '',
      'fechaIni' => '',
      'fechaFin' => '',
      'titulo' => '',
      'enviaNotif' => ''
    );

    // Array para mensajes de error y variable para mensaje de confirmación.
    $error = array();
    $valid = 0;

    // Validación del formulario
    if (!empty($_POST)){

      // Verifica que los campos no estén vacios
      $valTitulo = !empty($_POST["_titulo"]);
      $valContenido = !empty($_POST["_contenido"]);
      $valFechaIni = !empty($_POST["_fecha-ini"]);
      $valFechaFin = !empty($_POST["_fecha-fin"]);
      // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
      $titulo = $_POST["_titulo"];
      $contenido = $_POST["_contenido"];
      $fechaIni = new \DateTime($_POST["_fecha-ini"]);
      $fechaFin = new \DateTime($_POST["_fecha-fin"]);
      $enviaNotif = !empty($_POST["notif"]);
      // Verifica que la fecha de fin sea mayor a la de inicio
      $diferencia = $fechaIni->diff($fechaFin);
      $diferencia = $diferencia->format('%r%a');


      if ($valTitulo && $valContenido && $valFechaIni && $valFechaFin && $diferencia >= 0){
        $fechaPubli = new \DateTime();
        $visible = true;

        $anuncio = new Anuncios();
        $anuncio->setTitulo($titulo);
        $anuncio->setContenido($contenido);
        $anuncio->setFechaPubli($fechaPubli);
        $anuncio->setFechaIni($fechaIni);
        $anuncio->setFechaFin($fechaFin);
        $anuncio->setVisible($visible);

        // Si no tiene que enviar notificacion, crea anuncio directamente
        if ($enviaNotif != 1){
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($anuncio);
          $entityManager->flush();
          $valid = 1;
        }

        // Envio de notificacion
        if ($enviaNotif == 1){
          require_once __DIR__ . '/../../firebase/Firebase.php';
          require_once __DIR__ . '/../../firebase/Push.php';

          $firebase = new Firebase();
          $push = new Push();
          $push_type = 'topic';
          $push->setAsunto($titulo);
          $push->setDescripcion($contenido);
          $push->setFecha($fechaIni->format('Y-m-d'));
          $push->setIsBackground(FALSE);

          $json = '';
          $response = '';

          // mensaje para todos/particular
          if ($push_type == 'topic') {
            $json = $push->getPush();
            $response = $firebase->sendToTopic('global', $json);
          } else if ($push_type == 'individual') {
            $json = $push->getPush();
            $regId = isset($_GET['regId']) ? $_GET['regId'] : '';
            $response = $firebase->send($regId, $json);
          }

          // Crea el anuncio al enviar la notificacion
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($anuncio);
          $entityManager->flush();
          $valid = 1;
        }
      } else {
        // Error si el formulario esta incompleto
        // Envia mensajes de error para los campos incompletos
        $error = array(
          'contenido' => $valContenido,
          'fechaIni' => $valFechaIni,
          'fechaFin' => $valFechaFin,
          'titulo' => $valTitulo,
          'diferencia' => $diferencia
        );
        // envia un array con los datos del formulario para recargarlos
        $datos['contenido'] = $contenido;
        if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
        if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');
        $datos['enviaNotif'] = $enviaNotif;
        $datos['titulo'] = $titulo;
      }
    }

   // return new Response('Saved new anuncio $anuncio with id '.$anuncio->getId());
    return $this->render('cartelera/crear-anuncio.html.twig', array(
      'error' => $error,
      'valid' => $valid,
      'datos' => $datos)
    );
  }

  /**
  * @Route("/cartelera/actualizar/{id}", name="update-anuncio")
  */

   public function update($id){

     $entityManager = $this->getDoctrine()->getManager();
     $anuncio = $entityManager->getRepository(Anuncios::class)->find($id);

     $datos = array(
      'contenido' => $anuncio->getContenido(),
      'fechaIni' => $anuncio->getFechaIni()->format('Y-m-d'),
      'fechaFin' => $anuncio->getFechaFin()->format('Y-m-d')
    );

    $error = array();
    $valid = 0;

    // Validación del formulario
    if (!empty($_POST)){

      // Verifica que los campos no estén vacios
      $valContenido = !empty($_POST["_contenido"]);
      $valFechaIni = !empty($_POST["_fecha-ini"]);
      $valFechaFin = !empty($_POST["_fecha-fin"]);
      // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
      $contenido = $_POST["_contenido"];
      $fechaIni = new \DateTime($_POST["_fecha-ini"]);
      $fechaFin = new \DateTime($_POST["_fecha-fin"]);
      // Verifica que la fecha de fin sea mayor a la de inicio
      $diferencia = $fechaIni->diff($fechaFin);
      $diferencia = $diferencia->format('%r%a');


      if ($valContenido && $valFechaIni && $valFechaFin && $diferencia >= 0){
        $visible = true;

        $anuncio->setContenido($contenido);
        $anuncio->setFechaIni($fechaIni);
        $anuncio->setFechaFin($fechaFin);
        $anuncio->setVisible($visible);

        $entityManager->flush();

        $valid = 1;
      } else {
        // En caso de error, envia array indicando los campos con errores.

        $error = array(
          'contenido' => $valContenido,
          'fechaIni' => $valFechaIni,
          'fechaFin' => $valFechaFin,
          'diferencia' => $diferencia
        );
      }

      // Guarda los datos del formulario en un array para recargarlos
      $datos['contenido'] = $contenido;
      if ($valFechaIni)$datos['fechaIni'] = $fechaIni->format('Y-m-d');
      if ($valFechaFin)$datos['fechaFin'] = $fechaFin->format('Y-m-d');
    }


     return $this->render('cartelera/update-anuncio.html.twig', array(
       'id' => $id,
       'error' => $error,
       'datos' => $datos,
       'valid' => $valid
     ));
   }

   /** Método encargado de eliminar un anuncio
    *
    * @Route("/cartelera/eliminar/{id}", name="delete-anuncio")
   */

  public function delete($id){

    $entityManager = $this->getDoctrine()->getManager();
    $anuncio = $entityManager->getRepository(Anuncios::class)->find($id);

    if ($anuncio){
      $entityManager->remove($anuncio);
      $entityManager->flush();
    }

    $repository = $this->getDoctrine()->getRepository(Anuncios::Class);
    $anuncios = $repository->findAll();

    return $this->render('cartelera/cartelera.html.twig', array('anuncios' => $anuncios ));
  }

   /** Método encargado de generar un json conteniendo los anuncios
    *
    * @Route("/info/anuncios", name="anuncios")
   */

  public function show(){
    $repository = $this->getDoctrine()->getRepository(Anuncios::Class);
    $anuncios = $repository->findAll();
    $datosTodo = array();

    foreach ($anuncios as $anuncio){
      $fecha = $anuncio->getFechaIni()->format('Y-m-d');
      if (!is_null($anuncio->getFechaFin())){
        $fecha = $fecha." al ".$anuncio->getFechaFin()->format('Y-m-d');
      }

      $datos = array(
        'asunto' => $anuncio->getTitulo(),
        'descripcion' => $anuncio->getContenido(),
        'fecha' => $fecha
      );

      array_push($datosTodo, $datos);
    }

    $json = json_encode($datosTodo, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);


    return new Response($json);
  }

}


?>