<?php





class ProgramRoutineController extends Controller{

    function __construct(){
    }


    public function handle_delete_routine($data){
        $deleted_routine =  $this->delete_routine( $data );
        if($deleted_routine) return $data;
        return false;
    }

    public function handle_add_routine($data){
        $added_routine =  $this->add_routine( $data );
        if($added_routine) return $data;
        return false;
    }


    /**
     *  Delete exercise
     *  @params $routine_id: index of routine
     *  @param $workoutRoutineId : index of exercise on routine + 1
     */
    public function delete_routine($data){
        
        $program_id = $data->programId;
        $routine_id = $data->routineId;
        $exercise_routine_id = ($data->workoutRoutineId )+ 1;
        $deleted = delete_row( "routines_".$routine_id, $exercise_routine_id ,$program_id);
        if($deleted === true) return true;
        return false;
    }


    public function add_routine($data){
        
        $program_id = $data->programId;
        $routine_id = $data->routineId;        
        $added = add_row( "routines_".$routine_id,$program_id);
        if($added === true) return true;
        return false;
    }

}