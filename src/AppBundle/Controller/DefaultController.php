<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function loginAction(Request $request,$username,$password)
    {
        $u = new User();
        $u->setUsername($username);
        // $username = $request->query->get($username);
        $u->setPassword($password);
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->findBy($username);
        if ($user) {
            $serializer = new Serializer([new ObjectNormalizer()]);
            $formatted = $serializer->normalize($user);
            return new JsonResponse($formatted);
        } else {
            return new Response("failed");
        }
    }


    public function registerAction(Request $request,$email,$username,$password,$role){
        $u = new User();
        $u->setEmail($email);
        $u->setUsername($username);
        $u->setPlainPassword($password);
        $u->addRole($role);
        $u->setEnabled(true);
        $em = $this->getDoctrine()->getManager();
        $em->persist($u);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($u);
        return new JsonResponse($formatted);
    }
}
