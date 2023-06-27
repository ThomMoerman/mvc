<?php

declare(strict_types = 1);

class ArticleController
{
    public function index() {
        // Load all required data
        $articles = $this->getArticles();

        // Load the view and pass the articles as a variable
        require 'View/articles/index.php';
    }

    private function getArticles() {
        // TODO: prepare the database connection
        $dbHost = 'localhost';
        $dbName = 'mvc_exercise';
        $dbUser = 'root';
        $dbPass = '';

        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

        // TODO: fetch all articles as $rawArticles (as a simple array)
        $statement = $pdo->query('SELECT id, title, description, publish_date FROM articles');
        $rawArticles = $statement->fetchAll(PDO::FETCH_ASSOC);

        $articles = [];
        foreach ($rawArticles as $rawArticle) {
            $id = (int) $rawArticle['id'];
            $articles[] = new Article($id, $rawArticle['title'], $rawArticle['description'], $rawArticle['publish_date']);
        }        

        return $articles;
    }

    public function show() {

    // Get the article ID from the URL parameters
    $articleId = $_GET['id'] ?? null;

        // Check if the article ID is provided
        if ($articleId) {
            // TODO: Retrieve the article data from the database using the ID
            
            // Database connection
            $dbHost = 'localhost';
            $dbName = 'mvc_exercise';
            $dbUser = 'root';
            $dbPass = '';

            $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);
            
            // Fetch the article data from the database
            $statement = $pdo->prepare('SELECT id, title, description, publish_date FROM articles WHERE id = :id');
            $statement->bindParam(':id', $articleId);
            $statement->execute();
            $rawArticle = $statement->fetch(PDO::FETCH_ASSOC);

            // Retrieve the previous article
            $previousArticle = $this->getPreviousArticle($articleId);

            // Retrieve the next article
            $nextArticle = $this->getNextArticle($articleId);

            // Check if the article exists
            if ($rawArticle) {
                $id = (int) $rawArticle['id'];
                // Create an instance of the Article class with the retrieved article data
                $article = new Article($id, $rawArticle['title'], $rawArticle['description'], $rawArticle['publish_date']);
                
                // Load the view and pass the $article object to the view
                require 'View/articles/show.php';
            } else {
                // Redirect to the articles index page if the article does not exist
                header('Location: index.php?page=articles-index');
            }
        } else {
            // Redirect to the articles index page if no article ID is provided
            header('Location: index.php?page=articles-index');
        }
    }

    private function getPreviousArticle($currentArticleId) {
        // TODO: Retrieve the previous article from the database based on the current article ID

        // Example implementation
        $dbHost = 'localhost';
        $dbName = 'mvc_exercise';
        $dbUser = 'root';
        $dbPass = '';

        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

        $statement = $pdo->prepare('SELECT id, title, description, publish_date FROM articles WHERE id < :currentId ORDER BY id DESC LIMIT 1');
        $statement->execute(['currentId' => $currentArticleId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $id = (int) $row['id'];
            return new Article($id, $row['title'], $row['description'], $row['publish_date']);
        }

        return null;
 }

    private function getNextArticle($currentArticleId) {
        // TODO: Retrieve the next article from the database based on the current article ID

        // Example implementation
        $dbHost = 'localhost';
        $dbName = 'mvc_exercise';
        $dbUser = 'root';
        $dbPass = '';

        $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass);

        $statement = $pdo->prepare('SELECT id, title, description, publish_date FROM articles WHERE id > :currentId ORDER BY id ASC LIMIT 1');
        $statement->execute(['currentId' => $currentArticleId]);
        $row = $statement->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $id = (int) $row['id'];
            return new Article($id, $row['title'], $row['description'], $row['publish_date']);
        }

        return null;
    }
}