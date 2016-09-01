@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/demo.js"></script>
    <script type="text/javascript" src="/js/manage.js"></script>
    <script type="text/javascript" src="/js/analyze.js"></script>
    <script type="text/javascript" src="/js/validator.min.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <div class="row">
            <div id="error" style="display:none;" class="col-lg-12 message">
                <p id="errorMsg" class="bg-danger"></p>
            </div>
            <div id="success" style="display:none;" class="col-lg-12 message">
                <p id="successMsg" class="bg-success"></p>
            </div>
            <!-- Nav tabs-->
            <ul id="myTab" class="nav nav-pills">
                <li class="active"><a href="#candidates" data-toggle="tab">Candidates</a></li>
                <li><a href="#jobs" data-toggle="tab">Job positions </a></li>
            </ul>
            <!-- Tab panes-->
            <div class="tab-content">
                <div id="candidates" class="tab-pane active">
                    <div id="candidate-add-form" style="display:none;">
                        <h3>Add candidate</h3>
                        <div class="well">
                            <form id="candidateForm" role="form" action="javascript:handleCreation();" data-toggle="validator">
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12"><input id="txt-name" type="text" required="true" placeholder="Name" data-minlength="20" class="form-control"><span class="help-block">Minimum of 20 characters</span></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12"><textarea id="txt-profile" rows="15" required="true" placeholder="Profile description (min 200 words)" class="form-control"></textarea><span class="help-block">Minimum of 200 words - <span class="wordsCount small"></span></span></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3"><button id="candidate-cancel-btn" type="button" class="btn btn-block">Cancel</button></div>
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3"><button id="candidate-clear-btn" type="reset" class="btn btn-block">Clear</button></div>
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3 col-xs-push-3"><button id="candidate-add-btn" type="submit" class="btn btn-block">Add</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="candidates-loading" style="display:none;" class="row text-center">
                        <h2>&nbsp;</h2>
                        <img src="../images/watson.gif">
                    </div>
                    <div class="row">
                        <h2><span id="num-candidates"></span><button id="candidate-new-btn" type="button" class="btn pull-right"> <span class="visible-md visible-lg">New candidate</span><span class="fa fa-plus visible-sm visible-xs"></span></button></h2>
                        <p>Click at the candidate's name to find jobs.</p>
                    </div>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th># of words</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="candidates-table"></tbody>
                    </table>
                </div>
                <div id="jobs" class="tab-pane">
                    <div id="job-add-div" style="display:none;">
                        <h2>Add job</h2>
                        <div class="well">
                            <form id="jobForm" role="form" action="javascript:submitJob();" data-toggle="validator">
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12"><input id="code" type="text" required="true" placeholder="Position code (like GBS-0730663)" data-minlength="10" class="form-control"><span class="help-block">Minimum of 10 characters</span></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12"><input id="title" type="text" required="true" placeholder="Position tile (like Technical Support Representative)" data-minlength="100" class="form-control"><span class="help-block">Minimum of 100 characters</span></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-12 col-xs-12"><textarea id="description" rows="15" required="true" placeholder="Description text" data-minlength="500" class="form-control"></textarea><span class="help-block">Minimum of 500 characters</span></div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3"><button id="job-cancel-btn" type="button" class="btn btn-block">Cancel</button></div>
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3"><button id="job-clear-btn" type="reset" class="btn btn-block">Clear</button></div>
                                    <div class="col-lg-3 col-lg-push-3 col-xs-3 col-xs-push-3"><button id="job-add-btn" type="submit" class="btn btn-block">Add</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div id="jobs-loading" style="display:none;" class="row text-center">
                        <h2>&nbsp;</h2>
                        <img src="../images/watson.gif">
                    </div>
                    <div class="row">
                        <h2><span id="num-jobs"></span><button id="job-new-btn" type="button" class="btn pull-right"> <span class="visible-md visible-lg">New job</span><span class="fa fa-plus visible-sm visible-xs"></span></button></h2>
                        <p>Click at the job's title to find candidates.</p>
                    </div>
                    <table class="table table-condensed table-striped">
                        <thead>
                        <tr>
                            <th>Code</th>
                            <th>Title</th>
                            <th># of concepts</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody id="jobs-table"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
