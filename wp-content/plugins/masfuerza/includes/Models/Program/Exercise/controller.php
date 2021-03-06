<?php





class ProgramExerciseController extends Controller{

    function __construct(){
    }


    public function handle_delete_exercise($data){
        $deleted_exercise =  $this->delete_exercise( $data );
        if($deleted_exercise) return $data;
        return false;
    }

    public function handle_add_exercises($data){
        $added_exercises =  $this->add_exercises( $data );
        if($added_exercises) return $data;
        return false;
    }


    /**
     *  Delete exercise
     *  @params $routine_id: index of routine
     *  @param $workoutRoutineId : index of exercise on routine + 1
     */
    public function delete_exercise($data){
        
        $program_id = $data->programId;
        $routine_id = $data->routineId;
        $exercise_routine_id = ($data->workoutRoutineId )+ 1;
        $deleted = delete_row( "routines_".$routine_id."_workouts", $exercise_routine_id ,$program_id);
        if($deleted === true) return true;
        return false;
    }


    public function add_exercises($data){
        
        $program_id = $data->programId;
        $routine_id = $data->routineId;
        $exercise_routine_id = ($data->workoutRoutineId )+ 1;
        
        $added = add_row( "routines_".$routine_id."_workouts", $exercise_routine_id ,$program_id);
    
        if($added === true) return true;
        return false;
    }




}