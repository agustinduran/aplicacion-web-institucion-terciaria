<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Alumno;

class StudentController extends Controller
{
    /**
     * Método encargado de obtener el listado de cuentas de alumno de la BD
     * @Route("/alumnos", name="gestion-alumnos")
     * @Method({"GET"})
    */
    public function index(){
      $repository = $this->getDoctrine()->getRepository(Alumno::Class);
      $alumnos = $repository->findAll();
      return $this->render('alumnos/alumnos.html.twig', array('alumnos' => $alumnos ));
    }

    /**
     * Método encargado de crear una cuenta de alumno.
     *
     * @Route("/alumnos/crear", name="crear-alumno")
    */
    public function create(){
      $datos = array(
        'codigo' => '',
        'email' => '',
        'contrasena' => ''
      );

      // Array para mensajes de error y variable para mensaje de confirmación.
      $error = array();
      $valid = 0;

      // Validación del formulario
      if (!empty($_POST)){

        // Verifica que los campos no estén vacios
        $valCodigo = !empty($_POST["_codigo"]);
        $valEmail = !empty($_POST["_email"]);
        // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
        $codigo = $_POST["_codigo"];
        $email = $_POST["_email"];
        // Valida que no exista una cuenta con el mismo código
        $entityManager = $this->getDoctrine()->getManager();
        $existeCuenta = $entityManager->getRepository(Alumno::Class)->find($codigo) instanceof Alumno;

        //  Valida que el codigo de alumno exista
        $existeLegajo = false;

        $dirDbfAlumnos = $this->get('kernel')->getProjectDir() . '\public\dbf\ALUMNOS.dbf';
        $conDbf = dbase_open( $dirDbfAlumnos, 0);
        if ($conDbf){
          $nRegistros = dbase_numrecords($conDbf);
          for($i = $nRegistros;$i >= 1;$i --){
            $codAlumno = dbase_get_record($conDbf,$i);
            if ($codAlumno[0] == $codigo){
              $existeLegajo = true;
              break;
            }
          }
        }

        if ($valCodigo && $valEmail && !$existeCuenta && $existeLegajo){

          // Genera una contraseña aleatoria
          $largoPass = 6;//random_int(8, 10);
          $caracteres = '0123456789abcdefghijkmnopqrstuvwxyz';
          $largoCaracteres = strlen($caracteres);
          $password = '';
          for ($i = 0; $i < $largoPass; $i++) {
              $password .= $caracteres[rand(0, $largoCaracteres - 1)];
          }
          $password_encriptado = md5($password);

          $alumno = new Alumno();
          $alumno->setCodigo($codigo);
          $alumno->setEmail($email);
          $alumno->setCambiopass(false);
          $alumno->setPassword($password_encriptado);


          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($alumno);
          $entityManager->flush();

          // Envia codigo de alumno y contraseña para mostrarlos en pantalla
          $datos['codigo'] = $codigo;
          $datos['contrasena'] = $password;
          $valid = 1;
        } else {
          // envia un array con datos para los mensajes de error
          $error = array(
            'codigo' => $valCodigo,
            'email' => $valEmail,
            'cuenta' => $existeCuenta,
            'existeLegajo' => $existeLegajo
          );
          // envia un array con los datos del formulario para recargarlos
          $datos['codigo'] = $codigo;
          $datos['email'] = $email;
        }
      }


      return $this->render('alumnos/crear-alumno.html.twig', array (
        'datos' => $datos,
        'error' => $error,
        'valid' => $valid
      ));
    }

