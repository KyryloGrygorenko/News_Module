<?php

namespace Controller\Admin;

use Library\Controller;
use Model\Form\FeedbackForm;
use Model\FeedbackRepository;
use Model\Entity\Feedback;
use Library\Request;
use Library\Session;


class DefaultController extends Controller
{
    const NEWS_PER_PAGE = 5;
    const COMMENTS_PER_PAGE = 5;

    public function indexAction(Request $request)  {

        if($request->post('search') ){
            $tag=$request->post('search');
            $redirect_route='/politics/tags/?tag='. $tag;
            $this->get('router')->redirect( $redirect_route );
        };


        $repository = $this->get('repository')->getRepository('Feedback');
        $top5FeedbackAuthors=$repository->Top5FeedbackAuthors();
        $articles_limit=3;// how many top articles to show
        $topArticles=$repository->top3topics($articles_limit);


        $repository = $this->get('repository')->getRepository('Articles');
        $all_tags=$repository->findAllTags();
        $categories_count=$repository->countCategories();
        $analytic_articles=$repository->findAllAnalytics();
        $categories_names= $repository->getCategories_names();
        $categories_ids=$repository->getCategories_ids();

        for ($category_id=1; $category_id <=$categories_count ; $category_id++) {
            $category_articles[$category_id] = $repository->findLatestArticle ($category_id);
        };

        //$articles_count - Количество статей в слайдере

        $articles_count=4;
        $latest_articles=$repository->findLatestArticle2 ($articles_count);

        $latest_analytic_articles=$repository->findLatestAnalytics(0,5);


        $data = [
            'category_articles' => $category_articles,
            'latest_articles' => $latest_articles,
            'categories_count' => $categories_count,
            'categories_names' =>$categories_names,
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
}