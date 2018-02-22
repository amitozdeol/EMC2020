<?php

class AlgorithmTableSeeder extends Seeder
{

    public function run()
    {
        DB::table('algorithms')->delete();

        Algorithm::create([
          'id' => 1,
          'algorith_type' => 'immediate',
          'function_name' => 'on-off',
          'description' => 'Output Immediate Response to a Single Input',
          'number_of_inputs' => 1,
          'min_required_inputs' => 1,
        ]);

        Algorithm::create([
          'id' => 2,
          'algorith_type' => 'delayed',
          'function_name' => 'on-off',
          'description' => 'Output Delayed Response to a Single Input',
          'number_of_inputs' => 1,
          'min_required_inputs' => 1,
        ]);

        Algorithm::create([
          'id' => 3,
          'algorith_type' => 'immediate',
          'function_name' => 'on-off',
          'description' => 'Output Immediate Response to Multiple Inputs',
          'number_of_inputs' => 3,
          'min_required_inputs' => 3,
        ]);

        Algorithm::create([
          'id' => 4,
          'algorith_type' => 'delayed',
          'function_name' => 'on-off',
          'description' => 'Output Delayed Response to Multiple Inputs',
          'number_of_inputs' => 2,
          'min_required_inputs' => 2,
        ]);
    }
}
