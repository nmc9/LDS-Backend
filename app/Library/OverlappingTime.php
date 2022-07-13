<?php

namespace App\Library;

class OverlappingTime {

	public function isOverlapping($time_frame, array $time_frames){

		$combined_time_frames = $this->createCombinedTimeFrames($time_frames);

		foreach ($combined_time_frames as $time_frame_2) {
			if($this->isEntirelyWithin($time_frame,$time_frame_2)){
				return true;
			}
		}
		return false;
	}

	public function createCombinedTimeFrames(array $time_frames){
		// Loop Every Combintation to check
		// If a combintation is found replace the LATER item
		// in the array (This way it can be checked again)
		// then replace the earlier with a null.
		for($i = 0; $i < count($time_frames); $i++){
			for($j = 0; $j < count($time_frames); $j++){
				if($this->shouldTryToCombine($i,$j,$time_frames)){
					if($this->areOverlappingTimeFrames($time_frames[$i],$time_frames[$j])){
						$time_frames[$j] = $this->combineTimeFrames($time_frames[$i],$time_frames[$j]);
						$time_frames[$i] = null;
					}
				}
			}
		}
		// Strip Nulls From the Array
		return array_values((array_filter($time_frames)));
	}

	/**
	 * Helper for createCombinedTimeFrames
	 * True if values are different and neither are NULL
	 */
	private function shouldTryToCombine($i,$j,$time_frames){
		return $j != $i && $time_frames[$j] != null && $time_frames[$i] != null;
	}

	public function combineTimeFrames($time_frame_1,$time_frame_2){
		return [
			"start" => $time_frame_1["start"] >= $time_frame_2["start"] ? $time_frame_2["start"] : $time_frame_1["start"],
			"end" => $time_frame_1["end"] >= $time_frame_2["end"] ? $time_frame_1["end"] : $time_frame_2["end"],
		];
	}

	public function areOverlappingTimeFrames($time_frame_1,$time_frame_2){
		return
		$this->isWithin($time_frame_1["start"],$time_frame_2) ||
		$this->isWithin($time_frame_1["end"],$time_frame_2) ||
		$this->isWithin($time_frame_2["start"],$time_frame_1) ||
		$this->isWithin($time_frame_2["end"],$time_frame_1);
	}

	protected function isEntirelyWithin($time_frame_1,$time_frame_2){
		return $time_frame_1["start"] >= $time_frame_2["start"]
		&&  $time_frame_2["end"] >= $time_frame_1["end"];
	}

	protected function isWithin($time,$time_frame){
		return $time_frame["end"] >= $time && $time >= $time_frame["start"];
	}


}