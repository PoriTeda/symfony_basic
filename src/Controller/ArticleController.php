<?php
    namespace App\Controller;
    use App\Entity\Article;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TextareaType;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class ArticleController extends Controller{
    /**
     * @Route("/", name="article_list")
     * @Method({"GET"})
     */
    public function index(){
        //call getDoctrine->getRepository to get a table , findAll to get data
        $articles = $this->getDoctrine()->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig', array('articles' => $articles));
    }


    /**
     * @Route("/article/new", name="article_new")
     * @Method({"GET", "POST"})
     */
    public function new(Request $request)
    {
        $article = new Article();
        //create new form for create article
        //add( name input, type input, array to custom style), create with createFormBuilder() then getForm()
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array("attr" => array("class" => "form-control")))
            ->add('body', TextareaType::class, array(
                "attr" => array("class" => "form-control"),
                "required" => false
                ))
            ->add('save', SubmitType::class, array(
                "attr" => array("class" => "btn btn-primary mt-3"),
                "label" => "Create"
                ))->getForm();
        //handle submit action
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //get data submitted
            $article = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            //pass it into persist
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute("article_list");
        }

        //after get form then createView to show
        return $this->render("articles/new.html.twig" , array('form' => $form->createView()));
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        //create new form for create article
        //add( name input, type input, array to custom style), create with createFormBuilder() then getForm()
        $form = $this->createFormBuilder($article)
            ->add('title', TextType::class, array("attr" => array("class" => "form-control")))
            ->add('body', TextareaType::class, array(
                "attr" => array("class" => "form-control"),
                "required" => false
            ))
            ->add('save', SubmitType::class, array(
                "attr" => array("class" => "btn btn-primary mt-3"),
                "label" => "Update"
            ))->getForm();
        //handle submit action
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //get data submitted
            $article = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute("article_list");
        }

        //after get form then createView to show
        return $this->render("articles/edit.html.twig" , array('form' => $form->createView()));
    }

    /**
     * @Route("/article/{id}", name="article_show")
     */
    public function show($id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        return $this->render("articles/show.html.twig",array("article" => $article));
    }

    /**
     *@Route("/article/delete/{id}")
     * @Method({"DELETE"})
     */
    public  function delete(Request $request,$id)
    {
        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);

        $entityManager = $this->getDoctrine()->getManager();
        //pass it into remove
        $entityManager->remove($article);
        //delete item in db
        $entityManager->flush();

        //response send back fetch result in main.js file then reload the page
        $response = new Response();
        $response->send();
    }

    /**
     * @Route("/article/save")
     */
//    public function save()
//    {
//            //Manage all entity
//            $entityManager = $this->getDoctrine()->getManager();
//
//            $article = new Article();
//            $article->setTitle('Article Two');
//            $article->setBody('Article Two Body');
//
//            //pass article vao persist, nhung muon save tren database thi dung flush()
//            $entityManager->persist($article);
//            $entityManager->flush();
//            return new Response('Save article with the id of '.$article->getId());
//    }
}