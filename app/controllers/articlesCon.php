<?php

namespace controllers;

use models\category;
use function Symfony\Component\String\b;

class articlesCon
{
    public function getNewArticle(\Base $base)
    {

        $sessionUserId = $base->get("SESSION.user[id]");
        $sessionUserRole = $base->get("SESSION.user[role]");

        if (!$sessionUserId) {
            $base->reroute('login/');
            return;
        }

        if ($sessionUserRole == 3 || $sessionUserRole == 4) {
            // Uživatel nemá přístup na tuto stránku
            echo "Na tuto stránku nemáte přístup. Můžete požádat administrátora o změnění role.";
        }else{
            echo \Template::instance()->render('newArticle.php');
        }
    }

    public function getNewCategory(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if (!$user) {
            $base->reroute('not-found/');
            return;
        }

        $sessionUserId = $base->get("SESSION.user[id]");
        $sessionUserRole = $base->get("SESSION.user[role]");

        if (!$sessionUserId) {
            $base->reroute('login/');
            return;
        }

        if ($sessionUserRole == 3 || $sessionUserRole == 4) {
            echo "Na tuto stránku nemáte přístup. Můžete požádat administrátora o změnění role.";
        }else{
            echo \Template::instance()->render('newCategory.php');
        }

    }


    public function postNewCategory(\Base $base)
    {
        $sessionUserRole = $base->get("SESSION.user[role]");
        if ($sessionUserRole == 3 || $sessionUserRole == 4) {
            echo "Na tuto stránku nemáte přístup. Můžete požádat administrátora o změnění role.";
        }else{
            $name = $base->get('POST.name');
            $description = $base->get('POST.description');

            $existingCategory = new \models\category();
            $existingCategory->load(array('name=?', $name));

            if ($existingCategory->dry()) {
                $category = new \models\category();
                $category->name = $name;
                $category->description = $description;

                $category->save();

                $base->reroute('categories/');
            }

        }
    }

    public function categories(\Base $base)
    {
        echo \Template::instance()->render('categoryViewer.php');
    }

    public function searchCategories(\Base $base)
    {
        $query = $base->get('GET.query');
        $category = new \models\category();

        $result = $query ? $category->find(array('name LIKE ?', '%' . $query . '%')) : $category->find();

        $output = '<ul>';
        foreach ($result as $cat) {
            $output .= '<li class="category"><input type="checkbox" name="categoryCheckbox" onclick="onlyOneCheckbox(this)" value="' . $cat->id . '"> <b>' . htmlspecialchars($cat->name) . '</b>- ' . htmlspecialchars($cat->description) . '</li>';
        }
        $output .= '</ul>';

        echo $output;
    }


