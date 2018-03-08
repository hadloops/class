<?php
namespace controller;
use framework\Page;
use model\UserModel;
use model\DetailsModel;
use model\ReplyModel;

class SearchController extends Controller
{
	public $user;
	public $details;
	public $reply;

	function __construct()
	{
		parent::__construct();
		$this->user = new UserModel();
		$this->details = new DetailsModel();
		$this->reply = new ReplyModel();
	}


	function cha()
	{

		//判断是否登录
		if (!empty($_SESSION['username'])) {
			$username = $_SESSION['username'];
			$u = $this->user->getByUsername($username);
			$udertype = $u[0]['udertype'];
			$pic = $u[0]['picture'];
			$this->assign('username',$username);
			$this->assign('pic',$pic);
			$this->assign('udertype',$udertype);
		}
		//帖子展示
		
		$name = $_GET['name'];
		$total = $this->details->mucount($name);

		$page = new Page('3',$total);
		$allPage = $page->allPage();
		$limit = $page->limit();
	    $message = $this->details->muhu1($name,$limit);
	    
	   	//最新发表
	   	$article = $this->details->field('title,id')->table('bg_details')->where('isdel=0')->order('id desc')->limit('0,10')->select();
	  	

	  	//点击排行
	  	$hits = $this->details->field('title,id')->table('bg_details')->where('isdel=0')->order('hits desc')->limit('0,5')->select();

	  	$this->assign('total',$total);
		$this->assign('allPage',$allPage);
	  	$this->assign('hits', $hits);
	  	$this->assign('article',$article);
	    $this->assign('message',$message);


		$this->display();
	}
}