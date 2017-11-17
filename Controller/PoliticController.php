<?php

namespace Controller;

use Library\Controller;
use Library\Request;
use Library\Pagination\Pagination;
use Model\BookRepository;
use Model\Entity\Feedback;
use Model\FeedbackRepository;
use Model\Form\FeedbackForm;
use Library\Session;

class PoliticController extends Controller
{
    const NEWS_PER_PAGE = 5;
    
    public function indexAction(Request $request){
        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };
        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();

        $repository = $this->get('repository')->getRepository('Articles');
        $categogy_articles=[];
        $all_tags=$repository->findAllTags();

        $analytic_articles=$repository->findAllAnalytics();
        $categories_count=$repository->countCategories();
        $categories_names= $repository->getCategories_names();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findAllActive(($currentPage - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE,$category_id);
        $repository = $this->get('repository')->getRepository('Articles');
        $latest_analytic_articles=$repository->findLatestAnalytics(($currentPage - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE);

        
        $pagination = new Pagination([
            'itemsCount' => $count, 
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $currentPage
        ]);
        
        
        $data = [
            'category_articles' => $category_articles,
            'categories_names' =>$categories_names,
            'pagination' => $pagination, 
            'articles' => $articles,
            'category_id' =>$category_id,
            'categories_ids' =>$categories_ids,
            'analytic_articles' =>$analytic_articles,
            'latest_analytic_articles' =>$latest_analytic_articles,
            'all_tags' =>$all_tags,
            'top5FeedbackAuthors' =>$top5FeedbackAuthors

        ];
        return $this->render('index.phtml', $data);    
    }
    public function indexAnalyticsAction(Request $request){

        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();

        $repository = $this->get('repository')->getRepository('Articles');
        $categogy_articles=[];
        $categories_count=$repository->countCategories();
        $analytic_articles=$repository->findAllAnalytics();
        $all_tags=$repository->findAllTags();


        $categories_names= $repository->getCategories_names();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findAllActive(($currentPage - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE,$category_id);
        $repository = $this->get('repository')->getRepository('Articles');
        $latest_analytic_articles=$repository->findLatestAnalytics(($currentPage - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE);


        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $currentPage
        ]);


        $data = [
            'category_articles' => $category_articles,
            'categories_names' =>$categories_names,
            'pagination' => $pagination,
            'articles' => $articles,
            'category_id' =>$category_id,
            'categories_ids' =>$categories_ids,
            'analytic_articles' =>$analytic_articles,
            'latest_analytic_articles' =>$latest_analytic_articles,
            'all_tags' =>$all_tags,
            'top5FeedbackAuthors' =>$top5FeedbackAuthors


        ];

        return $this->render('analytic.phtml', $data);
    }


    public function showAction(Request $request){
        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();

        $repository = $this->get('repository')->getRepository('Articles');
        $categories_count=$repository->countCategories();
        $analytic_articles=$repository->findAllAnalytics();
        $all_tags=$repository->findAllTags();


        $categories_names= $repository->getCategories_names();
        $categories_ids=$repository->getCategories_ids();

        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };
        $category_id= $request->get('category_id',1);



        $currentArticleId = $request->get('article_id', 1);
        $repository = $this->get('repository')->getRepository('Politics');
        $article = $repository->findArticle($currentArticleId);

        $repository = $this->get('repository')->getRepository('Articles');
        $latest_analytic_articles=$repository->findLatestAnalytics(0,4);
        $repository = $this->get('repository')->getRepository('Politics');

        $form = new FeedbackForm($request);
        Session::setFlash('Leave your comment');
        if ($request->isPost()) {
            if ($form->isValid()) {
                $repository = $this->get('repository')->getRepository('Politics');
                $feedback = (new Feedback())->setFromForm($form);
                if (\Library\Session::has('user') ){
                    $repository->save($feedback,$currentArticleId);
                    Session::setFlash('Your comment was added');
                    }else{
                        \Library\Session::setFlash('Please <a href="/login"> login </a> to leave the message');
                    }
//                $this->get('router')->redirectToRoute('politics_articles');

            }else {
                Session::setFlash('Fill the fields properly');
            }


        }
        $Session_flash=Session::getFlash();

        if ($request->post('rating') ){
            $rating_info= explode(',',$request->post('rating'));

            $rating= $rating_info[0];

            $feedback_id= $rating_info[1];

            $user_id= $rating_info[2];

            $repository = $this->get('repository')->getRepository('Feedback');
            $repository->rate_feedback($feedback_id,$user_id,$rating);
        }

        $repository = $this->get('repository')->getRepository('Politics');
        $messages=$repository->load($currentArticleId);

        $rating_info=[];
        //$rating info consist of 2 parameters: 1st is +1 or -1 to rating, 2nd is current_article_id


        $data = [

            'article' => $article,
            'form' => $form,
            'Session_flash' => $Session_flash,
            'messages' => $messages,
            'category_articles' => $category_articles,
            'categories_names' =>$categories_names,
            'category_id' =>$category_id,
            'categories_ids' =>$categories_ids,
            'analytic_articles' =>$analytic_articles,
            'latest_analytic_articles' =>$latest_analytic_articles,
            'all_tags' =>$all_tags,
            'rating_info' =>$rating_info,
            'top5FeedbackAuthors' =>$top5FeedbackAuthors


        ];




        return $this->render('article.phtml', $data);

    }

