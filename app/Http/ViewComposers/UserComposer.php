<?php

namespace App\Http\ViewComposers;

use Illuminate\Contracts\View\View;
use Illuminate\Contracts\Auth\Guard;

class UserComposer {
  protected $auth;

  public function __construct(Guard $auth) //認証系のいろんな情報が入ってくる
  {
    $this->auth = $auth;
  }

  public function compose(View $view)
  {
    //userという変数を使えるようにし、その中に$this->user()という値を詰めてビューに渡す。という定義の仕方になります。
    $view->with('user', $this->auth->user());
  }

}
