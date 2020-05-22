<?php

namespace EspritApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonReponse;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use EspritApiBundle\Entity\Post;
use EspritApiBundle\Entity\Commentaire;
use AppBundle\Entity\User;
use Symfony\Component\HttpFoundation\Response;


class BlogController extends Controller
{



    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        $reservations =$em->getRepository('EspritApiBundle:Post')->findAll();

       $normalizer = new ObjectNormalizer();
         $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $reservationss=array();
        foreach ($reservations as $reservation) {

            $r=array("id"=>$reservation->getId(),
                    "title"=>$reservation->getTitle(),
                    "description"=>$reservation->getDescription(),
               //     "photo"=>$reservation->getPhoto(),
                    "rating"=>$reservation->getRating(),
                     "postdate"=>$reservation->getPostdate(),
                 //   "datep"=>$reservation->getPostdate(),
                   // "nbr"=>$reservation->getNbrpost()
                    "nbrpost"=>$reservation->getNbrpost()
         //      "creator"=>$reservation->getCreator()
                );
                   
                   
            array_push($reservationss,$r);

        }
        $formatted=$serializer->normalize($reservationss);
        return new JsonResponse($formatted);

    }
    





    public function findAction($id)
    {
            $posts = $this->getDoctrine()->getManager()
                                         ->getRepository('EspritApiBundle:Post')
                                         ->find($id);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize( $posts); 
        return new JsonResponse($formatted);
    }









    public function newAction(Request $request)
    {
            $em = $this->getDoctrine()->getManager();
            $post = new Post();
    //      $file=$post->getPhoto();
     //      $filename= md5(uniqid()).'.'.$file->guessExtension();
     //    $file->move($this->getParameter('photos_directory'),$filename);
   //         $post->setPhoto($filename);
       //     $post->setCreator($this->getUser());
       //     $post->setNbrpost($post->getNbrpost()+1);
         //   $post->setPostdate(new \DateTime('now'));
         $post->setDescription($request->get('description'));
         $post->setTitle($request->get('title'));
         $post->setRating($request->get('rating'));
        $datenow=  new \DateTime();
    $post->setPostdate($datenow);
         $post->setPhoto($request->get('photo'));

            $em->persist($post);
            $em->flush();                               
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize( $post); 
        return new JsonResponse($formatted);
    }


    public function updateAction(Request $request,Post $event)
    {

       // $editForm = $this->createForm('BlogBundle\Form\PostType', $event);
       // $editForm->handleRequest($request);
        $event->setTitle($request->get('title'));
//        $event->setDateE($request->get('dateE'));
        $event->setDescription($request->get('description'));
        $event->setRating($request->get('rating'));
     //   $event->setPhoto($request->get('photo'));
     //   $event->setNameC($request->get('nameC'));
     //   $event->setNbplaces($request->get('nbplaces'));
        $this->getDoctrine()->getManager()->flush();
        
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($event);
        return new JsonResponse($formatted);











    }

    
    public function deleteAction($id,Request $request)
    {
        
        
        $em = $this->getDoctrine()->getManager();
        $Post = $em->getRepository('EspritApiBundle:Post')->find($id);
        $em->remove($Post);
        $em->flush();
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($Post);
        return new JsonResponse($formatted);

    }




     public function showdetailedAction($id,Request $request)
    {

        $em= $this->getDoctrine()->getManager();

        $post=$em->getRepository('EspritApiBundle:Commentaire')->findby(['post'=>$id]);
        $serializer = new Serializer([new ObjectNormalizer()]);
        $data = $serializer->normalize($post,null, ['attributes' => ['id','post'=>['id','creator','title','description','photo','postdate','rating','blocke']
        ,'user'=> ['id','username','username_canonical','email','email_canonical','enabled','salt','password','last_login','confirmation_token','password_requested_at','roles','first_name','telephone','age','idtrans']
        ,'content']]);
        $formatted = $serializer->normalize($data);
        return new JsonResponse($formatted);




    }


    public function addCommentAction(Request $request,$user,$id,$Con)
    { 

        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findPostByid($id);

            $userr = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user);
       
       

        $comment = new Commentaire();

        $comment->setUser($userr);
        $comment->setPost($post);
        $comment->setContent($Con);
        $datenow=  new \DateTime();
        $comment->setPostedAt($datenow);

        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $formatted = $serializer->normalize($comment);
        return new JsonResponse($formatted);



    }








    public function alluserAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users =$em->getRepository('EspritApiBundle:User')->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $userss=array();
        foreach ($users as $user) {

            $r=array("id"=>$user->getId(),
                     "firstname"=>$user->getFirstname(),
                     "username"=>$user->getUsername(),
              //     "password"=>$user->getPassword(),
                     "email"=>$user->getEmail(),
                     "role"=>$user->getRoles()

                   
                   
                );
                   
                   
            array_push($userss,$r);

        }
        $formatted=$serializer->normalize($userss);
        return new JsonResponse($formatted);

    }


    public function deleteuserAction($id,Request $request)
    {
        
        
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('EspritApiBundle:User')->find($id);
        $em->remove($user);
        $em->flush();
        $serializer = new Serializer([new ObjectNormalizer()]);
        $formatted = $serializer->normalize($user); 
        return new JsonResponse($formatted);
    }


    public function allcommentaireAction()
    {
        $em = $this->getDoctrine()->getManager();
        $users =$em->getRepository("EspritApiBundle:Commentaire")->findAll();

        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(2);
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $serializer = new Serializer($normalizers);
        $userss=array();
        foreach ($users as $user) {

            $r=array("idcommentaire"=>$user->getId(),
                     "content"=>$user->getContent()
                    // "description"=>$user->getPostedAt(),
                    //"post"=>$user->getPost()
                   
                );
                   
                   
            array_push($userss,$r);

        }
        $formatted=$serializer->normalize($userss);
        return new JsonResponse($formatted);

    }
    




}
