@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="container" style="padding:30px 0;max-width:900px;">
    <h1 style="margin-bottom:10px;">{{ $page->title }}</h1>
    <article style="line-height:1.8;color:#333;">{!! $page->content !!}</article>
</div>
@endsection