    public function showAnalyticAction(Request $request){

        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();

        $repository = $this->get('repository')->getRepository('Articles');
        $analytic_articles=$repository->findAllAnalytics ();
        $categogy_articles=[];
        $all_tags=$repository->findAllTags();

        $categories_count=$repository->countCategories();


        $categories_names= $repository->getCategories_names();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findAllActive(($currentPage - 1) * self::NEWS_PER_PAGE, self::NEWS_PER_PAGE,$category_id);

        $repository = $this->get('repository')->getRepository('Articles');
        $latest_analytic_articles=$repository->findLatestAnalytics(0,4);
        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $currentPage
        ]);


        $data = [
            'category_articles' => $category_articles,
            'categories_names' =>$categories_names,
            'pagination' => $pagination,
            'articles' => $articles,
            'category_id' =>$category_id,
            'categories_ids' =>$categories_ids,
            'latest_analytic_articles' =>$latest_analytic_articles,
            'analytic_articles' =>$analytic_articles,
            'all_tags' =>$all_tags,
            'top5FeedbackAuthors' =>$top5FeedbackAuthors


        ];


        return $this->render('analytic.phtml', $data);

    }

    public function showByTagAction(Request $request){
        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();

        $repository = $this->get('repository')->getRepository('Articles');
        $categogy_articles=[];
        $all_tags=$repository->findAllTags();
        $categories_count=$repository->countCategories();
        $categories_names= $repository->getCategories_names();
        $analytic_articles=$repository->findAllAnalytics();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);
        $tag= $request->get('tag',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findByTag($tag);



        $pagination = new Pagination([
            'itemsCount' => $count,
            'itemsPerPage' => self::NEWS_PER_PAGE,
            'currentPage' => $currentPage
        ]);

        $repository = $this->get('repository')->getRepository('Articles');
        $latest_analytic_articles=$repository->findLatestAnalytics(0,5);



        $data = [
            'category_articles' => $category_articles,
            'categories_names' =>$categories_names,
            'pagination' => $pagination,
            'articles' => $articles,
            'category_id' =>$category_id,
            'categories_ids' =>$categories_ids,
            'tag' =>$tag,
            'analytic_articles' =>$analytic_articles,
            'latest_analytic_articles' =>$latest_analytic_articles,
            'all_tags' =>$all_tags,
            'top5FeedbackAuthors' =>$top5FeedbackAuthors
        ];

        return $this->render('tags.phtml', $data);
    }

}
