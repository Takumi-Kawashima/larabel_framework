@extends('layouts.app');

@section('content');
  <!-- デフォルトだとこの中ではvue.jsが有効 -->
  <!-- example-component はLaravelに入っているコンポーネント -->
  <example-component
    title="{{ __('Practice').'「'.$drill->title.'」' }}"
    :drill="{{ $drill }}" category_name="{{ $drill->category_name }}"></example-component>
  </div>
@endsection