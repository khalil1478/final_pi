<?php

namespace BlogBundle\Controller;
use AppBundle\Entity\User;
use BlogBundle\BlogBundle;
use BlogBundle\Entity\Commentaire;
use BlogBundle\Entity\Post;
use BlogBundle\Form\PosteditType;
use BlogBundle\Form\PostType;
use BlogBundle\Repository\PostRepository;
use bonP\badgeBundle\Entity\Comment;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;


class BlogController extends Controller
{

    public function homeAction()
    {
        return $this->render('@Blog/Default/layout.html.twig');
    }

    public function loginAction()
    {
        return $this->render('@Blog/Default/login.html.twig');
    }

    public function registerAction()
    {
        return $this->render('@Blog/Default/register.html.twig');
    }


    


    public function addpostAction(Request $request)
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var UploadedFile $file
             */

            $em = $this->getDoctrine()->getManager();
            $file=$post->getPhoto();
            $filename= md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'),$filename);
            $post->setPhoto($filename);
            $post->setCreator($this->getUser());

            $post->setNbrpost($post->getNbrpost()+1);
            $post->setPostdate(new \DateTime('now'));
            $em->persist($post);
            $em->flush();


           
           
          
           $this->addFlash(
               'info',
               'post est crée avec succées'
           );


            return $this->redirectToRoute('acceuilblog');

        }
      
        return $this->render('@Blog/Default/addpost.html.twig', array(
            "Form" => $form->createView()
        ));

    


    }


    public function CountAction(Request $request)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $rep = $em->getRepository('BlogBundle:Post')->findBy(array('Idblog' => $id));
        $number = count($rep);
        return new JsonResponse(array('number' => $number, 'id' => $id));
    }


    public function acceuilblogAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAll();
        $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
      
      


        


        $paginator = $this->get('knp_paginator');
       $result = $paginator->paginate(
        $posts,
        
     
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );


    
        return $this->render('@Blog/Default/acceuilblog.html.twig', array(
            "posts" => $result,  "postsss"=>$postsss
        ));
    }


    public function deletepostAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Post = $em->getRepository('BlogBundle:Post')->find($id);
        $em->remove($Post);
        $em->flush();
        $this->addFlash('warning1', 'le blog est supprimée avec succées');
        return $this->redirectToRoute('acceuilblog');

    }

    public function updatepostAction(Request $request, $id)
    {
        /**
         * @var UploadedFile $file
         */


        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Post::class)->find($id);
        $transp = $this->createForm(PostType::class,$p);
        $transp->handleRequest($request);

        if ($transp->isSubmitted()) {


            $file = $p->getPhoto();
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $file->move($this->getParameter('photos_directory'), $filename);
            $p->setPhoto($filename);
            $p->setPostdate(new \DateTime('now'));
            $em->persist($p);
            $em->flush();

            $this->addFlash('success', 'le blog est crée est modifier');
            return $this->redirectToRoute('acceuilblog');

        }

        return $this->render('@Blog/Default/updatepost.html.twig', array(
            "Form2" => $transp->createView(), 'photo' =>$p->getPhoto()

        ));
    }


    public function detailedpostAction($id, Request $request)
    {

        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);





        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Post::class)->find($id);
        return $this->render('@Blog/Default/detailedpost.html.twig', array(
            'rating' =>$p->getRating(),
            'creator' =>$p->getCreator(),
            'title' => $p->getTitle(),
            'date' => $p->getPostdate(),
            'photo'=>$p->getPhoto(),
            'description' => $p->getDescription(),
            'posts' => $p,
            'comments' => $p,
            'id' => $p->getId()
        ));

        return $this->render('@Blog/Default/detailedpost.html.twig', array(
            "form" => $form->createView()));

    }

    public function addcommentAction(Request $request)
    {


       
        $post = $this->getDoctrine()
            ->getRepository(Post::class)
            ->findPostByid($request->request->get('post_id'));

            $em = $this->getDoctrine()->getManager();
            $p = $em->getRepository(Post::class)->find($post);



    


        $comment = new Commentaire();
        $comment->setUser($this->getUser());
        $comment->setPostedAt();
        $comment->setPost($post);
        $comment->setContent($request->request->get('comment'));
        $em = $this->getDoctrine()->getManager();
        $em->persist($comment);
        $em->flush();

        $this->addFlash(
            'info',
            'commentaire  est crée avec succées'
        );


       

        return $this->render('@Blog/Default/detailedpost.html.twig',array(
            
            'photo'=>$p->getPhoto(),
            'rating' =>$p->getRating(),
            'creator' =>$p->getCreator(),
            'title' => $p->getTitle(),
            'date' => $p->getPostdate(),
            'photo'=>$p->getPhoto(),
            'description' => $p->getDescription(),
            'posts' => $p,
            'comments' => $p,
            'id' => $p->getId()
        ));


    }

    public function deletecommentAction($id)
    {

      

        $em = $this->getDoctrine()->getManager();
        $comment = $em->getRepository('BlogBundle:Commentaire')->find($id);
        $em->remove($comment);
        $em->flush();


        $this->addFlash(
            'warning',
            'commentaire  est supprimé  avec succées'
        );
        
        return $this->redirectToRoute('acceuilblog');

    }


    public function updatecommentAction(Request $request, $id)
    {




        $em = $this->getDoctrine()->getManager();
        $transp = $em->getRepository(Commentaire::class)->find($id);








        if ($request->isMethod('POST')) {

           

            $transp->setPostedAt();
            $transp->setContent($request->request->get('comment'));


            $em->flush();


            $this->addFlash(
                'warning1',
                'commentaire  est modifier  avec succées'
            );
            return $this->redirectToRoute('acceuilblog');

          
        }

        return $this->render('@Blog/Default/updatecomment.html.twig', array('transp' => $transp));
    }

   


   

    

    public function likeAction(Commentaire $commentaire)
    {
        $user=$this->getUser();
        $commentaire->addLikes($user);
        $this->getUser()->addComm($commentaire);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('acceuilblog');

    }


    public function dislikeAction(Commentaire $commentaire)
    {

        $user=$this->getUser();
        $commentaire->addLikes($user);
        $this->getUser()->removeComm($commentaire);
        $this->getDoctrine()->getManager()->flush();
        return $this->redirectToRoute('acceuilblog');
    }






    public function searchtitreAction(Request $request)
    {
        $titre= $request->get('title');

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->cherchertitre($titre);

        if($posts== null)
        {
            $em = $this->getDoctrine()->getManager();
            $posts = $em->getRepository('BlogBundle:Post')->findAll();
            $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
            $this->addFlash(
                'warning1',
                "post  n'existe pas selon votre titre "
            );

        }
        $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
        
      
      
              

        


        $paginator = $this->get('knp_paginator');
       $result = $paginator->paginate(
        $posts,
        
     
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );


    
        return $this->render('@Blog/Default/acceuilblog.html.twig', array(
            "posts" => $result,  "postsss"=>$postsss
        ));
    }




    public function searchdescriptionAction(Request $request)
    {
        $description= $request->get('description');

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->chercherdescription($description);

        $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
      
      
        if($posts== null)
        {
            $em = $this->getDoctrine()->getManager();
            $posts = $em->getRepository('BlogBundle:Post')->findAll();
            $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
            $this->addFlash(
                'warning1',
                "post  n'existe pas selon votre description "
            );

        }  

        


        $paginator = $this->get('knp_paginator');
       $result = $paginator->paginate(
        $posts,
        
     
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );


    
        return $this->render('@Blog/Default/acceuilblog.html.twig', array(
            "posts" => $result,  "postsss"=>$postsss
        ));
    }



    public function searchdateAction(Request $request)
    {
        $postdate= $request->get('date');

        $em = $this->getDoctrine()->getManager();

        $posts = $em->getRepository('BlogBundle:Post')->chercherdate($postdate);

        if($posts== null)
        {
            $em = $this->getDoctrine()->getManager();
            $posts = $em->getRepository('BlogBundle:Post')->findAll();
            $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
            $this->addFlash(
                'warning1',
                "post  n'existe pas selon votre date "
            );

        }
        $postsss = $em->getRepository('BlogBundle:Post')->finddescpost();
        $paginator = $this->get('knp_paginator');
       $result = $paginator->paginate(
        $posts,
        
     
            $request->query->getInt('page', 1),
            $request->query->getInt('limit', 3)
        );


    
        return $this->render('@Blog/Default/acceuilblog.html.twig', array(
            "posts" => $result,  "postsss"=>$postsss
        ));
    }

















































    





    public function adminAction()
    {



        $nbrpost = $this->getDoctrine()
        ->getRepository(Post::class)
        ->nbrpost();

        $nbrcommentaire= $this->getDoctrine()
        ->getRepository(Commentaire::class)
        ->nbrcommentaire();
   


    





        
        return $this->render('@Blog/Admin/dashbord.html.twig', array(
            'nbrpost' => $nbrpost,'nbrcommentaire'=>$nbrcommentaire));
    }




    public function listpostadminAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository('BlogBundle:Post')->findAll();




        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
         $posts,
             $request->query->getInt('page', 1),
             $request->query->getInt('limit', 5)
         );
        return $this->render('@Blog/Admin/listpostadmin.html.twig', array(
            "posts" => $result));
    }

    public function adminlistcommentAction( Request $request){
        $em = $this->getDoctrine()->getManager();
   $posts = $em->getRepository('BlogBundle:Commentaire')->findAll();



   $paginator = $this->get('knp_paginator');
   $result = $paginator->paginate(
    $posts,
    
 
        $request->query->getInt('page', 1),
        $request->query->getInt('limit', 5)
    );







        return $this->render('@Blog/Admin/listcommentadmin.html.twig', array(
            'comments' => $result
        ));
    }



    public function deletepostadminAction($id, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $Post = $em->getRepository('BlogBundle:Post')->find($id);
        $em->remove($Post);
        $em->flush();

        return $this->redirectToRoute('adminlistpost');

    }


    public function adminlistfosuserAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $fosuser = $em->getRepository('AppBundle:User')->findAll();




        $paginator = $this->get('knp_paginator');
        $result = $paginator->paginate(
         $fosuser,
             $request->query->getInt('page', 1),
             $request->query->getInt('limit', 5)
         );
        return $this->render('@Blog/Admin/listfosuseradmin.html.twig', array(
            "fosuser" => $result));

    }




   public function  addpostadminAction ( Request $request)
   {
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);
    
    if ($form->isSubmitted() && $form->isValid()) {
        /**
         * @var UploadedFile $file
         */
        $em = $this->getDoctrine()->getManager();
        $file=$post->getPhoto();
        $filename= md5(uniqid()).'.'.$file->guessExtension();
        $file->move($this->getParameter('photos_directory'),$filename);
        $post->setPhoto($filename);
        $post->setCreator($this->getUser());
        $post->setNbrpost($post->getNbrpost()+1);
        $post->setPostdate(new \DateTime('now'));
        $em->persist($post);
        $em->flush();      
       $this->addFlash(
           'info',
           'post est crée avec succées'
       );
        return $this->redirectToRoute('adminlistpost');
    }
  
    return $this->render('@Blog/Admin/addpostadmin.html.twig', array(
        "Form" => $form->createView()
    ));
   }

   public function detailsadminpostAction($id ,Request $request)

   {
    $post = new Post();
    $form = $this->createForm(PostType::class, $post);
    $form->handleRequest($request);

    $em = $this->getDoctrine()->getManager();
    $p = $em->getRepository(Post::class)->find($id);
    return $this->render('@Blog/Admin/detailsadminpost.html.twig', array(
        'rating' =>$p->getRating(),
        'creator' =>$p->getCreator(),
        'title' => $p->getTitle(),
        'date' => $p->getPostdate(),
        'photo'=>$p->getPhoto(),
        'description' => $p->getDescription(),
        'posts' => $p,
      
        'id' => $p->getId()
    ));

    return $this->render('@Blog/Admin/detailsadminpost.html.twig', array(
        "form" => $form->createView()));



   }















   public function addcommentadminAction(Request $request)
   {


      
       $post = $this->getDoctrine()
           ->getRepository(Post::class)
           ->findPostByid($request->request->get('post_id'));

           $em = $this->getDoctrine()->getManager();
           $p = $em->getRepository(Post::class)->find($post);



   


       $comment = new Commentaire();
       $comment->setUser($this->getUser());
       $comment->setPostedAt();
       $comment->setPost($post);
       $comment->setContent($request->request->get('comment'));
       $em = $this->getDoctrine()->getManager();
       $em->persist($comment);
       $em->flush();

       $this->addFlash(
           'info',
           'commentaire  est crée avec succées'
       );


      

       return $this->redirectToRoute('adminlistcomment');
           
       

   }

  


  

   public function deletecommentadminAction($id, Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $comment = $em->getRepository('BlogBundle:Commentaire')->find($id);
       $em->remove($comment);
       $em->flush();

       return $this->redirectToRoute('adminlistcomment');

   }


   public function deletefosuseradminAction($id, Request $request)
   {
       $em = $this->getDoctrine()->getManager();
       $comment = $em->getRepository('AppBundle:User')->find($id);
       $em->remove($comment);
       $em->flush();

       return $this->redirectToRoute('adminlistfosuser');

   }


   public function detailsadminuserAction($id ,Request $request)

   {
    $user = new User();
    

    $em = $this->getDoctrine()->getManager();
    $p = $em->getRepository(User::class)->find($id);
    return $this->render('@Blog/Admin/detailsadminuser.html.twig', array(
        'firstname' =>$p->getFirstname(),
       
        'email' => $p->getEmail(),
        'telephone'=>$p->getTelephone(),
        'age' => $p->getAge(),
        'photo' => $p->getPhotouser(),     
        'id' => $p->getId()
    ));

   }



  public function statistiqueAction()

  {
      //1
    $pieArray=array( array("post", "commentaire"));

    $em=$this->getDoctrine()->getManager();
    $enseignes=$em->getRepository("BlogBundle:Post")->findAll();

    foreach ( $enseignes as $e)
    {
        $en=$em->getRepository("BlogBundle:Commentaire")->findBy(array("post"=>$e->getId()));

        array_push($pieArray,array((string)+ $e->getId(),count($en) ));

    }

    var_dump($pieArray);
    $pieChart = new PieChart();
    $pieChart->getData()->setArrayToDataTable($pieArray);
    $pieChart->getOptions()->setTitle('  nombres des commentaires  %  post');
    $pieChart->getOptions()->setHeight(500);
    $pieChart->getOptions()->setWidth(500);
    $pieChart->getOptions()->getTitleTextStyle()->setBold(true);
    $pieChart->getOptions()->getTitleTextStyle()->setColor('#009900');
    $pieChart->getOptions()->getTitleTextStyle()->setItalic(true);
    $pieChart->getOptions()->getTitleTextStyle()->setFontName('Arial');
    $pieChart->getOptions()->getTitleTextStyle()->setFontSize(20);



  
// 2


    return $this->render('@Blog/Admin/statistique.html.twig', array(
        'piechart' => $pieChart ));


  
  }

  //3
 

}
