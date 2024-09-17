<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Queue;
use ReflectionClass;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DispatchJob extends Command
{
    // Define the name and signature of the command
    protected $signature = 'job:dispatch {jobClass} {--modelParams=*}';

    // Define the command description
    protected $description = 'Dispatch a job of the given class name with optional model parameters';

    // Execute the console command
    public function handle()
    {
        // Retrieve the job class name from the command argument
        $jobClass = $this->argument('jobClass');
        $modelParams = $this->option('modelParams');

        // Check if the class exists and is a valid job
        if (!class_exists($jobClass)) {
            $this->error("The job class {$jobClass} does not exist.");
            return COMMAND::FAILURE;
        }

        if (!is_subclass_of($jobClass, 'Illuminate\Contracts\Queue\ShouldQueue')) {
            $this->error("The class {$jobClass} is not a valid job.");
            return COMMAND::FAILURE;
        }

        // Convert model parameters from string IDs to model instances
        $params = [];
        foreach ($modelParams as $modelParam) {
            list($modelClass, $id) = explode(':', $modelParam);

            if (!class_exists($modelClass)) {
                $this->error("The model class {$modelClass} does not exist.");
                return COMMAND::FAILURE;
            }

            try {
                $modelInstance = $modelClass::findOrFail($id);
                $params[] = $modelInstance;
            } catch (ModelNotFoundException $e) {
                $this->error("Model {$modelClass} with ID {$id} not found.");
                return COMMAND::FAILURE;
            }
        }

        // Use Reflection to dynamically instantiate the job with parameters
        $reflectionClass = new ReflectionClass($jobClass);
        $jobInstance = $reflectionClass->newInstanceArgs($params);

        // Dispatch the job
        Queue::push($jobInstance);

        $this->info("The job {$jobClass} has been dispatched with model parameters.");
        return COMMAND::SUCCESS;
    }
}
