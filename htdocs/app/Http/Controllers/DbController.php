<?php

namespace App\Http\Controllers;

use App\Candidate;
use App\Job;
use Illuminate\Http\Request;

use App\Http\Requests;

class DbController extends Controller
{
    //------------------------------------------------ GET /db/candidates
    public function candidatesList(){
        $result = Candidate::all();
        return $result;
    }
    //------------------------------------------------ POST /db/candidates
    public function candidatesInsert(Request $request){
        $newId = "candidate_".uniqid("",true);
        $candidate = new Candidate();
        $candidate->_id = $newId;
        $candidate->name = $request->name;
        $candidate->text = $request->text;
        $candidate->profile = $request->profile;
        $candidate->save();
        return $candidate;
    }
    //------------------------------------------------ GET /db/candidates/by-name/{name}
    public function candidatesByName($name=false){
        if($name){
            $result = Candidate::where('name', '=', $name)->get();
        }else{
            $result = Candidate::all();
        }
        return $result;
    }
    //------------------------------------------------ DELETE /db/candidates/{name}
    public function deleteCandidatesById($id){
        $result = Candidate::where('_id', '=', $id)->delete();
        return $result;
    }

    //------------------------------------------------ GET /db/jobs
    public function jobsList(){
        $result = Job::all();
        return $result;
    }
    //------------------------------------------------ POST /db/jobs
    public function jobsInsert(Request $request){
        $job = new Job();
        $job->code = $request->code;
        $job->title = $request->title;
        $job->description = $request->description;
        $job->save();
        return $job;
    }
    //------------------------------------------------ GET /db/jobs/{id}
    public function jobsById($id){
        $result = Job::where('_id', '=', $id)->first();
        return $result;
    }
    //------------------------------------------------ GET /db/jobs/by-code/{code}
    public function jobsByCode($code){
        $result = Job::where('code', '=', $code)->first();
        return $result;
    }
    //------------------------------------------------ DELETE /db/jobs/by-code/{code}
    public function deleteJobsById($id){
        $result = Job::where('_id', '=', $id)->delete();
        return $result;
    }
}
