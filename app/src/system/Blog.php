<?php

/**
 * Created by PhpStorm.
 * User: Chris
 * Date: 11/5/2019
 * Time: 9:57 PM
 */
include_once dirname(__FILE__) . '/vendor/autoload.php';
use Firebase\JWT\JWT;
use \PHPMailer\PHPMailer\PHPMailer;
class Blog extends System
{

    protected $title;
    protected $intro;
    protected $article;
    protected $thumbnail;


    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @return mixed
     */
    public function getIntro()
    {
        return $this->intro;
    }

    /**
     * @return mixed
     */
    public function getThumbnail()
    {
        return $this->thumbnail;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @param mixed $intro
     */
    public function setIntro($intro)
    {
        $this->intro = $intro;
    }

    /**
     * @param mixed $thumbnail
     */
    public function setThumbnail($thumbnail)
    {
        $this->thumbnail = $thumbnail;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }


    public function create(){
        try{
            $payload = JWT::decode($this->getTkn(), SECRETE_KEY, ['HS256']);
            $id = $payload->userId;
            $cat = $this->getCategory();
            $title = $this->getTitle();
            $intro = $this->getIntro();
            $article = $this->getArticle();
            $thumbnail = $this->getThumbnail();

            $sql = "INSERT INTO `blogpost`(`id`, `cat`, `title`, `intro`, `article`, `thumbnail`, `writer`, `post`, `date_created`, `date_updated`, `updater`) VALUES ('','$cat','$title','$intro','$article','$thumbnail','$id','0',now(),'','')";
            $qry = mysqli_query($this->con, $sql);
            if ($qry){
                $data = array('success' => true, 'statusCode' => CREATED, 'message'=> 'Blog Post added');
                return $data;
            }else{
                $data = array('success' => true, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "BLOG_CREATION_ERROR", 'message' => 'Blog creation failed. Reason: '.mysqli_error($this->con)));
                return $data;
            }
        }catch (\Exception $e){
            $data = array('success' => false, 'statusCode' => INTERNAL_SERVER_ERROR, 'error'=> array('type' => "SERVER_ERROR", 'message' => $e->getMessage()));
            return $data;
        }

    }

    public function update(){

    }

    public function articles(){
        $cat = $this->getCategory();
        $sql = "SELECT * FROM `blogpost` WHERE `post` = '1' AND `cat` = '$cat' GROUP BY `date_created` DESC ";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $post = array();
            while ($rs = mysqli_fetch_assoc($qry)){
                $article = array(
                    'id' => $rs['id'],
                    'title' => $rs['title'],
                    'thumbnail' => $rs['thumbnail'],
                    'intro' => $rs['intro'],
                    'article' => $rs['article'],
                    'writer' => $this->getWriter($rs['writer']),
                    'date_created' => $rs['date_created']
                );

                array_push($post, $article);
            }
            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'article'=> $post);
            return $data;
        }else{
            $data = array('success' => true, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "BLOGS_NOT_FOUND", 'message' => 'No blog Post '));
            return $data;
        }
    }

    public function getWriter($id){
        $sql = "SELECT * FROM `admin` WHERE `id` = '$id'";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $results = array();
            while($row = mysqli_fetch_assoc($qry)){
                $arr = array(
                    'name' => $row['name'],
                    'surname' => $row['surname'],
                    'email' => $row['email'],
                    'profile' => $row['profile'],
                    'category' => $row['category'],
                    'permission' => $row['permisions']
                );
                array_push($results ,$arr);
            }
            return $results;
        }else{
            return array();
        }
    }

    public function saveImage($id, $type, $filename){}

    public function Categories(){
        $sql = "SELECT * FROM `blogcategories` WHERE 1";
        $qry = mysqli_query($this->con, $sql);
        if (mysqli_num_rows($qry) > 0){
            $cat = array();
            while ($rs = mysqli_fetch_assoc($qry)){
                $ct = array(
                    'id' => $rs['id'],
                    'name' => $rs['name']
                );

                array_push($cat, $ct);
            }
            $data = array('success' => true, 'statusCode' => SUCCESS_RESPONSE, 'categories'=> $cat);
            return $data;
        }else{
            $data = array('success' => true, 'statusCode' => NOT_FOUND, 'error'=> array('type' => "CATEGORIES_NOT_FOUND", 'message' => 'No blog categories '));
            return $data;
        }
    }
}