<?php
/*
	Author : Colin,
	Creation time : 2015-8-1 10:30:21
	FileType :分类类
	FileName :Page.class.php
*/
namespace MyClass\libs;
class Page{
	private $total;					//总记录
	private $pagesize;				//每页显示多少条
	private $limit;					//LIMIT
	private $page; 					//当前页码
	private $pagenum;				//总页数
	private $url;					//页面地址
	private $bothnum;				//两边数字保留的量
	private $page_url;					//整体URL
	private $url_model;				//URL模式

	/**
     * 构造方法
     * @author Colin <15070091894@163.com>
     */
	public function __construct($total , $pagesize){
		$this->total = $total ? $total : 1;
		$this->pagesize = $pagesize;
		$this->pagenum = ceil($this->total / $this->pagesize);
		$this->url_model = Config('URL_MODEL');
		//处理url
		if($this->url_model == 1){
			$this->page_url = "&page=%d";
		}elseif($this->url_model == 2){
			$this->page_url = "/page/%d";
		}
		$this->page = $this->setPage();
		$this->limit = "LIMIT ".($this->page-1)*$this->pagesize.",$this->pagesize";
		$this->url = $this->setUrl();
		$this->bothnum = 2;
	}
	
	/**
     * 拦截器
     * @author Colin <15070091894@163.com>
     */
	public function __get($key){
		return $this->$key;
	}
	
	/**
     * 获取当前页面 setPage()
     * @author Colin <15070091894@163.com>
     */
	private function setPage(){
		//排除page=0 或page为空
		if(!empty($_GET['page'])){
			//排除负数 和 非法字符
			if($_GET['page'] > 0){
				//排除比总页数大的
				if($_GET['page'] > $this->pagenum){
					return $this->pagenum;
				}else{
					return $_GET['page'];
				}
			}else{
				return 1;
			}
		}else{
			return 1;
		}
	}
	
	/**
     * 智能获取地址
     * @author Colin <15070091894@163.com>
     */
	private function setUrl(){
		//获取地址
		$_url = $_SERVER['REQUEST_URI'];
		//解析url
		$_par = parse_url($_url);
		if($this->url_model == 1){
			//判断url 里面是否包含query
			if(isset($_par['query'])){
				//分离url  至 query变量
				parse_str($_par['query'],$_query);
				//删除掉包含page的参数
				unset($_query['page']);
				//重组url
				$_url = $_par['path'].'?'.http_build_query($_query);
			}
		}elseif($this->url_model == 2){
			$explode = array_merge(array_filter(explode('/', $_url)));
			$count = count($explode);
			if(($count % 2) == 0){
                for ($i = 0; $i < $count ; $i += 2) { 
                    $new_pams[$explode[$i]] = $explode[$i + 1];
                }
            }
            if(array_key_exists('page', $new_pams)){
            	unset($new_pams['page']);
            }
            $_url = params($new_pams);
		}
		
		return $_url;
	}
	
	/**
     * 数字分页
     * @author Colin <15070091894@163.com>
     */
	private function pageList(){
		$_pagelist = '';
		//这一块还需好好理解
		for($i=$this->bothnum;$i>=1;$i--){
			$_page = $this->page-$i;
			if($_page < 1) continue;
			$_pagelist .= '<li><a href="'.$this->url.sprintf($this->page_url , $_page).'">'.$_page.'</a></li>';
		}
		$_pagelist .= '<li class="active"><a href="javascript:void(0);">'.$this->page.'</a></li>';
		for($i=1;$i<=$this->bothnum;$i++){
			$_page = $this->page+$i;
			if($_page > $this->pagenum) break;
			$_pagelist .= '<li><a href="'.$this->url.sprintf($this->page_url , $_page).'">'.$_page.'</a></li>';
		}
		return $_pagelist;
	}
	
	/**
     * 首页
     * @author Colin <15070091894@163.com>
     */
	private function first(){
		//如果当前页码 > 两边分页保留量+1
		if($this->page > $this->bothnum+1){
			return ' <a href="'.$this->url.'">1</a> ...';
		}
	}
	
	/**
     * 上一页
     * @author Colin <15070091894@163.com>
     */
	private function prev(){
		if($this->page == 1){
			return '<span class="disabled">上一页</span>';
		}
		return ' <a href="'.$this->url.sprintf($this->page_url , ($this->page-1)).'">上一页</a> ';
	}
	
	/**
     * 下一页
     * @author Colin <15070091894@163.com>
     */
	private function next(){
		if($this->page == $this->pagenum){
			return '<span class="disabled">下一页</span>';
		}
		return ' <a href="'.$this->url.sprintf($this->page_url , ($this->page+1)).'">下一页</a> ';
	}
	
	/**
     * 尾页
     * @author Colin <15070091894@163.com>
     */
	private function last(){
		//如果总页码-当前页码 > 两边保持的两边分页保留量
		if($this->pagenum - $this->page > $this->bothnum){
			return ' ...<a href="'.$this->url.sprintf($this->page_url , $this->pagenum).'">'.$this->pagenum.'</a> ';	
		}
	}
	
	/**
     * 对外公开的方法。分页信息
     * @author Colin <15070091894@163.com>
     */
	public function showpage(){
		$_page = '<ul class="pagination">';
		// $_page .= $this->first();
		$_page .= $this->pageList();
		// $_page .= $this->last();
		// $_page .= $this->prev();
		// $_page .= $this->next();
		$_page .= '</ul>';
		return $_page;
	}
}
?>