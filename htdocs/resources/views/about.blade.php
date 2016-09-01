@extends('layouts.app')

@section('specificJS')
@stop

@section('content')
    <div style="background-color: #004468;" class="row nav top-nav">
        <div class="col-lg-3"><a href="/" class="left"><img src="/images/logo-transp.png" class="chh_logo"></a></div>
        <div class="col-lg-9">
            <ul class="nav navbar-nav right hidden-xs">
                <li> <a href="/">Home</a></li>
                <li> <a href="/about" style="padding-right: 40px !important;">About</a></li>
                <li class="visible-sm"><a href="https://github.com/alanbraz/cognitive-head-hunter" target="_blank"> <span class="fa fa-code-fork"> </span> Fork me</a></li>
            </ul>
            <ul class="nav navbar-nav nav-pills right visible-xs">
                <li> <a href="/"><span class="fa fa-home"></span></a></li>
                <li> <a href="/about"><span class="fa fa-info"></span></a></li>
                <li><a href="https://hub.jazz.net/git/alanbraz/job-hunters" target="_blank"><span class="fa fa-code-fork"></span></a></li>
            </ul>
        </div>
    </div>
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <h1>About</h1>
        <p> Cognitive Head Hunter is a cognitive based system to designed to help both a candidate and a HR professional make a good match more quickly and more reliably using Watson to read and extract cognitive information from the both the candidates providing a better match between them, not only by analyzing keywords but also by understanding the concepts outlined within a resume or a job posting.</p>
        <h2>Watson Services used</h2>
        <p>Concept Insights - The Concept Insights service links documents that you provide with a pre-existing graph of concepts based on Wikipedia (e.g. 'Cognitive Systems', 'Solar Energy', etc.). Users of this service can also search for documents that are relevant to a concept or collection of concepts by exploring concepts that are explicitly contained in your queries or are implicitly referenced through related concepts.
            <a href="http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/concept-insights.html" target="_blank" class="learn-more">More about this service here.</a></p>
        <p>Personality Insights - The Watson Personality Insights service uses linguistic analytics to extract a spectrum of cognitive and social characteristics from the text data that a person generates through blogs, tweets, forum posts, and more.
            <a href="http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/personality-insights.html" target="_blank" class="learn-more">More about this service here.</a></p>
        <h2>Operating instructions</h2>
        <p><a href="https://vimeo.com/ibmwatson/review/130135189/c27aa6828b" target="_blank">Watch the live demo video here.</a></p>
    </div>
@stop
