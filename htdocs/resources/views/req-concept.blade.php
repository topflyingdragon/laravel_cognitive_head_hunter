@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/req-concepts.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <div class="col-lg-12 col-md-6 col-xs-12">
            <div style="padding:10px;margin-top:10px;margin-bottom:10px;" class="modal-content">
                <div class="row">
                    <div id="error" style="display:none;" class="col-lg-12 message">
                        <p id="errorMsg" class="bg-danger"></p>
                    </div>
                    <div id="success" style="display:none;" class="col-lg-12 message">
                        <p id="successMsg" class="bg-success"></p>
                    </div>
                    <div class="col-lg-12">
                        <h2 id="job_id" class="hidden">{{ $id }}</h2>
                        <div id="loading" style="display:none;" class="row text-center">
                            <h2>&nbsp;</h2>
                            <img src="/images/watson.gif">
                        </div>
                        <div id="concepts" style="display:none;" class="row">
                            <h1>Select the required concepts for this job position:</h1>
                            <h2 id="job-title"></h2>
                            <p id="job-description"></p>
                            <div id="concepts-form" class="well">
                                <form role="form" action="javascript:submitJob();">
                                    <h3>Concepts extracted:</h3>
                                    <div id="concepts-list" class="form-group row"></div>
                                    <div class="form-group row">
                                        <div class="col-lg-9">&nbsp;</div>
                                        <div class="col-lg-3"><button type="submit" class="btn btn-block">Set concepts</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="hidden">
                <div id="template">
                    <div class="checkbox col-lg-4"><input type="checkbox" name="conpect" value="abc"><label></label></div>
                </div>
            </div>
        </div>
    </div>
@stop
