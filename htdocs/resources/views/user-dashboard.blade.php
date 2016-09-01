@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/demo.js"></script>
    <script type="text/javascript" src="/js/personality.js"></script>
    <script type="text/javascript" src="/js/textsummary.js"></script>
    <script type="text/javascript" src="/js/jquery.circliful.min.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <div style="min-height: 210px" class="row">
            <div id="userInfo" style="padding-top:25px;padding-bottom:55px;" class="col-lg-4 col-xs-12 text-center">
                <span class="col-lg-4 col-xs-12 text-center">
                    <h2><img src="{{$user['pictureUrl']}}" height="80px"></h2>
                </span>
                <span class="col-lg-7 col-xs-12 text-left">
                    <h2>{{$user['fullName']}}</h2>
                    <p>{{$user['headline']}}</p>
                </span>
            </div>
            <div id="concepts" style="display: none;" class="col-lg-offset-2 col-lg-6 col-xs-12">
                <H3>Your top concepts</H3>
                <div id="concept1" data-dimension="120" data-width="10" data-fontsize="15" data-fgcolor="#00B2EF" data-bgcolor="#eee" data-fill="#ddd" class="circle"></div>
                <div id="concept2" data-dimension="120" data-width="10" data-fontsize="15" data-fgcolor="#00B2EF" data-bgcolor="#eee" data-fill="#ddd" class="circle"></div>
                <div id="concept3" data-dimension="120" data-width="10" data-fontsize="15" data-fgcolor="#00B2EF" data-bgcolor="#eee" data-fill="#ddd" class="circle"></div>
                <div id="concept4" data-dimension="120" data-width="10" data-fontsize="15" data-fgcolor="#00B2EF" data-bgcolor="#eee" data-fill="#ddd" class="circle"></div>
                <!--div.well.content(style='display:none;')-->
            </div>
            <div id="summary" class="col-lg-8 col-xs-12">
                <div class="row form-group">
                    <div class="col-lg-12 col-xs-12">
                        <h3>Review and update your profile text</h3>
                        <textarea id="txt-profile" rows="15" required="true" placeholder="Please enter the text to analyze (minimum of 200 words)..." class="content form-control">{{$user['data']}}</textarea>
                        <span class="help-block">Minimum of 200 words - <span class="wordsCount small"></span></span>
                    </div>
                </div>
                <div class="row form-group">
                    <div class="col-xs-3"><button id="back-btn" type="button" onClick="javascript:history.back();" class="btn btn-block">Back</button></div>
                    <div class="col-xs-3"><button id="clear-btn" type="button" class="btn btn-block">Clear</button></div>
                    <div class="col-xs-4 col-xs-offset-2"><button id="analysis-btn" type="button" class="btn btn-block">Analyze</button></div>
                </div>
            </div>
        </div>
        <div style="padding-top:10px;" class="row">
            <div style="display:none;" class="col-lg-6 col-xs-12 text-justify personality">
                <div class="row">
                    <h3>{{$user['fullName']}}'s Personality*</h3>
                    <div class="well">
                        <div class="summary-div"></div>
                        <div style="color: gray" class="text-right"><em class="small">*Compared to most people who participated in our surveys.</em></div>
                    </div>
                </div>
                <!--div.row
                    div.col-lg-12
                        div#chart-->
                <div class="row">
                    <h2>Visualization</h2>
                    <p>Big 5, Needs and Values attributes</p>
                    <div class="col-lg-12">
                        <div id="vizcontainer" style="display:none;" class="well text-center results"></div>
                    </div>
                </div>
            </div>
            <div id="loading" style="display:none;" class="row col-lg-6 col-xs-12 text-center"><img src="/images/watson.gif"></div>
            <div class="col-lg-6 col-xs-12">
                <div id="positions" style="display: none;min-height:200px;" class="col-lg-12 col-xs-12">
                    <h3>Suggested jobs</h3>
                    <h4> <small>Click the job title to see other candidates.</small></h4>
                    <div class="content"></div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 col-md-6 col-xs-12">
            <div class="row">
                <div style="display:none;" class="form-group row loading1 text-center">
                    <h2>&nbsp;</h2>
                    <img src="/images/watson.gif">
                </div>
                <div style="display: none;" class="form-group row error">
                    <h2>&nbsp;</h2>
                    <div class="well">
                        <p class="errorMsg"></p>
                    </div>
                </div>
            </div>
        </div>
        <div id="raw" class="hidden">{{json_encode($user)}}</div>
    </div>
@stop
