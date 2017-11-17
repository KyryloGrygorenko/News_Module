<?php

use Library\Route;

return  array(
    // site routes
    'default' => new Route('/', 'Default', 'index'),
    'index' => new Route('/index.php', 'Default', 'index'),
    'books_list' => new Route('/books', 'Book', 'index'),
//    'politics' => new Route('/politics', 'Politic', 'index'),
//    'analytics' => new Route('/analytics', 'Politic', 'indexAnalytics'),
//    'politics_articles' => new Route('/politics/articles/', 'Politic', 'show'),
//    'analytic_articles' => new Route('/politics/analytic/', 'Politic', 'showAnalytic'),
//    'articles_by_tags' => new Route('/politics/tags/', 'Politic', 'showByTag'),

    'politics' => new Route('/politics', 'Main', 'index'),
    'analytics' => new Route('/analytics', 'Main', 'indexAnalytics'),
    'politics_articles' => new Route('/politics/articles/', 'Main', 'show'),
    'analytic_articles' => new Route('/politics/analytic/', 'Main', 'showAnalytic'),
    'articles_by_tags' => new Route('/politics/tags/', 'Main', 'showByTag'),
    'feedbacker' => new Route('/feedbacker/', 'Main', 'ShowFeedbacks'),
    'article_filter' => new Route('/article_filter/', 'Main', 'ArticleFilter'),

    'books_page' => new Route('/book-{id}\.html', 'Book', 'show', array('id' => '[0-9]+') ),
    'feedback_page' => new Route('/feedback', 'Default', 'feedback'),
    'login' => new Route('/login', 'Security', 'login'),
    'logout' => new Route('/logout', 'Security', 'logout'),
    'cart' => new Route('/cart', 'Cart', 'index'),
    'cart_add' => new Route('/cart/add/{id}', 'Cart', 'add', array('id' => '[0-9]+')),
    
    
    'admin_default' => new Route('/admin', 'Admin\\Default', 'index'),
    'admin_categories' => new Route('/admin/categories', 'Admin\\Main', 'index'),
    'admin_add_articles' => new Route('/admin/add_article', 'Admin\\Main', 'add_article'),
    'admin_edit_articles' => new Route('/admin/edit_article', 'Admin\\Main', 'edit_article'),
    'admin_edit_feedback' => new Route('/admin/edit_feedback', 'Admin\\Main', 'edit_feedback'),
    'admin_approve_feedback' => new Route('/admin/approve_feedback', 'Admin\\Main', 'approve_feedback'),

    'api_add_to_cart' => new Route('/api/cart/add/{id}', 'API\\Cart', 'add', array('id' => '[0-9]+')),
    'api_save_feedback' => new Route('/api/feedback', 'API\\Feedback', 'save'),
    'api_books_list' => new Route('/api/books', 'API\\Book', 'index'),
    'api_book_item' => new Route('/api/book/{id}', 'API\\Book', 'show', array('id' => '[0-9]+')),
);