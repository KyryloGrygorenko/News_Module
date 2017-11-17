<?php

namespace Controller\Admin;

use Library\Controller;
use Library\Request;
use Library\Pagination\Pagination;
use Model\BookRepository;
use Model\Entity\Feedback;
use Model\FeedbackRepository;
use Model\Form\FeedbackForm;
use Library\Session;

class MainController extends Controller
{
    const NEWS_PER_PAGE = 5;
    const COMMENTS_PER_PAGE = 5;

    public function indexAction(Request $request){
        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };
        if($request->post('delete')){
            $category_name_to_delete=$request->post('delete');
            $repository = $this->get('repository')->getRepository('Politics');
            $repository->deleteCategory($category_name_to_delete);
        };
        if($request->post('category_name') ){
            $new_category_name=$request->post('category_name');
            $repository = $this->get('repository')->getRepository('Politics');
            $repository->addCategory($new_category_name);
//            $this->get('router')->redirect( $redirect_route );
        };
        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();
        $articles_limit=3;// how many top articles to show
        $topArticles=$repository->top3topics($articles_limit);

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
            'top5FeedbackAuthors' =>$top5FeedbackAuthors,
            'topArticles' =>$topArticles,

        ];
        if(session::get('admin')){
            return $this->render('index.phtml',$data);
        }else {
            return $this->render('tryagain.phtml',$data);
        }

    }

    public function add_articleAction(Request $request){
        if($request->post('article_title') ){
            $article_title=$request->post('article_title');
            $article_text=$request->post('article_text');
            $article_category_id=$request->post('article_category_id');
            $article_tags=$request->post('article_tags');
            $img=$request->post('img');
            $date = date("Y-m-d H:i:s");
            $article_analytics=$request->post('article_analytics');
            $repository = $this->get('repository')->getRepository('Articles');
            $repository->addArticle($article_title,$article_text,$article_category_id,$article_tags,$img,$article_analytics,$date);
//            $this->get('router')->redirect( $redirect_route );
        };
        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();
        $articles_limit=3;// how many top articles to show
        $topArticles=$repository->top3topics($articles_limit);

        $repository = $this->get('repository')->getRepository('Articles');
        $categogy_articles=[];
        $all_tags=$repository->findAllTags();

        $analytic_articles=$repository->findAllAnalytics();
        $categories_count=$repository->countCategories();
        $categories_names= $repository->getCategories_names();
        $categories_names_ids= $repository->getCategories_names_ids();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findAllArticles();
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
            'top5FeedbackAuthors' =>$top5FeedbackAuthors,
            'topArticles' =>$topArticles,
            'categories_names_ids' =>$categories_names_ids,

        ];
        if(session::get('admin')){
            return $this->render('add_article.phtml',$data);
        }else {
            return $this->render('tryagain.phtml',$data);
        }


    }
    public function edit_articleAction(Request $request){

        if($request->post('article_title') ){
            $article_title=$request->post('article_title');
            $article_tags=$request->post('article_tags');
            $img=$request->post('img');
            $id=$request->post('id');
            $repository = $this->get('repository')->getRepository('Articles');
            $repository->editArticle($article_title,$article_tags,$img,$id);
//            $this->get('router')->redirect( $redirect_route );
        };
        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();
        $articles_limit=3;// how many top articles to show
        $topArticles=$repository->top3topics($articles_limit);

        $repository = $this->get('repository')->getRepository('Articles');
        $categogy_articles=[];
        $all_tags=$repository->findAllTags();

        $analytic_articles=$repository->findAllAnalytics();
        $categories_count=$repository->countCategories();
        $categories_names= $repository->getCategories_names();
        $categories_names_ids= $repository->getCategories_names_ids();
        $categories_ids=$repository->getCategories_ids();
        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        $currentPage = $request->get('page', 1);
        $category_id= $request->get('category_id',1);

        $repository = $this->get('repository')->getRepository('Politics');
        $count = $repository->count($category_id);
        $articles = $repository->findAllArticles();
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
            'top5FeedbackAuthors' =>$top5FeedbackAuthors,
            'topArticles' =>$topArticles,
            'categories_names_ids' =>$categories_names_ids,

        ];
        if(session::get('admin')){
            return $this->render('edit_article.phtml',$data);
        }else {
            return $this->render('tryagain.phtml',$data);
        }


    }
    public function edit_feedbackAction(Request $request){

        if($request->post('feedback_message') ){
            $feedback_message=$request->post('feedback_message');
            $id=$request->post('id');
            $repository = $this->get('repository')->getRepository('Feedback');
            $repository->editFeedback($feedback_message,$id);
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $allFeedbacks=$repository->FindAllFeedbacks();

        $data = [
            'allFeedbacks' =>$allFeedbacks,

        ];
        if(session::get('admin')){
            return $this->render('edit_feedback.phtml',$data);
        }else {
            return $this->render('tryagain.phtml',$data);
        }


    }


    public function approve_feedbackAction(Request $request){

        if($request->post('id') ) {
            $id=$request->post('id');
            $repository = $this->get('repository')->getRepository('Feedback');
            $repository->ApproveFeedbackById($id);
        };

        $repository = $this->get('repository')->getRepository('Feedback');
        $FeedbacksToBeApproved=$repository->FeedbacksToBeApproved();

        $data = [
            'FeedbacksToBeApproved' =>$FeedbacksToBeApproved,

        ];
        if(session::get('admin')){
            return $this->render('approve_feedback.phtml',$data);
        }else {
            return $this->render('tryagain.phtml',$data);
        }

    }







public function ShowFeedbacksAction(Request $request){
    if($request->post('search') ){
        $tag=$request->post('search');
        $redirect_route='/politics/tags/?tag='. $tag;
        $this->get('router')->redirect( $redirect_route );
    };
    $repository = $this->get('repository')->getRepository('Feedback');
    $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();
    $articles_limit=3;// how many top articles to show
    $topArticles=$repository->top3topics($articles_limit);

    $user_id=$request->get('user_id');
    $allFeedbackByAuthor= $repository->FeedbackByAuthor($user_id);

    $repository = $this->get('repository')->getRepository('Articles');
    $categogy_articles=[];
    $all_tags=$repository->findAllTags();

    $categories_count=$repository->countCategories();
    $categories_names= $repository->getCategories_names();
    $categories_ids=$repository->getCategories_ids();
    for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
        $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
    };

    $currentPage = $request->get('page', 1);
    $user_id= $request->get('user_id',1);




    $repository = $this->get('repository')->getRepository('Feedback');
    $count = $repository->count($user_id);
    $comments = $repository->FeedbackByAuthorPagination(($currentPage - 1) * self::COMMENTS_PER_PAGE, self::COMMENTS_PER_PAGE,$user_id);

//    $repository = $this->get('repository')->getRepository('Articles');
//    $latest_analytic_articles=$repository->findLatestAnalytics(($currentPage - 1) * self::COMMENTS_PER_PAGE, self::COMMENTS_PER_PAGE);


    $pagination = new Pagination([
        'itemsCount' => $count,
        'itemsPerPage' => self::COMMENTS_PER_PAGE,
        'currentPage' => $currentPage
    ]);


    $data = [
        'category_articles' => $category_articles,
        'categories_names' =>$categories_names,
        'pagination' => $pagination,

        'category_id' =>$category_id,
        'categories_ids' =>$categories_ids,

        'all_tags' =>$all_tags,
        'top5FeedbackAuthors' =>$top5FeedbackAuthors,
        'allFeedbackByAuthor' =>$allFeedbackByAuthor,
        'comments' =>$comments,
        'topArticles' =>$topArticles,

    ];
    return $this->render('feedbacker.phtml', $data);
}





}
