<?php
namespace App\Controller;

use App\Entity\Empresa;
use App\Entity\Tienda;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use function PHPUnit\Framework\returnSelf;

class TiendaController extends AbstractController
{  
    /**
     * @Route("/tienda/buscar/{texto}", name="buscar_tienda")
     */
    public function buscar(ManagerRegistry $doctrine, $texto): Response{
        $repositorio = $doctrine-> getRepository(Tienda::class);

        $tiendas = $repositorio->findByName($texto);
        
        return $this-> render('lista_tiendas.html.twig', [
            'tiendas' => $tiendas
        ]);    
    }
    /**
     * @Route("/tienda/insertar", name="insertar_tienda")
     */
    public function insertar(ManagerRegistry $doctrine)
    {
        $entityManager = $doctrine->getManager();
        foreach($this->tiendas as $c){
            $tienda = new Tienda();
            $tienda->setNombre($c["nombre"]);
            $tienda->setTelefono($c["telefono"]);
            $tienda->setLugar($c["lugar"]);
            $entityManager->persist($tienda);
        }

        try
        {
            $entityManager->flush();
            return new Response("Tiendas insertadas");
        }catch (\Exception $e) {
            return new Response("Error insertando objetos");
        }
    }
    /**
     * @Route("/tienda/update/{id}/{nombre}",name="modificar_tienda")
     */
    public function update(ManagerRegistry $doctrine, $id, $nombre): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Tienda::class);
        $tienda = $repositorio->find($id);
        if ($tienda){
            $tienda->setNombre($nombre);
            try{
                $entityManager->flush();
                return $this->render('ficha_tienda.html.twig',[
                    'tienda' => $tienda
                ]);
            } catch (\Exception $e) {
                return new Response("Error insertando objetos");
            }
        }else
        return $this->render('ficha_tienda.html.twig', [
            'tienda' => null
        ]);
    }
    /**
     * @Route("/tienda/delete/{id}",name="eliminar_tienda")
     */
    public function delete(ManagerRegistry $doctrine, $id): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Tienda::class);
        $tienda = $repositorio->find($id);
        if ($tienda){
            try{
                $entityManager->remove($tienda);
                $entityManager->flush();
                return new Response("Tienda eliminada");
            } catch (\Exception $e) {
                return new Response("Error eliminanado objeto");
            }
        }else
            return $this->render('ficha_tienda.html.twig', [
                'tienda' => null
        ]);
    }
    /**
     * @Route("/tienda/insertarConEmpresa", name="insertar_con_empresa_tienda")
     */
    public function insertarConEmpresa(ManagerRegistry $doctrine): Response{
        $entityManager = $doctrine->getManager();
        $empresa = new Empresa();

        $empresa->setNombre("Auchan");
        $tienda = new Tienda();

        $tienda->setNombre("Alcampo");
        $tienda->setTelefono("679939493");
        $tienda->setLugar("Salera Madrid");
        $tienda->setEmpresa($empresa);

        $entityManager->persist($empresa);
        $entityManager->persist($tienda);

        $entityManager->flush();
        return $this->render('ficha_tienda.html.twig', [
            'tienda' => $tienda
        ]);
    }
    /**
     * @Route("/tienda/insertarSinEmpresa", name="insertar_sin_empresa_tienda")
     */
    public function insertarSinEmpresa(ManagerRegistry $doctrine): Response{
        $entityManager = $doctrine->getManager();
        $repositorio = $doctrine->getRepository(Empresa::class);

        $empresa = $repositorio->findOneBy(["nombre" => "Auchan"]);

        $tienda = new Tienda();

        $tienda->setNombre("Alcampo");
        $tienda->setTelefono("679939493");
        $tienda->setLugar("Salera Barcelona");
        $tienda->setEmpresa($empresa);

        $entityManager->persist($tienda);

        $entityManager->flush();
        return $this-> render('ficha_tienda.html.twig',[
            'tienda' => $tienda
        ]);
    }
    /**
     * @Route("/tienda/nuevo", name="nueva_tienda")
     */
    public function nuevo(ManagerRegistry $doctrine, Request $request): Response {
        $tienda = new Tienda();

        $formulario = $this->createFormBuilder($tienda)
        ->add('nombre', TextType::class)
        ->add('telefono', TextType::class)
        ->add('lugar', TextType::class)
        ->add('empresa', EntityType::class, array(
            'class'=>Empresa::class,
            'choice_label' => 'nombre',))
        ->add('save', SubmitType::class, array('label' => 'Enviar'))
        ->getForm();
        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()){
            $tienda = $formulario->getData();
            
            $entityManager = $doctrine->getManager();
            $entityManager->persist($tienda);
            $entityManager->flush();
            return $this->render('ficha_tienda.html.twig', ["tienda" => $tienda]);
        }
        return $this-> render('nuevo.html.twig', array(
            'formulario' => $formulario->createView()   
        ));
    }
    /**
     * @Route("/tienda/editar/{codigo}", name="editar_tienda", requirements={"codigo"="\d+"})
     */
    public function editar(ManagerRegistry $doctrine, Request $request, $codigo){

        $repositorio = $doctrine->getRepository(Tienda::class);
        $tienda = $repositorio->find($codigo);

        $formulario = $this->createFormBuilder($tienda)
            ->add('nombre', TextType::class)
            ->add('telefono', TextType::class)
            ->add('lugar', TextType::class)
            ->add('empresa', EntityType::class, array(
                'class'=>Empresa::class,
                'choice_label' => 'nombre',))
            ->add('save', SubmitType::class, array('label' => 'Enviar'))
            ->getForm();

        $formulario->handleRequest($request);

        if ($formulario->isSubmitted() && $formulario->isValid()){
            $tienda = $formulario->getData();
            $entityManager = $doctrine->getManager(); 
            $entityManager->persist($tienda);
            $entityManager->flush();
        }
        return $this->render('editar.html.twig', array(
            'formulario' => $formulario->createView()
        ));
    }
}
?>