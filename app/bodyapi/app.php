<?php
/**
 * Created by PhpStorm.
 * User: PVer
 * Date: 2017/8/28
 * Time: 9:18
 */
class app{
    public $G;
    public $data = array();
    public $sessionvars;

    public function __construct(&$G)
    {
        $this->G = $G;
        $this->ev = $this->G->make('ev');
        $this->tpl = $this->G->make('tpl');
        $this->sql = $this->G->make('sql');
        $this->pdosql = $this->G->make('pdosql');
        $this->db = $this->G->make('pepdo');
        $this->pg = $this->G->make('pg');
        $this->html = $this->G->make('html');
        $this->session = $this->G->make('session');
        $this->_user = $this->session->getSessionUser();
        $this->user = $this->G->make('user','user');
        $this->exam = $this->G->make('exam','exam');
        $this->basic = $this->G->make('basic','exam');
        $this->section = $this->G->make('section','exam');
        $this->question = $this->G->make('question','exam');
        $this->course = $this->G->make('course','course');
        $this->content = $this->G->make('content','course');
        $this->user = $this->G->make('user','user');
        $this->api = $this->G->make('api','bodyapi');
        $this->sound = $this->G->make('sound','sound');
        $this->favor = $this->G->make('favor','exam');
        $this->attach = $this->G->make('attach','document');
    }
}

?>