@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/demo.js"></script>
    <script type="text/javascript" src="/js/analyze.js"></script>
    <script type="text/javascript" src="/js/validator.min.js"></script>
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
        <h2 class="col-lg-12 col-xs-12 text-center">Import your LinkedIn profile</h2>
        <div style="padding:10px;margin-top:10px;margin-bottom:10px;" class="row">
            <div class="row col-lg-12 text-center"><a href="/auth" class="visible-lg text-center"><img src="/images/Sign-In-Large---Default.png"></a><a href="/auth" class="hidden-lg text-center"><img src="/images/linkedin.png" width="50px"></a></div>
        </div>
        <h2 class="text-center col-lg-12 col-xs-12">Or input your profile text below:</h2>
        <div style="padding:10px;margin-top:10px;margin-bottom:10px;" class="row">
            <div id="loading" style="display:none;" class="row col-lg-12 col-xs-12"><img src="/images/watson.gif"></div>
        </div>
        <div class="row">
            <form role="form" action="javascript:handleCreation();" data-toggle="validator">
                <div class="form-group"><label for="txt-name">Name</label><input id="txt-name" type="text" required="true" placeholder="Please enter your full name" data-minlength="20" class="form-control"><span class="help-block">Minimum of 20 characters</span></div>
                <div class="form-group"><label for="txt-profile">Profile</label><textarea id="txt-profile" rows="15" required="true" placeholder="Please enter the text to analyze (minimum of 200 words)..." class="content form-control"></textarea><span class="help-block">Minimum of 200 words - <span class="wordsCount small">		</span></span></div>
                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-4"><button type="reset" class="btn btn-block">Clear</button></div>
                        <div class="col-xs-4 col-xs-offset-4"><button id="continue-btn" type="submit" class="btn btn-block">Continue</button></div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop

