<?php
    namespace App\Controller;
    use App\Entity\Article;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\Routing\Annotation\Route;
    use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use App\Form\ArticleType;
class ArticleController extends AbstractController{
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

        $form = $this->createForm(ArticleType::class, $article);

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
        return $this->render('articles/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/article/edit/{id}", name="article_edit")
     * @Method({"GET", "POST"})
     */
    public function edit(Request $request, $id)
    {

        $article = $this->getDoctrine()->getRepository(Article::class)->find($id);
        $form = $this->createForm(ArticleType::class, $article);
        //handle submit action
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            //get data submitted
            $form->getData();

            $entityManager = $this->getDoctrine()->getManager();

            $entityManager->flush();

            return $this->redirectToRoute("article_list");
        }

        //after get form then createView to show
        return $this->render('articles/edit.html.twig', [
            'form' => $form->createView(),
        ]);
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