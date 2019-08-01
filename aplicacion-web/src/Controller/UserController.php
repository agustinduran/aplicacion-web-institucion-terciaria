<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * Método encargado de obtener el listado de empleados de la BD
     * @Route("/usuarios", name="gestion-usuarios")
     * @Method({"GET"})
    */
    public function index(){
      $repository = $this->getDoctrine()->getRepository(User::Class);
      $usuarios = $repository->findAll();
      return $this->render('usuarios/usuarios.html.twig', array('usuarios' => $usuarios )); 
    }
    
    /** 
     * Método encargado de crear un nuevo usuario.
     *  
     * @Route("/usuarios/crear", name="crear-usuario")
    */
    public function create(UserPasswordEncoderInterface $encoder){
      $datos = array(
        'username' => '',
        'password' => '',
        'email' => ''
      );

      // Array para mensajes de error y variable para mensaje de confirmación.
      $error = array();        
      $valid = 0;

      // Validación del formulario
      if (!empty($_POST)){      

        // Verifica que los campos no estén vacios
        $valUsername = !empty($_POST["_username"]);
        $valPassword = !empty($_POST["_password"]);
        $valEmail = !empty($_POST["_email"]);  
        // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
        $username = $_POST["_username"];
        $password = $_POST["_password"];
        $email = $_POST["_email"];

        if ($valUsername && $valPassword && $valEmail){                        

          $usuario = new User();
          $usuario->setUsername($username);
          $usuario->setPassword($encoder->encodePassword($usuario, $password));
          $usuario->setEmail($email);
          $usuario->setRole('ROLE_EMPLOYEE');
          
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($usuario);        
          $entityManager->flush();

          $valid = 1;
        } else {
          // envia un array con datos para los mensajes de error
          $error = array(
            'username' => $valUsername,          
            'password' => $valPassword,
            'email' => $valEmail
          );        
          // envia un array con los datos del formulario para recargarlos
          $datos['username'] = $username;
          $datos['password'] = $password;
          $datos['email'] = $email;
        }
      }


      return $this->render('usuarios/crear-usuario.html.twig', array (
        'datos' => $datos,
        'error' => $error,
        'valid' => $valid
      ));
    }

    /** 
     * Método encargado de modificar una cuenta de alumno.
     *  
     * @Route("/usuarios/actualizar/{id}", name="update-usuario")
    */
    public function update($id){
      $entityManager = $this->getDoctrine()->getManager();
      $usuario = $entityManager->getRepository(User::Class)->find($id);
      $datos = array(
        'username' => $usuario->getUsername(),
        'email' => $usuario->getEmail()
      );

      // Array para mensajes de error y variable para mensaje de confirmación.
      $error = array();        
      $valid = 0;

      // Validación del formulario
      if (!empty($_POST)){      
        // Verifica que los campos no estén vacios
        $valUsername = !empty($_POST["_username"]);
        $valEmail = !empty($_POST["_email"]);  
        // Obtiene los datos para recargarlos al formulario, en caso de que este se envie incompleto
        $username = $_POST["_username"];
        $email = $_POST["_email"];

        if ($valUsername && $valEmail){                        
          $usuario->setUsername($username);
          $usuario->setEmail($email);
          $usuario->setRole('ROLE_EMPLOYEE');                    

          $entityManager->flush();
          $valid = 1;
        } else {
          // envia un array con datos para los mensajes de error
          $error = array(
            'username' => $valUsername,          
            'email' => $valEmail
          );                  
        }

        // envia un array con los datos del formulario para recargarlos
        $datos['username'] = $username;
        $datos['email'] = $email;        
      }


      return $this->render('usuarios/update-usuario.html.twig', array (
        'id' => $id,
        'datos' => $datos,
        'error' => $error,
        'valid' => $valid
      ));
    }

   /** Método encargado de eliminar un anuncio
    * 
    * @Route("/usuarios/eliminar/{id}", name="delete-usuario")
   */
    public function delete($id){
      $entityManager = $this->getDoctrine()->getManager();
      $usuario = $entityManager->getRepository(User::class)->find($id);

      if ($usuario){
        $entityManager->remove($usuario);
        $entityManager->flush();  
      } 

      $repository = $this->getDoctrine()->getRepository(User::Class);
      $usuarios = $repository->findAll();  

      return $this->render('usuarios/usuarios.html.twig', array('usuarios' => $usuarios ));

    }
}


?>