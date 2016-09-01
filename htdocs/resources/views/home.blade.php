@extends('layouts.app')

@section('specificJS')

@stop

@section('content')
    <div style="background-color: #004468;" class="row nav top-nav">
        <div class="col-lg-3">
            <a href="/" class="left">
                <img src="/images/logo-transp.png" class="chh_logo">
            </a>
        </div>
        <div class="col-lg-9">
            <ul class="nav navbar-nav right hidden-xs">
                <li>
                    <a href="/">Home</a>
                </li>
                <li>
                    <a href="/about" style="padding-right: 40px !important;">About</a>
                </li>
                <li class="visible-sm">
                    <a href="https://github.com/alanbraz/cognitive-head-hunter" target="_blank">
                        <span class="fa fa-code-fork"> </span> Fork me
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav nav-pills right visible-xs">
                <li>
                    <a href="/"><span class="fa fa-home"></span></a>
                </li>
                <li>
                    <a href="/about"><span class="fa fa-info"></span></a>
                </li>
                <li>
                    <a href="https://hub.jazz.net/git/alanbraz/job-hunters" target="_blank">
                        <span class="fa fa-code-fork"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content"></div>
    <div style="margin-top:40px;" class="jumbotron">
        <h1>Cognitive Head Hunter</h1>
        <p class="lead">Helps a candidate to find the next job position using its own LinkedIn profile or custom text.</p>
        <!--p.lead Cognitive Head Hunter is a cognitive application that uses Watson services to build an enhanced job openings knowledge based that will support candidates and HR professionals to find the best match between a candidate and a position.-->
        <!--h3 Choose one role and try it out:-->
        <div class="row">
            <div class="col-lg-6 col-xs-12 text-center">
                <div class="row">
                    <img src="/images/miguel.png" class="img-circle img-persona">
                </div>
                <div class="row">
                    <a id="btn-candidate" href="/analyze" role="button" class="btn btn-default btn-lg btn-block wrap">
                        Candidate looking for new job
                    </a>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 text-center">
                <div class="row">
                    <img src="/images/adrian.png" class="img-circle img-persona"></div>
                <div class="row">
                    <a id="btn-hrAnalyst" href="/manage" role="button" class="btn btn-default btn-lg btn-block wrap">
                        HR Professional matching jobs and candidates
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 col-xs-12">
            <div class="avatar img-container col-lg-2 col-xs-2">
                <img src="/images/ci.png">
            </div>
            <div class="col-lg-10 col-xs-10">
                <h2>Concept Insights</h2>
                <p>The Concept Insights service links documents that you provide with a pre-existing graph of concepts based on Wikipedia (e.g. 'Cognitive Systems', 'Solar Energy', etc.). Users of this service can also search for documents that are relevant to a concept or collection of concepts by exploring concepts that are explicitly contained in your queries or are implicitly referenced through related concepts.</p>
                <a href="http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/concept-insights.html" target="_blank" class="learn-more">More about this service</a>
            </div>
        </div>
        <div class="col-lg-6 col-xs-12">
            <div class="avatar img-container col-lg-2 col-xs-2">
                <img src="/images/pi.png">
            </div>
            <div class="col-lg-10 col-xs-10">
                <h2>Personality Insights</h2>
                <p>The Watson Personality Insights service uses linguistic analytics to extract a spectrum of cognitive and social characteristics from the text data that a person generates through blogs, tweets, forum posts, and more.</p>
                <a href="http://www.ibm.com/smarterplanet/us/en/ibmwatson/developercloud/personality-insights.html" target="_blank" class="learn-more">
                    More about this service
                </a>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#modal').hide();
            $('.top-nav').hide();
        });
    </script>

@stop

