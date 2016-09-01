@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/candidatesearch.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <div class="row">
            <div class="col-lg-12 col-md-6 col-xs-12">
                <div id="error" style="display:none;" class="col-lg-12 message">
                    <p id="errorMsg" class="bg-danger"></p>
                </div>
                <div id="success" style="display:none;" class="col-lg-12 message">
                    <p id="successMsg" class="bg-success"></p>
                </div>
                <span id="job_id" class="hidden">{{$jobid}}</span>
                <div id="loading" style="display:none;" class="row text-center">
                    <h2>&nbsp;</h2>
                    <img src="/images/watson.gif">
                </div>
                <div id="concepts" style="display:none;" class="row">
                    <div class="col-lg-12 col-xs-12">
                        <h2 id="job-title"></h2>
                    </div>
                    <div class="col-sm-8 col-xs-12">
                        <h4>Job description</h4>
                        <div id="job-description" style="overflow:auto;height:300px; padding: 10px; border:1px solid #ccc;background:#f2f2f2;margin-bottom:20px;"></div>
                    </div>
                    <div class="col-sm-4 col-xs-12">
                        <h4>Job concepts</h4>
                        <div id="concepts-list" style="overflow:auto;height:300px;" class="well text-center"></div>
                    </div>
                </div>
                <div class="row">
                    <div id="loading2" style="display:none;" class="col-lg-12 text-center">
                        <h2>&nbsp;</h2>
                        <img src="/images/watson.gif">
                    </div>
                    <div id="sug-cand" style="display:none;" class="col-lg-12 col-sm-12">
                        <h3>Suggested candidates</h3>
                        <h4><small>Click the candidate picture to open its personal dashboard.</small></h4>
                        <div id="candidates" class="text-center"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden"></div>
        <div id="template"></div>
        <label></label>
    </div>
@stop
