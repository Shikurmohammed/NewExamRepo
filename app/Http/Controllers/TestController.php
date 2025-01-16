<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use App\Models\Test;
use Flasher\Noty\Prime\Noty;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use function Flasher\Noty\Prime\noty;


class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        try {
            $group_data = Group::all();
            $test_data = Test::with('user', 'groups')->paginate(2);
            return view('pages.shared.test.view', compact('test_data', 'group_data'));
        } catch (\Exception $e) {
            //noty()->error('Error occured:' . $e->getMessage());
            return response()->json(['error' => 'Error occured:' . $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //dd($request);

        $test = new Test();

        $test->user_id = Auth::id();
        $test->name = $request->test_name;
        $description = trim(strip_tags($request->description));
        $test->description =  $description;
        if ($request->start)
            $test->start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
        if ($request->start)
            $test->end = Carbon::parse($request->end)->format('Y-m-d H:i:s');
        $test->duration = $request->duration;

        $test->score_right = $request->score_right ?? 0;
        $test->score_wrong = $request->score_wrong ?? 0;
        $test->score_threshold = $request->score_threshold ?? 0;
        //Hash the password
        if ($request->password)
            $test->password = Hash::make($request->exam_password);
        $test->score_unanswered = $request->score_unanswered ?? 0;
        $test->max_score = $request->max_score ?? 0;
        $test->random_questions_select = $request->random_questions_select ?? 0;
        $test->random_answers_select = $request->random_answers_select ?? 0;

        $test->mcma_partial_score = $request->mcma_partial_score ?? 0;
        $test->noanswer_enabled = $request->noanswer_enabled ?? 0;
        $test->comment_enabled = $request->comment_enabled ?? 0;
        $test->repeatable = $request->repateable ?? 0;

        $test->result_to_user = $request->result_to_user ?? 0;
        $test->report_to_user = $request->report_to_user ?? 0;
        $test->logout_on_timeout = $request->logout_on_timeout ?? 0;
        $test->menu_enabled = $request->menu_enabled ?? 0;

        //question_order_mode and answer_order_mode
        $test->created_by = Auth::user()->name;
        $group_ids = $request->group_id;
        $group_ids = [$group_ids];

        $is_test_name_exist = Test::where('name', $request->test_name)->exists();
        //dd($test);
        if ($is_test_name_exist) {
            noty()->error("Test name already exist!");
            return redirect()->back();
        } else {
            if ($group_ids) {
                $test->save(); //Save test and get test_id to save in test_groups table
                foreach ($group_ids as $group_id) {
                    $test->groups()->attach($group_id); //Save test_id and group_id test_groups
                }
                noty()->success("Test added successfully");
                return redirect()->back(); //('/view_test');
            } else {
                noty()->error("Error: ");
            }
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $test_data = Test::findOrFail($id);
        $group_data = Group::all();
        return view('shared.test.edit_test', compact('test_data', 'group_data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $testInuse = DB::table('tests_users', 'test_id', $id)->exists();
            if ($testInuse) {
                noty()->error("Test is in use!");
                return redirect()->back();
            }
            $test = Test::findOrFail($id);
            $score_threshold = $request->score_threshold ?? 0;
            $max_score = $request->max_score ?? 0;
            //score_threshold => pass score
            if ($score_threshold >  $max_score) {
                //If the pass point is greater than the maximum score, set the pass point to 60 % of the maximum score
                $score_threshold = 0.6 * $max_score;
            }
            //Hash the password
            if ($request->password)
                $test->password = Hash::make($request->exam_password);


            // Populate the test object with the request data
            $test->name = $request->test_name;
            $description = trim(strip_tags($request->description));
            $test->description =  $description;
            if ($request->start)
                $test->start = Carbon::parse($request->start)->format('Y-m-d H:i:s');
            if ($request->start)
                $test->end = Carbon::parse($request->end)->format('Y-m-d H:i:s');
            $test->duration = $request->duration;

            $test->score_right = $request->score_right ?? 0;
            $test->score_wrong = $request->score_wrong ?? 0;
            $test->score_threshold = $request->score_threshold ?? 0;
            $test->score_unanswered = $request->score_unanswered ?? 0;
            $test->max_score = $request->max_score ?? 0;
            $test->random_questions_select = $request->random_questions_select ?? 0;
            $test->random_answers_select = $request->random_questions_select ?? 0;

            $test->mcma_partial_score = $request->mcma_partial_score ?? 0;
            $test->noanswer_enabled = $request->noanswer_enabled ?? 0;
            $test->comment_enabled = $request->comment_enabled ?? 0;
            $test->repeatable = $request->repateable ?? 0;

            $test->result_to_user = $request->result_to_user ?? 0;
            $test->report_to_user = $request->report_to_user ?? 0;
            $test->logout_on_timeout = $request->logout_on_timeout ?? 0;
            $test->menu_enabled = $request->menu_enabled ?? 0;
            //question_order_mode and answer_order_mode
            $test->updated_by = Auth::user()->name;
            $group_ids = $request->group_id;
            $group_ids = [$group_ids];
            /*
              Delete previous group_id's from test_groups table where the test_id is $id,  This will maintain data integrity,
              so that we don't have to worry about orphaned data during updation or deletion of data.
            */
            DB::table('test_groups')->where('test_id', $id)->delete();
            if ($group_ids) {
                $test->save(); //Save test and get test_id to save in test_groups table
                foreach ($group_ids as $group_id) {
                    $test->groups()->attach($group_id); //Save test_id and group_id test_groups
                }
                noty()->success("Test updated successfully");
                return redirect()->back(); //('/view_test');
            } else {
                noty()->error("Error: ");
            }
            return redirect()->back();
        } catch (\Exception $e) {
        }
        //return redirect('/view_test');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $test = Test::find($id);
            if (! $test) {
                return response()->json(['error' => "Test with ID:: " . $id . " not found!"]);
            }
            //check if test is used in test_groups table
            $isTestUsedInTest_group = DB::table('test_groups')->where('test_id', $id)->exists();
            if ($isTestUsedInTest_group) {
                $test->groups()->detach(); //Detach group_id from test_group table
            }
            $test->delete();
            return response()->json(['success' => "Test with ID:: " . $id . " deleted successfully!"]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage() . "Failed to delete test with ID::" . $id]);
        }
    }

    public function lockTest($id)
    {
       try{
        $test = Test::find($id);
        $test->isLocked = 1;
        $test->save();
        return response()->json(['success' =>'Test locked successfully']);
       }catch(\Exception $e){
              return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
       }
    }
    public function unLockTest($id)
    {
       try{
        $test = Test::find($id);
        $test->isLocked = 0;
        $test->save();
        return response()->json(['success' =>'Test locked successfully']);
       }catch(\Exception $e){
              return response()->json(['err' => 'Fail: ' . $e->getMessage()]);
       }
    }
}
