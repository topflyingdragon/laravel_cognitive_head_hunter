@extends('layouts.app')

@section('specificJS')
    <script type="text/javascript" src="/js/manage.js"></script>
@stop

@section('content')
    <!-- top navigation bar -->
    @include('layouts.nav')
    <!-- page content -->
    <div id="modal" style="padding:10px;margin-top:10px;margin-bottom:10px; min-height: 600px;" class="row modal-content">
        <h1>Import jobs</h1>
        <div class="well">
            <form id="importForm" role="form">
                <div class="form-group row">
                    <div class="col-lg-12 col-xs-12">
                        <textarea id="text" rows="20" required="true" placeholder="Paste a JSON array with: [ { &quot;code&quot; : string , &quot;title&quot; : string , &quot;description&quot; : string } , ... ]" class="form-control"></textarea>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-offset-9 col-lg-3"><button id="importButton" type="button" class="btn btn-block">Import</button></div>
                </div>
            </form>
        </div>
        <div class="row">
            <div id="error" style="display:none;" class="col-lg-12 message">
                <p id="errorMsg" class="bg-danger"></p>
            </div>
            <div id="success" style="display:none;" class="col-lg-12 message">
                <p id="successMsg" class="bg-success"></p>
            </div>
            <div id="info" style="display:none;" class="col-lg-12 message">
                <p id="infoMsg" class="bg-info"></p>
            </div>
        </div>
        <div class="well">
            <ul id="results"></ul>
        </div>
        <script>$(document).ready(function() {
                console.log("ready");
            });

            $("#importButton").click(function() {
                $("#importButton").blur();
                $("#info").show();
                $('#results').empty();
                //console.log($("#text").val());
                var inputJson = JSON.parse($("#text").val());
                //console.log(json);

                inputJson.forEach(function(job, i, ar) {
                    $("#infoMsg").html('Processing ' + i + ' of ' + ar.length + '.');
                    console.log(i);
                    if (isJob(job)) {
                        $('<li>' + job.code + ': ' + job.title  + ' ignored.</li>').appendTo($('#results'));
                        $("html, body").animate({ scrollTop: $(document).height() }, 200);
                    } else {
                        importJob(job);
                    }

                });
                $("#info").hide();
                $("#error").hide();
                $("#success").hide();
                //JSON.stringify(job)

            });

            function importJob(job) {
                var $input = {
                    code: job.code,
                    title: job.title,
                    description: job.description.replace(/(\r\n|\n|\r)/gm," ")
                };

                $.ajax({
                    type: "POST",
                    url: '/db/jobs',
                    data: $input,
                    dataType: 'json',
                    success: function(data) {
                        $input.id = data._id;
                        addJobConcept($input);
                    },
                    error: function(err) {
                        console.log(err);
                    },
                    complete: function(data) {
                        $('<li>' + job.code + ': ' + job.title  + ' inserted.</li>').appendTo($('#results'));
                        //$("html, body").animate({ scrollTop: $(document).height() }, 200);
                    }
                });

            }

            function isJob(job) {
                var r;
                $.ajax({
                    type: "GET",
                    url: '/db/jobs?code=' + job.code,
                    dataType: 'json',
                    async: false,
                    success: function(data) {
                        //console.log(JSON.stringify(data));
                        r = (data.length > 0) ? true : false;
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
                return r;
            }
        </script>
    </div>
@stop