    /**
     * Método encargado de actualizar una cuenta de alumno.
     *
     * @Route("/alumnos/actualizar/{codigo}", name="update-alumno")
    */
    public function update($codigo){
      $entityManager = $this->getDoctrine()->getManager();
      $alumno = $entityManager->getRepository(Alumno::Class)->find($codigo);
      $codigo = $alumno->getCodigo();
      $datos = array(
        'email' => $alumno->getEmail(),
        'cambiopass' => $alumno->getCambiopass(),
        'contrasena' => ""
      );

      // Array para mensajes de error y variable para mensaje de confirmación.
      $error = array();
      $valid = 0;

      // Validación del formulario
      if (!empty($_POST)){
        // Verifica que los campos no estén vacios
        $valEmail = !empty($_POST["_email"]);
        // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
        $email = $_POST["_email"];
        if (!empty($_POST["_cambiopass"]))
          $cambiopass = $_POST["_cambiopass"];
        else
          $cambiopass = 0;

        if ($valEmail){
          $alumno->setEmail($email);
          $alumno->setCambiopass($cambiopass);

          // Al habilitar un cambio de contraseña, volverla aleatoria
          if ($cambiopass == false){
            // Genera una contraseña aleatoria
            $largoPass = 6; //random_int(8, 10);
            $caracteres = '0123456789abcdefghijkmnopqrstuvwxyz';
            $largoCaracteres = strlen($caracteres);
            $password = '';
            for ($i = 0; $i < $largoPass; $i++) {
              $password .= $caracteres[rand(0, $largoCaracteres - 1)];
            }
            $password_encriptado = md5($password);
            $alumno->setPassword($password_encriptado);
            $datos['contrasena'] = $password;
          }

          $entityManager->flush();
          $valid = 1;
        } else {
          // envia un array con datos para los mensajes de error
          $error = array(
            'email' => $valEmail
          );
        }

        // envia un array con los datos del formulario para recargarlos
        $datos['email'] = $email;
        $datos['cambiopass'] = $cambiopass;
      }


      return $this->render('alumnos/update-alumno.html.twig', array (
        'codigo' => $codigo,
        'datos' => $datos,
        'error' => $error,
        'valid' => $valid
      ));
    }

   /** Método encargado de eliminar un anuncio
    *
    * @Route("/alumnos/eliminar/{codigo}", name="delete-alumno")
   */
    public function delete($codigo){
      $entityManager = $this->getDoctrine()->getManager();
      $alumno = $entityManager->getRepository(Alumno::class)->find($codigo);

      if ($alumno){
        $entityManager->remove($alumno);
        $entityManager->flush();
      }

      $repository = $this->getDoctrine()->getRepository(Alumno::Class);
      $alumnos = $repository->findAll();

      return $this->render('alumnos/alumnos.html.twig', array('alumnos' => $alumnos ));

    }

  /** Método encargado de iniciar sesión de una cuenta de alumno
  *
  * @Route("/login-alumno/", methods={"GET"}, name="login-alumno")
  */
  public function login($codigo = 0, $password = 0){
    $request = Request::createFromGlobals();
    $codigo = $request->get('codigo');
    $password = $request->get('password');

    $entityManager = $this->getDoctrine()->getManager();
    $alumno = $entityManager->getRepository(Alumno::class)->find($codigo);
    $datos = array(
      'flag' => false,
      'mensaje' => "",
      'cambioPass' => false
    );

    // Verifica si el alumno esta registrado
    if (!is_null($alumno)){
      $datos['cambioPass'] = $alumno->getCambiopass();
    }

    // Verifica si el alumno esta registrado antes de verificar la contraseña
    if (!is_null($alumno)){
      if ($password == $alumno->getPassword()){
        $datos['flag'] = true;
      } else {
        $datos['mensaje'] = "Contraseña incorrecta.";
      }
    } else {
      $datos['mensaje'] = "Código de alumno o contraseña incorrectos";
    }

    $json = json_encode($datos, JSON_UNESCAPED_UNICODE);
    return new Response($json);
  }

  /** Método encargado de cambiar la contraseña de una cuenta de alumno
  *
  * @Route("/alumno-changepass/", methods={"GET"}, name="alumno-change-pass")
  */
  public function changePass($codigo = 0, $nuevoPass = 0){
    $request = Request::createFromGlobals();
    $codigo = $request->get('codigo');
    $nuevoPass = $request->get('nuevoPass');

    $entityManager = $this->getDoctrine()->getManager();
    $alumno = $entityManager->getRepository(Alumno::class)->find($codigo);

    $flag = false;
    $mensaje = "";

    if ($nuevoPass !== 0){
      $alumno->setPassword($nuevoPass);
      $alumno->setCambiopass(true);
      $entityManager->flush();
      $flag = true;
      $mensaje = "Cambio de contraseña realizado correctamente.";
    } else {
      echo $nuevoPass;
      $mensaje = "Ingrese una contraseña válida.";
    }

    $json = array("flag" => $flag, "mensaje" => $mensaje);

    return new Response(json_encode($json, JSON_UNESCAPED_UNICODE));
  }
}

?>