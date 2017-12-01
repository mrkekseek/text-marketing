<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Seance;
use Illuminate\Http\Request;

class AnswersController extends Controller
{
    public function save($id = false, $post = [])
    {
    	if ( ! empty($post['answers'])) {
    		foreach ($post['answers'] as $row) {
    			$answer = new Answer();
    			$answer->create($row);
			}
			$seance = Seance::where('id', $post['seance']['id']);
			$seance->update([
				'completed' => 1,
				'social_show' => ! empty($post['seance']['show_reviews']) ? $post['seance']['show_reviews'] : 0
			]);
    	}
    }
}