    public function postNewArticle(\Base $base)
    {
        $sessionUserRole = $base->get("SESSION.user[role]");
        if ($sessionUserRole == 3 || $sessionUserRole == 4) {
            echo "Na tuto stránku nemáte přístup. Můžete požádat administrátora o změnění role.";
        }else{
            $name = $base->get('POST.name');
            $content = $base->get('POST.content');
            $category = $base->get('POST.category');
            $authorId = $base->get('SESSION.user[id]');

            $user = new \models\user();
            $user->load(array('id=?', $authorId));



            $article = new \models\article();
            $article->name = $name;
            $article->content = $content;
            $article->category = $category;
            $article->user = $user;
            $article->save();

            $articleId = $article->id;

            $uploadError = '';

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $_FILES['image'];
                if ($image['size'] <= 25 * 1024 * 1024 && $image['type'] == 'image/jpeg') {
                    $targetDir = __DIR__ . '/../../articleImages/'; // Upravte cestu podle vašeho nastavení
                    if (!file_exists($targetDir)) {
                        mkdir($targetDir, 0777, true);
                    }
                    $targetFile = $targetDir . $articleId . '.jpg'; // Název souboru je ID článku

                    if (!move_uploaded_file($image['tmp_name'], $targetFile)) {
                        $uploadError = 'Nastala chyba při nahrávání souboru.';
                    }
                } else {
                    $uploadError = 'Obrázek musí být ve formátu .jpg a mít maximálně 25 MB.';
                }
            }

            if (!empty($uploadError)) {
                echo $uploadError;
            } else {
                $base->reroute('/');
            }
        }

    }


    public function fetchCategories(\Base $base)
    {
        $category = new \models\category();
        $categories = $category->find();

        $output = '';
        foreach ($categories as $cat) {
            $output .= '<option value="' . $cat->id . '">' . htmlspecialchars($cat->name) . '</option>';
        }

        echo $output;
    }

    public function fetchArticles(\Base $base)
    {
        $articleModel = new \models\article();
        $articles = $articleModel->find(null, array('order' => 'id DESC'));

        $userId = $base->get('SESSION.user[id]');
        $output = '<div class="articles">';

        foreach ($articles as $article) {
            $output .= '<div class="article">';
            $output .= '<h2><a class="name" href="article/' . $article->id  .'">' . htmlspecialchars($article->name) . '</a></h2>';

            $imagePath = 'articleImages/' . $article->id . '.jpg';
            if (file_exists($imagePath)) {
                $output .= '<a class="name" href="article/' . $article->id  .'">' . '<img class="image" src="http://localhost/writertest/' . $imagePath . '" alt="' . htmlspecialchars($article->name) . '"></a><br>';
            }

            $category = $article->category;
            $output .= '<b>' . htmlspecialchars($category->name) . '</b><br>    ';

            $output .= substr(htmlspecialchars($article->content), 0, 300) . '...';

            if ($article->user->id == $userId) {
                $output .= '<br><small class="small">Psáno vámi</small>';
            }
            $output .= '</div>';
        }

        $output .= '</div>';
        echo $output;
    }



    public function searchArticles(\Base $base)
    {
        $query = $base->get('GET.query');
        $articleModel = new \models\article();
        $articles = $articleModel->find(
            array('name LIKE ? OR content LIKE ?', '%' . $query . '%', '%' . $query . '%'),
            array('order' => 'id DESC')
        );

        $userId = $base->get('SESSION.user[id]');
        $output = '<div class="articles">';

        foreach ($articles as $article) {
            $output .= '<div class="article">';
            $output .= '<h2><a class="name" href="article/' . $article->id  .'">' . htmlspecialchars($article->name) . '</a></h2>';

            $imagePath = 'articleImages/' . $article->id . '.jpg';
            if (file_exists($imagePath)) {
                $output .= '<a class="name" href="article/' . $article->id  .'">' . '<img class="image" src="http://localhost/writertest/' . $imagePath . '" alt="' . htmlspecialchars($article->name) . '"></a><br>';
            }

            $category = $article->category;
            $output .= '<b>' . htmlspecialchars($category->name) . '</b><br>    ';

            $output .= substr(htmlspecialchars($article->content), 0, 300) . '...';

            if ($article->user->id == $userId) {
                $output .= '<br><small class="small">Psáno vámi</small>';
            }
            $output .= '</div>';
        }

        $output .= '</div>';
        echo $output;
    }

    public function article(\Base $base, $params)
    {
        $user = $base->get("SESSION.user[id]");
        if (!$user) {
            $base->reroute('login/');
            return;
        }

        $articleId = $params['id'];

        $base->set('id', $articleId);

        $imagePath = 'articleImages/' . $articleId . '.jpg';

        if (file_exists($imagePath)) {
            $base->set('imageUrl', 'http://localhost/writertest/articleImages/' . $articleId . '.jpg');
        } else {
            $base->set('imageUrl', '');
        }


        echo \Template::instance()->render('article.php');
    }

    public function getArticle(\Base $base, $params)
    {

        $articleId = $params['id'];

        $articles = new \models\article();
        $article = $articles->findone(["id=?", $articleId]);

        if ($article) {
            $category = $article->category;

            echo json_encode(array(
                'name' => $article->name,
                'categories' => $category->name,
                'content' => $article->content

            ));
        } else {
            echo json_encode(array(
                'name' => 'Nenalezeno',
                'categories' => [],
                'content' => 'Nenalezeno'
            ));
        }
    }

    public function fetchUserArticles(\Base $base)
    {
        $user = $base->get("SESSION.user[id]");
        if (!$user) {
            $base->reroute('not-found/');
            return;
        }

        $userId = $base->get('SESSION.user[id]');
        $articleModel = new \models\article();
        $articles = $articleModel->find(['user=?', $userId], array('order' => 'id DESC'));

        $output = '<div class="articles">';

        foreach ($articles as $article) {
            $output .= '<div class="article">';
            $output .= '<h2><a class="name" href="article/' . $article->id  .'">' . htmlspecialchars($article->name) . '</a></h2>';
            $category = $article->category;
            $output .= '<b>' . $category->name . '</b>';
            $output .= '<p>' . substr(htmlspecialchars($article->content), 0, 300) . '...</p>';
            $output .= '<button class="button" onclick="confirmDelete(' . $article->id . ')">Smazat článek</button> ';
            $output .= '<a href="edit-article/' . $article->id .'"><button class="button">Upravit článek</button></a>';
            $output .= '</div>';
        }

        $output .= '</div>';
        echo $output;
    }

    public function fetchArticlesByCategories(\Base $base, $params)
    {
        $categoryId = $params['id'];
        $userId = $base->get('SESSION.user[id]');

        $categories = new \models\category();

        $articleModel = new \models\article();
        $articles = $articleModel->find(['category=?', $categoryId], array('order' => 'id DESC'));

        $output = '<div class="articles">';

        foreach ($articles as $article) {
            $output .= '<div class="article">';
            $output .= '<h2><a class="name" href="article/' . $article->id  .'">' . htmlspecialchars($article->name) . '</a></h2>';
            $category = $article->category;
            $output .= '<b>' . $category->name . '</b>';
            $output .= '<p>' . substr(htmlspecialchars($article->content), 0, 300) . '...</p>';
            if ($article->user->id == $userId) {
                $output .= '<br><small class="small">Psáno vámi</small>';
            }
            $output .= '</div>';
        }

        $output .= '</div>';

        echo $output;
    }

    public function deleteArticle(\Base $base, $params)
    {
        $user = $base->get("SESSION.user[id]");
        if (!$user) {
            echo 'Tuto akci nemůžete provést! Není to váš článek!';
        }else{
            $articleId = $params['id'];
            $articleModel = new \models\article();
            $article = $articleModel->findone(['id=?', $articleId]);

            if($article->user->id != $user){
                echo 'Tuto akci nemůžete provést! Toto není váš článek!';
            }else{
                if ($article) {
                    $article->erase();
                    echo json_encode(['success' => true]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Článek nebyl nalezen.']);
                }
            }
        }

    }

    public function editArticle(\Base $base, $params)
    {
        $user = $base->get("SESSION.user[id]");
        $articleId = $params['id'];
        $articleModel = new \models\article();
        $article = $articleModel->findone(['id=?', $articleId]);

        if($article->user->id != $user){
            echo 'Tuto akci nemůžete provést! Není to váš článek!';
        }else{
            if ($article) {
                $category = $article->category;

                $base->set('id', $article->id);
                $base->set('name', $article->name);
                $base->set('category', $category->name);
                $base->set('content', $article->content);

                $imagePath = 'articleImages/' . $articleId . '.jpg';

                if (file_exists($imagePath)) {
                    $base->set('imageUrl', 'http://localhost/writertest/articleImages/' . $articleId . '.jpg');
                } else {
                    $base->set('imageUrl', '');
                }
                echo \Template::instance()->render('editArticle.php');
            } else {
                $base->set('name', 'nenalezeno');
                $base->set('category', 'nenalezeno');
                $base->set('content', 'nenalezeno');
                echo \Template::instance()->render('editArticle.php');

            }


        }


    }

    public function postEditArticle(\Base $base)
    {
        $id= $base->get("POST.id");
        $name= $base->get("POST.name");
        $content= $base->get("POST.content");

        $articleModel = new \models\article();
        $article = $articleModel->findone(['id=?', $id]);

        if(!$article->user->id == $base->get['SESSION.user.id']){
            echo 'Tuto akci nemůžete provést! Toto není váš článek!';
        }else{
            $article->name = $name;
            $article->content = $content;

            $article->save();

            $base->reroute('/article/' . $id);
        }



    }

    public function newComment(\Base $base)
    {




        $user = $base->get("POST.user");
        $content = $base->get("POST.commentContent");
        $article = $base->get("POST.article");

        $comment = new \models\comments();

        $comment->user = $user;
        $comment->content = $content;
        $comment->article = $article;

        $comment->save();


    }

    public function fetchComments(\Base $f3, $params) {
        $articleId = $params['id'];
        $commentsModel = new \models\comments();
        $comments = $commentsModel->find(array('article=?', $articleId), array('order' => 'id DESC'));


        $result = array();
        foreach ($comments as $comment) {
            $result[] = array(
                'id' => $comment->id,
                'content' => $comment->content,
                'userName' => $comment->user->name . ' ' .$comment->user->surname,
            );
        }

        echo json_encode($result);
    }


}