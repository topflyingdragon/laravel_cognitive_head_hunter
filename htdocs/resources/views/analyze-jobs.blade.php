@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/jobs.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <div class="row">
            <h2 class="text-center">Job search for candidates </h2>
            <div style="padding:10px;margin-top:10px;margin-bottom:10px;" class="row">
                <div id="loading" style="display:none;" class="row col-lg-12 col-xs-12 text-center"><img src="/images/watson.gif"></div>
                <div class="row col-lg-12 col-xs-12">
                    <form>
                        <div class="row col-lg-12 col-xs-12">
                            <div>
                                <div class="form-group col-xs-6"><label for="txt-id">Job ID</label><textarea id="txt-id" type="text" required="true" placeholder="Please enter the job ID" class="form-control">{{$jobs['id']}}</textarea></div>
                                <div class="form-group col-xs-6"><label for="txt-name">Job Title</label><textarea id="txt-title" type="text" required="true" placeholder="Please enter the job title" class="form-control">{{$jobs['title']}}</textarea></div>
                                <div class="col-lg-12 col-xs-12">
                                    <label for="txt-profile">Description</label>
                                    <textarea id="txt-profile" rows="10" required="true" placeholder="Please enter the job description (minimum of 50 words)..." class="content form-control">{{$jobs['description']}}</textarea>
                                    <div class="text-right"><span class="wordsCount small"></span></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-lg-push-4 col-xs-4"><button type="reset" class="btn btn-block clear-btn">Clear</button></div>
                            <div class="col-lg-4 col-lg-push-4 col-xs-4 col-xs-push-4"><button id="continue-btn" type="button" class="btn btn-block analysis-btn">Continue</button></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
