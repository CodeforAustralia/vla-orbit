<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;

/**
 * Task Controller.
 * Controller for the task functionalities  
 * @author VLA & Code for Australia
 * @version 1.2.0
 * @see  Controller
 */
class TasksController extends Controller
{
    /**
     * Display a listing of task
     * @return view task information
     */        
    public function index()
    {
        
        $tasks = Task::all();
        //return $tasks;
        return view('tasks.index', compact('tasks'));
    }
    /**
     * Display a specific task
     * @param Task $task task
     * @return view single task information page
     */    
    public function show(Task $task) 
    {
        return view("tasks.show", compact("task"));
    }
}
